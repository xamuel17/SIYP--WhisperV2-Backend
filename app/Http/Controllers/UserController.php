<?php

namespace App\Http\Controllers;

use  App\Models\User;
use  App\Models\Notifications;
use App\Http\Resources\UserResource;
use App\Mail\ActivationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class UserController extends Controller
{

    public function checkVersion(){

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
            'email'  =>      'required|max:50|email|unique:users',
            'username' =>    'required|max:20|min:3|unique:users',
            'password' =>    'required|min:6',
            'firstname' => 'required |min:4',
            'lastname' => 'required |min:4',
            'country' => 'required|min:4',
            'confirm_password' => 'required|same:password'

        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['Data'] = $validator->errors();
            return response()->json($response, 200);
        } else {
            $activationCode = $this->generatePin(5);
            $user = new User();
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->sex = $request->sex;
            $user->dob = $request->dob;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->country = $request->country;
            $user->status = "inactive";
            $user->activation_code = $activationCode;
            $user->imei = $request->imei;
            $email = $request->email;
             $this->sendEMail($email, $activationCode);
            if ($user->save()) {



 //send notification to user
 $notId= DB::table('users')->where('email',  $request->email)->value('id');
 $title= "Welcome to Whisper! ";
 $content="Hello ".$request->username. "! \n We are Happy to have you on board! \n       Whisper is a social media safe space created by Whisper to Humanity to house young Nigerian feminists, build an unbreakable bond and create a generation of young people excited about equality in humanity and willing to be humans.";
 $this->sendNotification($notId,$title,$content,"8");


                $response['responseMessage'] = 'success';
                $response['responseCode'] = 00;
                $response['data'] = new UserResource($user);

                return response()->json($response, 200);
            }
        }
    }

    //##############################################################################################



    //###############################SEND NOTIFICATION ###############################################
public function sendNotification($userId,$title,$content,$mobile_id){
    $msg = new Notifications();
    $msg->admin_id ="2020" ;
    $msg->user_id= $userId;
    $msg->title=$title;
    $msg->content=$content;
    $msg->status='unread';
    $msg->mobile_id=$mobile_id;
    $msg->save();
}



//########################################################











    //################################EMAIL################################################



    //Send Email
    public function sendEMail($email, $activationCode)
    {

        $details = [
            'title' => 'Confirm your email address',
            'body' => 'Your confirmation code is below — enter it in the Whisper Mobile App',
            'code' => $activationCode
        ];
        Mail::to($email)->send(new ActivationMail($details));
    }




    //Generate Pin
    public function generatePin($number)
    {
        $digits = $number;
return rand(pow(10, $digits-1), pow(10, $digits)-1);


        // // Generate set of alpha characters
        // $alpha = array();
        // for ($u = 65; $u <= 90; $u++) {
        //     // Uppercase Char
        //     array_push($alpha, chr($u));
        // }
        // // Get random alpha character
        // $rand_alpha_key = array_rand($alpha);
        // $rand_alpha = $alpha[$rand_alpha_key];

        // // Add the other missing integers
        // $rand = array($rand_alpha);
        // for ($c = 0; $c < $number - 1; $c++) {
        //     array_push($rand, mt_rand(0, 9));
        //     shuffle($rand);
        // }

        // return implode('', $rand);
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
        $user = User::where('activation_code', $inputCode)->first();
        if ($user) {

            //update status
            $reqdata['status'] = 'active';
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
        $email = $request->email;
        $activationCode = $this->generatePin(5);

        //update activationCode
        $reqdata['activation_code'] =  $activationCode;
        $user = User::where('email', $email)->update($reqdata);
        $this->sendEMail($email, $activationCode);
        if ($user) {
            $response['responseMessage'] = 'activation mail sent';
            $response['responseCode'] = 00;
            $response['activationCode'] = $activationCode;
            return response($response, 200);
        } else {
            $response['responseMessage'] = 'failed';
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
        $reqdata['activation_code'] =  $activationCode;
        $user = User::where('email', $email)->update($reqdata);
        $this->sendForgetEMail($email, $activationCode);
        if ($user) {
            $response['responseMessage'] = 'activation mail sent';

            $response['responseCode'] = 00;
            $response['activationCode']=$activationCode;
            return response($response, 200);
        } else {
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }



    //Send Email
    public function sendForgetEMail($email, $activationCode)
    {

        $details = [
            'title' => 'Password Forget',
            'body' => 'Your confirmation code is below — enter it in the Whisper Mobile App',
            'code' => $activationCode
        ];
        Mail::to($email)->send(new ActivationMail($details));
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
            'activation_code' => 'required',
            'password' =>    'required|min:6',
            'confirm_password' => 'required|same:password'

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
                'email' =>  $request->email,
                'activation_code' => $request->activation_code

            );

            $email = $request->email;
            $user = User::where($conditions)->update($reqdata);
            if ($user) {

                $response['responseMessage'] = 'success';
                $response['responseCode'] = 00;
               // $response['data'] = User::findOrFail($email);

                return response()->json($response, 200);
            } else {

                $response['responseMessage'] = 'failed';
                $response['responseCode'] = -1001;


                return response()->json($response, 400);
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
       public function searchUserByUsername(Request $request){

        $username=$request->username;


      $response['responseMessage'] = 'success';
      $response['responseCode'] = 00;
      $response['data'] = User::query()->where('username',$username)->get();

      return response()->json($response, 200);


    }








    //########################################  USER Search By Like #################################################








        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
       public function searchUser(Request $request){

        $username=$request->username;


    //   $response['responseMessage'] = 'success';
    //   $response['responseCode'] = 00;
    //   $response['data'] = User::query()->where('username','LIKE', "%{$username}%")->get();

    //   return response()->json($response, 200);

$users = User::query()->where('username','LIKE', "%{$username}%")->get();
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
    public function userChangePassword(Request $request){
        $user_id = $request->user_id;
        $oldPassword= $request->old_password;
        $newPassword= $request->new_password;
        $confirmPassword = $request->confirm_password;


    }




function checkStatus($email,$username){
 if($email != null){

    $conditions = array(
        'email' =>  $email


    );

}else{
    $conditions = array(
        'username' =>  $username


    );
}


     $status = DB::table('users')->where($conditions)->value('status');
     return $status;
}




    function login(Request $request)
    {
        $email= $request->email;






if($email != null){

    $conditions = array(
        'email' =>  $request->email,
        'status' => 'active'

    );

}else{
    $conditions = array(
        'username' =>  $request->username,
        'status' => 'active'

    );
}

$uemail = $request->email;
$uuname = $request->username;
$status = $this->checkStatus($uemail,$uuname);





if ($status == 'inactive'){

   if($email != null){

    $con = array(
        'email' =>  $uemail


    );

}else{
    $con = array(
        'username' =>  $uuname


    );
}

    $user = User::where($con)->first();
   $token = $user->createToken('my-app-token')->plainTextToken;
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
                $response['user']=$user;
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
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rules = array(
            'firstname' => 'required|min:4',
            'lastname' => 'required|min:4',
            'sex' => 'required',
            'dob' => 'required',
            'phone' => 'required|min:11',
            'country' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['Data'] = $validator->errors();
            return response()->json($response, 200);
        } else {

            // $reqdata = $request->all();

            $reqdata['firstname'] = $request->firstname;
            $reqdata['lastname'] = $request->lastname;
            $reqdata['sex'] = $request->sex;
            $reqdata['dob'] = $request->dob;
            $reqdata['phone'] = $request->phone;
            $reqdata['country'] = $request->country;



            $user = User::where('id', $id)->update($reqdata);
            if ($user) {

                $response['responseMessage'] = 'success';
                $response['responseCode'] = 00;
                $response['data'] = User::findOrFail($id);

                return response()->json($response, 200);
            } else {

                $response['responseMessage'] = 'failed';
                $response['responseCode'] = -1001;


                return response()->json($response, 200);
            }
        }
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

$conditions= array(
    "id"=>$request->user_id
);


$user = User::where($conditions)->first();

if (!$user || !Hash::check($request->old_password, $user->password)) {



    $response['responseMessage'] = 'Incorrect Old Password';
    $response['responseCode'] = -1001;
    return response()->json($response, 200);

        }else{
        $rules = array(
            'password' =>    'required|min:6',
            'confirm_password' => 'required|same:password'

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
        }else{
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
        $image_path = public_path("/public/users-images/" . $pic[0]->pic);
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

        $pic =  $DP[0];
        if ($DP != null) {
            return response()->download(public_path("users-images/" . $pic), 'User Image');
        } else {
            return response()->download(public_path("users-images/avatar.JPG"), 'User Image');
        }
    }


    ################################################################################################################







}
