<?php

namespace App\Http\Controllers;

use App\Http\Resources\GuardianBlockResource;
use App\Http\Resources\GuardianResource;
use App\Models\GuardianBan;
use App\Models\GuardianBlock;
use Illuminate\Http\Request;
use  App\Models\Guardians;
use App\Models\Notifications;
use Illuminate\Support\Facades\DB;
use  App\Models\User;
class GuardianController extends Controller
{
    //

//###################BLOCK GUARDIAN ################################




//#################################################################
/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function blockGuardian(Request $request){
$conditions2= array(
    //user_id  = ward_id
    'ward_id'=> $request->ward_id,
    'ward_username'=>$request->ward_username,
        //user_id = guardian_id
    'guardian_id'=>$request->guardian_id,
    'guardian_username'=> $request->guardian_username );

    //check if ward exists
    $ward = User::where('id', $request->ward_id)->first();
    if($ward == null){
        $response['responseMessage'] = 'ward does not exist';
        $response['responseCode'] = -1001;
        return response()->json($response, 200);
    }


       //check of user has been previously blocked

       $guardblock = GuardianBlock::where($conditions2)->first();
        if($guardblock){
         $response['responseMessage'] = 'ward has been previously blocked';
        $response['responseCode'] = -1001;
        return response()->json($response, 200);

}

    //check if following

    $guardDetails = Guardians::where($conditions2)->first();
    if ($guardDetails) {

        //if following exists
        //unfollow
        $id = DB::table('guardians')->where($conditions2)->pluck('id');
        $guard = Guardians::findOrFail($id);
        $guard->each->delete();

          //block user from follow
          $guardianBan = new GuardianBlock();
          $guardianBan->ward_id= $request->ward_id;
          $guardianBan->ward_username= $request->ward_username;
          $guardianBan->guardian_id=$request->guardian_id;
          $guardianBan->guardian_username=$request->guardian_username;
          $guardianBan->save();
        $response['responseMessage'] = 'ward blocked';
        $response['responseCode'] = 00;
        return response()->json($response, 200);
    }else{
        $response['responseMessage'] = 'ward not found';
        $response['responseCode'] = -1001;
        return response()->json($response, 200);

    }
}


    public function fetchBlockedUser($id){
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = GuardianBlockResource::collection( GuardianBlock::where('guardian_id',$id)->get());
    return response()->json($response, 200);
    }


    public function unblockUsers(Request $request){
        $conditions2= array(
            //user_id  = ward_id
            'ward_id'=> $request->ward_id,
            'guardian_id'=>$request->guardian_id );


              //check if ward exists
    $ward = User::where('id', $request->ward_id)->first();
    if($ward == null){
        $response['responseMessage'] = 'ward does not exist';
        $response['responseCode'] = -1001;
        return response()->json($response, 200);
    }

    //delete blocked ward
    $id = DB::table('guardian_blocks')->where($conditions2)->pluck('id');
    $guard = GuardianBlock::findOrFail($id);
    $guard->each->delete();


    $response['responseMessage'] = 'ward unblocked';
    $response['responseCode'] = 00;
    return response()->json($response, 200);
    }

//#################################################################


    //#################################FOLLOW GUARDIAN ###########################################
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function followGuardian(Request $request)
    {

        //check if guardian has blocked user
        $conditions= array(
            'ward_id'=> $request->ward_id,
            'guardian_id'=>$request->guardian_id
        );
        $guardblock = GuardianBlock::where($conditions)->first();
        if($guardblock){
         $response['responseMessage'] = 'you have been blocked from following this guardian';
        $response['responseCode'] = -1001;
        return response()->json($response, 200);

}

        $conditions1= array(
            'ward_id'=> $request->ward_id,
            'ward_username'=>$request->ward_username,
            'guardian_id'=>$request->guardian_id,
            'status'=>'pending',
            'guardian_username'=> $request->guardian_username );



            $guardDetails = Guardians::where($conditions1)->first();
            if ($guardDetails) {

                //guardian already followed
                $response['responseMessage'] = 'follow request awaiting approval';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }




        $conditions2= array(
            'ward_id'=> $request->ward_id,
            'ward_username'=>$request->ward_username,
            'guardian_id'=>$request->guardian_id,
            'status'=>'confirmed',
            'guardian_username'=> $request->guardian_username );




            $guardDetails = Guardians::where($conditions2)->first();
        if ($guardDetails) {

            //guardian already followed
            $response['responseMessage'] = 'guardian already followed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }else{
            $id=  $request->ward_id;
          User::findOrFail($id);
            $guard = new Guardians();
            $guard->ward_id = $request->ward_id;
            $guard->ward_username= $request->ward_username;
            $guard->guardian_id=$request->guardian_id;
            $guard->guardian_username=$request->guardian_username;
            $guard->status='pending';
            $guard->save();

 //send notification to ward
 $notId=$request->guardian_id;
 $title= "Follow Request From ".$request->ward_username;
 $content=$request->ward_username. " wants to follow you as a guardian.";
 $this->sendNotification($notId,$title,$content,"3");


            $response['responseMessage'] = 'follow request sent';
            $response['responseCode'] = 00;
            return response()->json($response, 200);

       }

    }






 //#################################UNFOLLOW GUARDIAN ###########################################
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unfollowGuardian(Request $request)
    {


        $conditions= array(
            'ward_id'=> $request->ward_id,
            'ward_username'=>$request->ward_username,
            'guardian_id'=>$request->guardian_id,
            'guardian_username'=> $request->guardian_username );


        $id = DB::table('guardians')->where($conditions)->pluck('id');


            $guard = Guardians::findOrFail($id);

            if ($guard->each->delete()) {
                $response['responseMessage'] = 'guardian unfollowed';
                $response['responseCode'] = 00;
                return response()->json($response, 200);

            }else{
                $response['responseMessage'] = 'failed';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }


    }


 //#########################################################################################################


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

 //################################GUARDIAN ACCEPT FOLLOWER ###########################################
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acceptFollow(Request $request)
    {


        $conditions= array(
            'ward_id'=> $request->ward_id,
            'ward_username'=>$request->ward_username,
            'guardian_id'=>$request->guardian_id,
            'status'=>'pending',
            'guardian_username'=> $request->guardian_username );




            $guardDetails = Guardians::where($conditions)->first();
        if ($guardDetails) {

            //update status
            $reqdata['status'] = 'confirmed';
            $id = DB::table('guardians')->where($conditions)->pluck('id');
            $guard = Guardians::where('id', $id)->update($reqdata);


            //send notification to ward
            $notId=$request->ward_id;
            $title= "You are now following ".$request->guardian_username;
            $content=$request->guardian_username. " Has accepted you follow request as guardian and would be getting your distress messages.";
            $this->sendNotification($notId,$title,$content,"6");

            //guardian already followed
            $response['responseMessage'] = 'follow request accepted';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }else{

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

    }

################################################################################################################################################








     //################################GUARDIAN IGNORE FOLLOWER ###########################################
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ignoreFollow(Request $request)
    {


        $conditions= array(
            'ward_id'=> $request->ward_id,
            'ward_username'=>$request->ward_username,
            'guardian_id'=>$request->guardian_id,
            'status'=>'pending',
            'guardian_username'=> $request->guardian_username );




            $guardDetails = Guardians::where($conditions)->first();
        if ($guardDetails) {

            //update status
            $reqdata['status'] = 'declined';
            $id = DB::table('guardians')->where($conditions)->pluck('id');
            $guard = Guardians::where('id', $id)->update($reqdata);

            //guardian already followed
            $response['responseMessage'] = 'follow request declined';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }else{

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

    }

################################################################################################################################################





//################################Guardians View Wards ###########################################

 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWards($id)
    {
        $conditions= array(
            'guardian_id'=>$id
        );

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = GuardianResource::collection(Guardians::where($conditions)->get());
        return response()->json($response, 200);


    }

//#############################################################################################




















//################################VIEW PENDING GUARDIANS ###########################################

 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingGuardians($id)
    {
        $conditions= array(
            'guardian_id'=>$id,
            'status'=>'pending'
        );


        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = GuardianResource::collection(Guardians::where($conditions)->get());
        return response()->json($response, 200);


    }

//#############################################################################################










 //################################VIEW ALL GUARDIANS ###########################################

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllGuardians()
    {
        $guards = Guardians::paginate(50);

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = GuardianResource::collection($guards);

        return response()->json($response, 200);
    }

//##################################################################################






//####################### View Wards Guardians ####################################



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllWardGuardians($id)
    {
        $conditions= array(
            'ward_id'=>$id,
        );


        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = GuardianResource::collection(Guardians::where($conditions)->get());
        return response()->json($response, 200);
    }
//#####################################################################3

}
