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

        $userData = NotificationPreference::where('user_id', auth()->user()->id)->first();

        return response()->json([
            'responseMessage' => 'success',
            'responseCode' => 00, // Use 0 instead of 00
            'data' => $userData,
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


        // Find or create the notification preference for the authenticated user
        $preference = NotificationPreference::updateOrCreate(
            ['user_id' => auth()->user()->id],
            $data
        );

        $userData = NotificationPreference::where('user_id', auth()->user()->id)->first();

        return response()->json([
            'responseMessage' => 'success',
            'responseCode' => 00,
            'data' => $userData
        ], 200);
    }

}
