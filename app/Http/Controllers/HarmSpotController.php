<?php

namespace App\Http\Controllers;

use App\Models\HarmSpot;
use App\Models\SpotTrue;
use Carbon\Carbon;
use App\Models\SpotFalse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Resources\HarmSpotResource;
use Illuminate\Support\Facades\DB;
class HarmSpotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showReport()
    {
        return HarmSpotResource::collection(HarmSpot::where('status','confirmed')->get());

    }



 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showTrendingSpot()
    {


return HarmSpotResource::collection(DB::select(DB::raw("
select *, (select count(*) from `spot_trues` where spot_trues.spot_id = harm_spots.id) as `true_count`,

(select count(*) from `spot_falses` where spot_falses.spot_id = harm_spots.id) as `false_count`
from `harm_spots` where  harm_spots.status=1 order by `true_count` desc limit 16 offset 0
")));

    }



 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showTrendingSpotByCountry($id)
    {


        return HarmSpotResource::collection(DB::select(DB::raw("
select *, (select count(*) from `spot_trues` where spot_trues.spot_id = harm_spots.id) as `true_count`,

(select count(*) from `spot_falses` where spot_falses.spot_id = harm_spots.id) as `false_count`
from `harm_spots` where `country`=$id AND  harm_spots.status=1 order by `true_count` desc limit 16 offset 0
")));

    }



     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewSpot($id)
    {
        //

        $conditions=array(
            'status'=>'1',
            'id'=>$id
         );
                $spot = HarmSpot::where($conditions)->first();
        if($spot){
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $post = HarmSpot::where($conditions)->first();
            $response['data'] = new HarmSpotResource($post);
            return response($response, 200);

        }else{
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['data'] = [];
            return response($response, 200);
        }
    }






    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reportSpot(Request $request)
    {
        //

        $admin_id = $request->admin_id;
        $title=$request->title;
        $latitude=$request->latitude;
        $longitude=$request->longitude;
        $content=$request->content;
        $risk_level=$request->risk_level;


        $rules = array(

            'admin_id' =>    'required',
            'latitude' => 'required',
            'longitude' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['Data'] = $validator->errors();
            return response()->json($response, 200);
        } else {
            $spot = new HarmSpot();
            $spot->title=$title;
            $spot->admin_id= $admin_id;
            $spot->latitude=$latitude;
            $spot->longitude=$longitude;
            $spot->content=$content;
            $spot->risk_level=$risk_level;
            $spot->status= '1';
            if($spot->save()){

            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
            }else{

                $response['responseMessage'] = 'failed';
                $response['responseCode'] = -1001;
            return response()->json($response, 200);
            }


        }

    }









  /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function voteTrue(Request $request)
    {

        $spot_id= $request->spot_id;
        $user_id=$request->user_id;


        $conditions =array(
            'spot_id'=>$spot_id,
            'user_id'=>$user_id
        );
        //check if user has liked previously
        $post = SpotTrue::where($conditions)->first();
        if ($post) {
            SpotTrue::where($conditions)->delete();

            $response['responseMessage'] = 'HarmSpot Not Voted';
        $response['responseCode'] = 00;
        $post = HarmSpotResource::collection(HarmSpot::where('id',$spot_id)->get());
        $response['data'] =$post;
        return response($response, 200);

        }else{
            SpotFalse::where($conditions)->delete();
            $spotTrue = new SpotTrue();
            $spotTrue->spot_id = $spot_id;
            $spotTrue->user_id = $user_id;
           if($spotTrue->save()){
            $response['responseMessage'] = 'HarmSpot Voted True';
            $response['responseCode'] = 00;
            $post = HarmSpotResource::collection(HarmSpot::where('id',$spot_id)->get());
        $response['data'] = $post;
        return response($response, 200);
           }

        }
    }



  /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function voteFalse(Request $request)
    {
        $spot_id= $request->spot_id;
        $user_id=$request->user_id;


        $conditions =array(
            'spot_id'=>$spot_id,
            'user_id'=>$user_id
        );
        //check if user has liked previously
        $post = SpotFalse::where($conditions)->first();
        if ($post) {
            SpotFalse::where($conditions)->delete();


            $response['responseMessage'] = 'HarmSpot Not Voted';
        $response['responseCode'] = 00;
        $post = HarmSpotResource::collection(HarmSpot::where('id',$spot_id)->get());
        $response['data'] =$post;
        return response($response, 200);

        }else{
            SpotTrue::where($conditions)->delete();
            $spotTrue = new SpotFalse();
            $spotTrue->spot_id = $spot_id;
            $spotTrue->user_id = $user_id;
           if($spotTrue->save()){
            $response['responseMessage'] = 'HarmSpot Voted False';
            $response['responseCode'] = 00;
            $post = HarmSpotResource::collection(HarmSpot::where('id',$spot_id)->get());
        $response['data'] = $post;
        return response($response, 200);
           }

        }
    }















    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
