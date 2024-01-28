<?php

namespace App\Http\Controllers;

use App\Models\Emergency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmergencyController extends Controller
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
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAssignedNumber()
    {

        $conditions = array(
            'user_id' => auth()->user()->id,
            'assigned' => '1'
        );
        $phoneNo = DB::table('emergencies')->where($conditions)->value('phone_no');
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = $phoneNo;
        return response()->json($response, 200);
    }





      /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllNumbers()
    {
        $phoneNo = DB::table('emergencies')->where(['user_id'=> auth()->user()->id])->get();
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = $phoneNo;
        return response()->json($response, 200);
    }




    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteNumber(Request $request)
    {
        $resp = Emergency::where(["user_id" => auth()->user()->id , "phone_no" => $request->phone_no])->first();
        if ($resp) {
            $resp->delete();
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            return response()->json($response, 200);

        } else {
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }




    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignNumber(Request $request)
    {
        //check of phonenumber is assigned by user
         DB::table('emergencies')->where('user_id', auth()->user->id)->update(array('assigned' => 0));
        $id = $request->id;
        $sel = DB::table('emergencies')->where('id', $id)->update(array('assigned' => 1));
        if ($sel) {
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        } else {
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addNumber(Request $request)
    {

        $contactCount = Emergency::where([ "user_id" =>auth()->user()->id])->count();
        if ($contactCount >= 4) {
            $response['responseMessage'] = 'You are limited to adding a maximum of four emergency contact numbers.';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }


        $contact = Emergency::where(["phone_no"=> $request->phone_no, "user_id" =>auth()->user()->id])->get();
        if($contact->count() > 0){
            $response['responseMessage'] = 'Contact already exists';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }



        if($request->fullname == null || $request->phone_no == null){
            $response['responseMessage'] = 'Invalid Contact Details';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

        $em = Emergency::create([
            'user_id' => auth()->user()->id,
            'fullname' => $request->fullname,
            'phone_no' => $request->phone_no,
            'assigned' => '0',
        ]);

        if($em){
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Emergency  $emergency
     * @return \Illuminate\Http\Response
     */
    public function show(Emergency $emergency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Emergency  $emergency
     * @return \Illuminate\Http\Response
     */
    public function edit(Emergency $emergency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Emergency  $emergency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Emergency $emergency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Emergency  $emergency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Emergency $emergency)
    {
        //
    }
}
