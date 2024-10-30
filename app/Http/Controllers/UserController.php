<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\ActivationMail;
use App\Models\CommunityMember;
use App\Models\NotificationPreference;
use App\Models\Notifications;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function checkVersion()
    {

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['version'] = 3.0;

        return response()->json($response, 200);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllUsers()
    {
        $users = User::paginate(10);

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = UserResource::collection($users);

        return response()->json($response, 200);
    }

    public function getCsrfToken()
    {
        // Generate a CSRF token
        $csrfToken = Crypt::encrypt(csrf_token());

        return response()->json(['csrf_token' => $csrfToken]);
    }

    //##########################################SIGNUP ############################################

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {

        $rules = array(
            'email' => 'required|max:50|email',
            'password' => 'required|min:6',
            'firstname' => 'required |min:4',

        );
        $_userId = DB::table('users')->where('email', $request->email)->value('id');
        if ($_userId != null) {
            $response['responseMessage'] = 'Looks like that email is already claimed! Try another one.';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['Data'] = $validator->errors();
            return response()->json($response, 200);
        } else {
            $activationCode = $this->generatePin(5);
            $currentTime = Carbon::now();
            $user = new User();
            $user->firstname = $request->firstname;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->status = "inactive";
            $user->activation_code = $activationCode;
            $user->activation_time = $currentTime->addMinutes(7);
            $user->imei = $request->imei;
            $email = $request->email;
            try {
                $this->sendEMail($email, $activationCode);
            } catch (\Throwable $th) {
                //throw $th;
            }

            if ($user->save()) {

                //send notification to user
                $notId = DB::table('users')->where('email', $request->email)->value('id');
                $title = "Welcome to SIYP! ";
                $content = "Hello " . $request->username . "! \n We are Happy to have you on board! \n       SIYP is a social media safe space created by Whisper to Humanity to house young Nigerian feminists, build an unbreakable bond and create a generation of young people excited about equality in humanity and willing to be humans.";

                NotificationPreference::create(['user_id' =>$notId]);
                try {
                    $this->sendNotification($notId, $title, $content, "8");
                } catch (\Throwable $th) {
                    //throw $th;
                }

                try {
                    $user = User::where(['email'=> $request->email, 'username' => $request->username ])->first();
                    $conditions_ = [
                        'user_id' => $user->id,
                        'community_id' => 1,
                    ];
                    if (CommunityMember::where($conditions_)->count()) {

                    } else {
                        CommunityMember::create([
                            'user_id' => $user->id,
                            'community_id' => 1,
                        ]);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }

                $response['responseMessage'] = 'success';
                $response['responseCode'] = 00;
                $response['data'] = new UserResource($user);

                return response()->json($response, 200);
            }
        }
    }

    //##############################################################################################

    //###############################SEND NOTIFICATION ###############################################
    public function sendNotification($userId, $title, $content, $mobile_id)
    {
        $msg = new Notifications();
        $msg->admin_id = "2020";
        $msg->user_id = $userId;
        $msg->title = $title;
        $msg->content = $content;
        $msg->status = 'unread';
        $msg->mobile_id = $mobile_id;
        $msg->save();
    }

//########################################################

    //################################EMAIL################################################

    //Send Email
    public function sendEMail($email, $activationCode)
    {

        $details = [
            'title' => 'Confirm your email address',
            'body' => 'Your confirmation code is below — enter it in the SIYP Mobile App',
            'code' => $activationCode,
        ];
        Mail::to($email)->send(new ActivationMail($details));
    }

    //Generate Pin
    public function generatePin($number)
    {
        $digits = $number;
        return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }
    //############################################################################################

    //#################################ACCOUNT ACTIVATION ###########################################

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function activateAccount(Request $request)
    {

        $inputCode = $request->activationCode;
        $user = User::where(['activation_code' => $inputCode, 'phone' => $request->phone])->first();
        if ($user) {

            $currentTime = Carbon::now();
            $futureTime = Carbon::parse($user->activation_time);

            if ($currentTime->greaterThan($futureTime)) {
                $response['responseMessage'] = 'The OTP has become invalid due to expiration. Kindly proceed to generate a new OTP.';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }
            //update status
            $reqdata['status'] = 'active';
            $reqdata['email_verified_at'] = Carbon::now();
            $user = User::where('activation_code', $inputCode)->update($reqdata);

            $response['responseMessage'] = 'account activated';
            $response['responseCode'] = 00;
            return response($response, 200);
        } else {

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }

    //Resend Activation Code
    public function resendActivationCode(Request $request)
    {
        $phone = $request->phone;
        $activationCode = $this->generatePin(5);

        //update activationCode
        $currentTime = Carbon::now();
        $reqdata['activation_code'] = $activationCode;
        $reqdata['activation_time'] = $currentTime->addMinutes(7);
        $user = User::where('phone', $phone)->update($reqdata);

        $email = User::where('phone', $phone)->first()->email;
        try {
            $this->sendEMail($email, $activationCode);
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($user) {
            $response['responseMessage'] = ' OTP activation has been sent!';
            $response['responseCode'] = 00;
            $response['activationCode'] = $activationCode;
            return response($response, 200);
        } else {
            $response['responseMessage'] = 'Unfortunately, the OTP has not sent!';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }

    //###############################################################################################################

    //###########################USER FORGET PASSWORD

    //Activate Account
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function forgetPassword(Request $request)
    {
        $email = $request->email;

        $activationCode = $this->generatePin(5);

        //update activationCode
        $currentTime = Carbon::now();
        $reqdata['activation_code'] = $activationCode;
        $reqdata['activation_time'] = $currentTime->addMinutes(7);
        $user = User::where('email', $email)->update($reqdata);
        $phone = User::where('email', $email)->first()->phone;
        try {
            $this->sendForgetEMail($email, $activationCode);
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($user) {
            $response['responseMessage'] = 'Activation mail sent';

            $response['responseCode'] = 00;
            $response['phone'] = $phone;
            return response($response, 200);
        } else {
            $response['responseMessage'] = 'Email address doesn\'t exist';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }

    //Send Email
    public function sendForgetEMail($email, $activationCode)
    {

        $details = [
            'title' => 'Password Forget',
            'body' => 'Your confirmation code is below — enter it in the SIYP Mobile App',
            'code' => $activationCode,
        ];
        Mail::to($email)->send(new ActivationMail($details));
    }

    //Save Language
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveLanguage(Request $request)
    {
        $userId = auth()->user()->id;

        $reqdata['language'] = $request->language;
        $user = User::where('id', $userId)->update($reqdata);
        $lang = "";
        switch ($request->language) {
            case ('en'):
                $lang = "English";
                break;
            // case ('es'):
            //     $lang = "Spanish";
            //     break;
            // case ('sw'):
            //     $lang = "Swahili";
            //     break;
            // case ('dn'):
            //     $lang = "Danish";
            //     break;
            // case ('en'):
            //     $lang = "English";
            //     break;
            // case ('fr'):
            //     $lang = "French";
            //    break;
            default:
                $lang = "";
        }

        if ($user) {
            $response['responseMessage'] = 'Language has been set to ' . $lang;
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        } else {
            $response['responseMessage'] = 'Ohh Snap! Something went wrong';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

    }

    //Enter New Password

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {

        $rules = array(
            'phone' => 'required',
            'password' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['Data'] = $validator->errors();
            return response()->json($response, 200);
        } else {

            $reqdata['password'] = Hash::make($request->password);
            $reqdata['status'] = 'active';

            $conditions = array(
                'phone' => $request->phone,

            );

            $user = User::where($conditions)->update($reqdata);
            if ($user) {

                $response['responseMessage'] = 'Yipee! Password change was successful';
                $response['responseCode'] = 00;
                return response()->json($response, 200);
            } else {

                $response['responseMessage'] = 'Ohh Snap! Something went wrong';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }
        }
    }

    //##################################################################################################

    //############Search User by Username #############################

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchUserByUsername(Request $request)
    {

        $username = $request->username;

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = User::query()->where('username', $username)->get();

        return response()->json($response, 200);

    }

    //########################################  USER Search By Like #################################################

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchUser(Request $request)
    {

        $username = $request->username;
        $users = User::query()->where('username', 'LIKE', "%{$username}%")->get();
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = UserResource::collection($users);
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userChangePassword(Request $request)
    {
        $user_id = $request->user_id;
        $oldPassword = $request->old_password;
        $newPassword = $request->new_password;
        $confirmPassword = $request->confirm_password;

    }

    public function checkStatus($email, $username, $phone)
    {
        if ($email != null) {

            $conditions = array(
                'email' => $email,

            );

        } else if ($phone != null) {

            $conditions = array(
                'phone' => $phone,

            );
        } else {
            $conditions = array(
                'username' => $username,

            );
        }

        $status = DB::table('users')->where($conditions)->value('status');
        return $status;
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $phone = $request->phone;
        $conditions = [];

        if ($email != null) {

            $conditions = array(
                'email' => $request->email,
                'status' => 'active',

            );

        } else if ($phone != null) {
            $conditions = array(
                'phone' => $request->phone,
                'status' => 'active',

            );

        } else {
            $conditions = array(
                'username' => $request->username,
                'status' => 'active',

            );
        }

        $uemail = $request->email;
        $uuname = $request->username;
        $uphone = $request->phone;
        $status = $this->checkStatus($uemail, $uuname, $uphone);

        if ($status == 'inactive') {

            if ($email != null) {

                $con = array(
                    'email' => $uemail,

                );

            } else {
                $con = array(
                    'username' => $uuname,

                );
            }

            $user = User::where($con)->first();
            $token = $user->createToken('my-app-token')->plainTextToken;
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['user'] = $user;
            $response['token'] = $token;

            return response($response, 200);
        }

        $user = User::where($conditions)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            $response['responseMessage'] = 'These credentials do not match our records.';
            $response['responseCode'] = -1001;

            return response($response, 200);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['user'] = new UserResource($user);
        $response['token'] = $token;

        return response($response, 200);

    }

    //#################################################################################################

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUser($id)
    {
        $user = User::findOrFail($id);
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = new UserResource($user);
        return response($response, 200);
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request)
    {
        // Prepare the data for update
        $reqdata = [
            'firstname' => $request->fullname,
            'dob' => $request->dob,
        ];

        // Update the user and fetch the updated user data
        $userUpdated = User::where('id', auth()->user()->id)->update($reqdata);

        // Prepare response
        if ($userUpdated) {
            $response = [
                'responseMessage' => 'success',
                'responseCode' => 0, // Use 0 instead of 00
                'data' => User::findOrFail(auth()->user()->id), // Fetch updated user
            ];
        } else {
            $response = [
                'responseMessage' => 'failed',
                'responseCode' => -1001,
            ];
        }

        return response()->json($response, 200);
    }


//#######################################USER
    /**
     * Show the form for creating a new resource.
     *@param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeUserPassword(Request $request)
    {

        $oldPassword = $request->old_password;

        $conditions = array(
            "id" => $request->user_id,
        );

        $user = User::where($conditions)->first();

        if (!$user || !Hash::check($request->old_password, $user->password)) {

            $response['responseMessage'] = 'Incorrect Old Password';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);

        } else {
            $rules = array(
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',

            );
            $user_id = $request->user_id;
            $reqdata['password'] = $request->password;
            $reqdata['confirm_password'] = $request->confirm_password;

            $validator = Validator::make($reqdata, $rules);
            if ($validator->fails()) {

                $response['responseMessage'] = 'failed';
                $response['responseCode'] = -1001;
                $response['Data'] = $validator->errors();
                return response()->json($response, 200);
            } else {
                $reqdata = [];
                $reqdata['password'] = Hash::make($request->password);
                $user = User::where('id', $user_id)->update($reqdata);
                if ($user) {

                    $response['responseMessage'] = 'success';
                    $response['responseCode'] = 00;
                    $response['data'] = User::findOrFail($user_id);

                    return response()->json($response, 200);
                } else {

                    $response['responseMessage'] = 'failed';
                    $response['responseCode'] = -1001;

                    return response()->json($response, 400);
                }
            }

        }
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *@param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */

    // public function verifyEmail(Request $request)
    // {
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->delete()) {
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] = new UserResource($user);
            return response()->json($response, 204);
        } else {
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 400);
        }
    }

    #####################################UPLOAD PROFILE PIC ########################################################

    public function userPic(Request $request, $userID)
    {
        $user = User::findOrFail($userID);

        if ($request->file('photo')->isValid()) {
            $this->checkPic($userID);
        } else {
            $response['responseMessage'] = 'Photo invalid';
            $response['responseCode'] = -1001;
            return response()->json($response, 400);
        }

        $input = $request->all();
        $extension = $request->file('photo')->extension();

        $fileName = time() . "." . $extension;
        $fileName = "userID(" . $userID . ")" . $fileName;
        $path = $request->file('photo')->move(public_path("/users-images"), $fileName);

        $photoURL = url('/' . $fileName);

        $data = [
            'profile_pic' => $fileName,
        ];

        user::where('id', $userID)->update($data);

        return response()->json(['url' => $photoURL], 200);
    }

    //Check if Pic Exists and delete it
    public function checkPic($userID)
    {

        $pic = user::where('id', $userID)->get('profile_pic');
        $image_path = env("APP_URL") . "/public/users-images/" . $pic[0]->pic;
        if ($pic != null) {
            if (File::exists(public_path('users-images/' . $pic[0]->pic))) {
                File::delete(public_path('users-images/' . $pic[0]->pic));
            } else {
            }
        }
    }

    public function viewPic($id)
    {
        $DP = DB::table('users')->where('id', $id)->pluck('profile_pic');

        $pic = $DP[0];
        if ($DP != null) {
            return response()->download(env("APP_URL") . "/users-images/" . $pic, 'User Image');
        } else {
            return response()->download(env("APP_URL") . "/users-images/avatar.jpg", 'User Image');
        }
    }

    ################################################################################################################

}
