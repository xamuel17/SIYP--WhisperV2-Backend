<?php

namespace App\Http\Controllers;

use App\Models\Terms;
use Illuminate\Http\Request;

class TermsController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewEula()
    {

        $eula = Terms::where('id', 1)->value('content');

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = $eula;
        return response()->json($response, 200);
    }


    public function termsAndConditions()
    {

        $termsAndConditions = Terms::where('id', 2)->value('content');

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = $termsAndConditions;
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Terms  $terms
     * @return \Illuminate\Http\Response
     */
    public function show(Terms $terms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Terms  $terms
     * @return \Illuminate\Http\Response
     */
    public function edit(Terms $terms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Terms  $terms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Terms $terms)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Terms  $terms
     * @return \Illuminate\Http\Response
     */
    public function destroy(Terms $terms)
    {
        //
    }
}
