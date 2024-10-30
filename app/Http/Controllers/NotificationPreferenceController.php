<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use Illuminate\Http\Request;

class NotificationPreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchUserPreference()
    {
        // Use firstOrCreate to fetch or create the preference in one call
        $data = NotificationPreference::firstOrCreate(
            ['user_id' => auth()->user()->id]
        );

        return response()->json([
            'responseMessage' => 'success',
            'responseCode' => 0, // Use 0 instead of 00
            'data' => $data,
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUserPreference(Request $request)
    {
        // Collect only the fields that are present in the request
        $data = $request->only([
            'alert_control',
            'geo_fencing',
            'audio_recording',
            'panic_alert',
            'timer',
            'show_notification',
            'theme'
        ]);

        // Add user_id to the data array
        $data['user_id'] = $request->user_id;

        // Find or create the notification preference for the authenticated user
        $preference = NotificationPreference::updateOrCreate(
            ['user_id' => auth()->user()->id],
            $data
        );

        return response()->json([
            'responseMessage' => 'success',
            'responseCode' => 00,
        ], 200);
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
     * @param  \App\Models\NotificationPreference  $notificationPreference
     * @return \Illuminate\Http\Response
     */
    public function show(NotificationPreference $notificationPreference)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NotificationPreference  $notificationPreference
     * @return \Illuminate\Http\Response
     */
    public function edit(NotificationPreference $notificationPreference)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NotificationPreference  $notificationPreference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NotificationPreference $notificationPreference)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NotificationPreference  $notificationPreference
     * @return \Illuminate\Http\Response
     */
    public function destroy(NotificationPreference $notificationPreference)
    {
        //
    }
}
