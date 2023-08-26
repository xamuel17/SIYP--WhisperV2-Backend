<?php

namespace App\Http\Controllers;

use App\Http\Resources\RuleResource;
use App\Models\ReportUser;
use App\Models\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    //

    public function reportUser(Request $request){


        $rules = array(

            'rules_id' =>    'required',
            'content' => 'required',

        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }else{

        $reportUser = new ReportUser();
        $reportUser->rules_id = $request->rules_id;
        $reportUser->comment_id = $request->comment_id;
        $reportUser->content= $request->content;
        $reportUser->save();

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        return response($response, 200);
        }
    }

    public function viewRules(){
        $rules=  RuleResource::collection(Rule::orderBy('created_at', 'DESC')->get());
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = $rules;
        return response($response, 200);


    }
}
