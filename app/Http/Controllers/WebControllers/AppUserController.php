<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guardians;
use App\Mail\WebMail\SendUserNotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\GuardianBlock;
use App\Models\Report;

class AppUserController extends Controller
{
    public function __construct()
    {

    }

    public function browseUser()
    {
        $users = User::all();
        return view('users.browse', compact('users'));
    }

    public function viewUser($userId)
    {
        $user = User::where('id', $userId)->first();
        if(!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        $confirmedGuardians = Guardians::where('ward_id',$userId)->where('status','confirmed')->get();
        $pendingGuardians = Guardians::where('ward_id',$userId)->where('status','pending')->get();
        $declinedGuardians = Guardians::where('ward_id',$userId)->where('status','declined')->get();
        $blockedGuardians = GuardianBlock::where('ward_id', $userId)->get();

        $userReportCount = Report::where('user_id', $userId)->count();
        return view('users.view', compact('user','confirmedGuardians', 'pendingGuardians', 'declinedGuardians', 'blockedGuardians', 'userReportCount'));
    }

    public function deactivateUser($userId)
    {
        $user = User::where('id', $userId)->first();
        if(!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        $user->update([
            'status' => 'inactive'
        ]);
        return redirect()->back()->with('message', 'User Deactivated Successfully');
    }

    public function reactivateUser($userId)
    {
        $user = User::where('id', $userId)->first();
        if(!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        $user->update([
            'status' => 'active'
        ]);
        return redirect()->back()->with('message', 'User Reactivated Successfully');
    }

    public function sendMessage($userId)
    {
        $user = User::where('id', $userId)->first();
        if(!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        return view('users.send-message', compact('user'));
    }

    public function sendMessagePost(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if(!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        // send messge to user
        try {
          Mail::to($user->email)->send(new SendUserNotificationMail($user->firstname. ' '. $user->lastname, $request->subject, $request->message));
        } catch (\Exception $e) {
          return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->back()->with('message', 'Message Sent Successfully');
    }
}
