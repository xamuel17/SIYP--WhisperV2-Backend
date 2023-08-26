<?php

namespace App\Http\Controllers\Webcontrollers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tracking;

class UserTrackingController extends Controller
{
    public function viewTracking($userId)
    {
        $userTracking = Tracking::where('user_id',$userId)->get();
        return view('tracking.view', compact('userTracking'));
    }
}
