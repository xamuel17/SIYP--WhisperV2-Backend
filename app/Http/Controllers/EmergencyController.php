<?php

namespace App\Http\Controllers;

use App\Models\Emergency;
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
    public function getAssignedNumber($id)
    {
        //
        $conditions = array(
            'user_id' => $id,
            'assigned' => '1'
        );
        $phoneNo = DB::table('emergencies')->where($conditions)->value('phone_no');
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['phone_no'] = $phoneNo;
        return response()->json($response, 200);
    }





      /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllNumbers($id)
    {
        //
        $conditions = array(
            'user_id' => $id,
            'assigned' => '1'
        );
        $phoneNo = DB::table('emergencies')->where('user_id', $id)->get();
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
    public function deleteNumber($id)
    {
        $resp = Emergency::findOrfail($id);
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

        $userId = $request->user_id;
         DB::table('emergencies')->where('user_id', $userId)->update(array('assigned' => 0));

        $id = $request->id;
       
        $sel = DB::table('emergencies')->where('id', $id)->update(array('assigned' => 1));
        
        if ($sel) {
            $response['responseMessage'] = 'saved';
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
    public function store(Request $request)
    {
        //

        $em = Emergency::create([
            'user_id' => $request->user_id,
            'fullname' => $request->fullname,
            'phone_no' => $request->phone_no,
            'assigned' => '0',
        ]);

        $response['responseMessage'] = 'saved';
        $response['responseCode'] = 00;
        return response()->json($response, 200);
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
