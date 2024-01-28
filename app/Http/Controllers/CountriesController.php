<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountriesController  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountries()
    {
        $countries = DB::table('countries')->get();

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = $countries;
        return response()->json($response, 200);
    }


}
