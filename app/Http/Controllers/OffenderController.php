<?php

namespace App\Http\Controllers;

use App\Http\Resources\OffenderFalseResource;
use App\Http\Resources\OffenderNotsureResource;
use App\Models\Offender;
use Illuminate\Http\Request;
use App\Http\Resources\OffenderResource;
use App\Http\Resources\OffenderTrueResource;
use App\Models\OffenderFalse;
use App\Models\OffenderNotsure;
use App\Models\OffenderTrue;
use Illuminate\Support\Facades\DB;
class OffenderController extends Controller
{
   
    public function showTrendingOffenders()
    {

return OffenderResource::collection(DB::select(DB::raw("
select *, (select count(*) from `offender_trues` where offender_trues.offence_id = offenders.id) as `true_count`,

(select count(*) from `offender_falses` where offender_falses.offence_id = offenders.id) as `false_count`
from `offenders` order by `true_count` desc limit 16 offset 0
")));
    }

   
    public function fetchTrues($id)
    {
        
        $conditions=array(
            'offence_id'=>$id
         );

         $votes = OffenderTrueResource::collection(OffenderTrue::where($conditions)->get());
        if($votes){
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] = $votes;
            return response($response, 200);

        }else{
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['data'] = [];
            return response($response, 200);
        }
    }

    public function fetchFalse($id)
    {
          
        $conditions=array(
            'offence_id'=>$id
         );
         $votes = OffenderFalseResource::collection(OffenderFalse::where($conditions)->get());
        if($votes){
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] = $votes;
            return response($response, 200);

        }else{
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['data'] = [];
            return response($response, 200);
        }
    }

    public function fetchNotSure($id)
    {
           
        $conditions=array(
            'offence_id'=>$id
         );
                $votes = OffenderNotsureResource::collection(OffenderNotsure::where($conditions)->get());
        if($votes){
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] = $votes;
            return response($response, 200);

        }else{
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['data'] = [];
            return response($response, 200);
        }
    }



 public function voteTrue(Request $request)
    {

        $offence_id= $request->offence_id;
        $user_id=$request->user_id;


        $conditions =array(
            'offence_id'=>$offence_id,
            'user_id'=>$user_id
        );
        //check if user has liked previously
        $post = OffenderTrue::where($conditions)->first();
        if ($post) {
            OffenderTrue::where($conditions)->delete();

            $response['responseMessage'] = 'Not Voted';
        $response['responseCode'] = 00;
        $post = OffenderResource::collection(Offender::where('id',$offence_id)->get());
        $response['data'] =$post;
        return response($response, 200);

        }else{
            OffenderFalse::where($conditions)->delete();
            OffenderNotsure::where($conditions)->delete();
            $spotTrue = new OffenderTrue();
            $spotTrue->offence_id = $offence_id;
            $spotTrue->user_id = $user_id;
           if($spotTrue->save()){
            $response['responseMessage'] = 'Voted True';
            $response['responseCode'] = 00;
            $post = OffenderResource::collection(Offender::where('id',$offence_id)->get());
        $response['data'] = $post;
        return response($response, 200);
           }

        }
    }






    

 public function voteFalse(Request $request)
 {

     $offence_id= $request->offence_id;
     $user_id=$request->user_id;


     $conditions =array(
         'offence_id'=>$offence_id,
         'user_id'=>$user_id
     );
     //check if user has liked previously
     $post = OffenderFalse::where($conditions)->first();
     if ($post) {
         OffenderFalse::where($conditions)->delete();

         $response['responseMessage'] = 'Not Voted';
     $response['responseCode'] = 00;
     $post = OffenderResource::collection(Offender::where('id',$offence_id)->get());
     $response['data'] =$post;
     return response($response, 200);

     }else{
         OffenderTrue::where($conditions)->delete();
         OffenderNotsure::where($conditions)->delete();
         $spotTrue = new OffenderFalse();
         $spotTrue->offence_id = $offence_id;
         $spotTrue->user_id = $user_id;
        if($spotTrue->save()){
         $response['responseMessage'] = 'Voted False';
         $response['responseCode'] = 00;
         $post = OffenderResource::collection(Offender::where('id',$offence_id)->get());
     $response['data'] = $post;
     return response($response, 200);
        }

     }
 }





 
 public function voteNotSure(Request $request)
 {

     $offence_id= $request->offence_id;
     $user_id=$request->user_id;


     $conditions =array(
         'offence_id'=>$offence_id,
         'user_id'=>$user_id
     );
     //check if user has liked previously
     $post = OffenderNotsure::where($conditions)->first();
     if ($post) {
        OffenderNotsure::where($conditions)->delete();

         $response['responseMessage'] = 'Not Voted';
     $response['responseCode'] = 00;
     $post = OffenderResource::collection(Offender::where('id',$offence_id)->get());
     $response['data'] =$post;
     return response($response, 200);

     }else{
         OffenderFalse::where($conditions)->delete();
         OffenderTrue::where($conditions)->delete();
         $spotTrue = new OffenderNotsure();
         $spotTrue->offence_id = $offence_id;
         $spotTrue->user_id = $user_id;
        if($spotTrue->save()){
         $response['responseMessage'] = 'Voted Not Sure';
         $response['responseCode'] = 00;
         $post = OffenderResource::collection(Offender::where('id',$offence_id)->get());
     $response['data'] = $post;
     return response($response, 200);
        }

     }
 }





 public function makeOffencePost(Request $request){




    //  $photo_extension = $request->file('photo')->extension();

      $fileName=null;
       $file = $request->file('photo');

          $photo_extension =  $file->getClientOriginalExtension();

      if (isset($photo_extension)) {

         $fileName = uniqid(). ".".$photo_extension;


          $photoPath = $request->file('photo')->move(public_path("/offenders-photo"), $fileName);




      }

 $input = $request->all();

      $admin_id = 1;
      $offender_name = $request->offender_name;
      $title = $request->title;
      $offence = $request->offence;
      $content = $request->content;
      $source_url =$request->source_url;
      $photo = $fileName;

      $data = new Offender();
      $data->admin_id =     $admin_id;
      $data->offender_name= $offender_name;
      $data->title= $title;
      $data->offence= $offence;
      $data->content= $content;
      $data->source_url= $source_url;
      $data->photo= $photo;
      $data->save();

      
      $response['responseMessage'] = 'Message Posted';
      $response['responseCode'] = 00;
      return response()->json($response, 200);

  
   




 }


}
