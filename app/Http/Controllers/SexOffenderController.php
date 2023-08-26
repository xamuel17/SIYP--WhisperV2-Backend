<?php

namespace App\Http\Controllers;

use App\Http\Resources\SexOffenderResource;
use Illuminate\Http\Request;
use  App\Models\SexOffender;
use Illuminate\Support\Facades\DB;
use  App\Models\User;
use Illuminate\Support\Facades\Validator;
class SexOffenderController extends Controller
{
    //


    //Register Sex Offender


      //################################GUARDIAN IGNORE FOLLOWER ###########################################
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {


        $rules = array(

            'content' => 'required',
            'offence' => 'required'

        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['Data'] = $validator->errors();
            return response()->json($response, 200);
        }else{

            $sexOffender = new SexOffender();
            $sexOffender->user_id = $request->user_id;
            $sexOffender->offence = $request->offence;
            $sexOffender->content = $request->content;
            $sexOffender->status= "pending";
            if($sexOffender->save()){
                $response['responseMessage'] = 'message sent';
            $response['responseCode'] = 00;
            return response()->json($response, 200);

            }else{

                $response['responseMessage'] = 'failed';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }

        }

    }
}
