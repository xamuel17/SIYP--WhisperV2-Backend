<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use  App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function trackUser(Request $request)
    {
        //

        $rules = array(

            'user_id' =>    'required',
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
            $user_id = $request->user_id;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $location=$request->location;
            $current_time = Carbon::now();
            $imei = $request->imei;
            $info = $request->info;

            $track = new Tracking();
            $track->user_id = $user_id;
            $track->latitude = $latitude;
            $track->longitude = $longitude;
            $track->current_time = $current_time;
            $track->imei = $imei;
            $track->location=$location;
            $track->info = $info;
            if($track->save()){
                $response['responseMessage'] = 'saved';
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showTrackHistory($id)
    {
        //

        $trackData = Tracking::where('user_id', $id)->get();
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['Data'] = $trackData;
        return response($response, 200);
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
    public function deleteAllUserTracking($id)
    {
        //
        $tracks = Tracking::where('user_id', $id)->delete();
        //$tracks->delete();
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        return response($response, 200);
    }










    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUserSingleTracking($id)
    {
        //
        $tracks = Tracking::where('id', $id)->first();
        $tracks->delete();
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        return response($response, 200);
    }
}
