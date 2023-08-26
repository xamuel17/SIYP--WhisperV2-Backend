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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CountriesController  $countriesController
     * @return \Illuminate\Http\Response
     */
    public function show(CountriesController $countriesController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CountriesController  $countriesController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CountriesController $countriesController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CountriesController  $countriesController
     * @return \Illuminate\Http\Response
     */
    public function destroy(CountriesController $countriesController)
    {
        //
    }
}
