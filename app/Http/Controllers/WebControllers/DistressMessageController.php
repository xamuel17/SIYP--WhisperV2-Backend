<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DistressMessage;
use App\Models\User;
use App\Models\GuardianDistressMessage;

class DistressMessageController extends Controller
{
    public function browseDistressMessages($userId=null)
    {
        if(isset($userId)) {
            // user distress messages
            $distressMessages = DB::table('distress_messages as dm')->where('user_id','=',$userId)->latest('dm.created_at')
               ->join('users as us', 'dm.user_id', '=', 'us.id')
               ->select('dm.id as distress_message_id', 'dm.time_of_message', 'dm.phone_number', 'dm.priority',
                       'us.firstname', 'us.lastname', 'us.username')->get();
        } else {
            // all distress messages
            $distressMessages = DB::table('distress_messages as dm')->latest('dm.created_at')
               ->join('users as us', 'dm.user_id', '=', 'us.id')
               ->select('dm.id as distress_message_id', 'dm.time_of_message', 'dm.phone_number', 'dm.priority',
                       'us.firstname', 'us.lastname', 'us.username')->get();
        }

        return view('distress-messages.browse', compact('distressMessages', 'userId'));
    }

    public function showDistressMessage($id)
    {
        // get the message
        $distressMessage = DistressMessage::where('id',$id)->first();
        if(!$distressMessage) {
            return redirect()->back()->with('error', 'No Distress Message Found');
        }
        // get the user
        $user = User::where('id',$distressMessage->user_id)->first();
        if(!$user) {
            return redirect()->back()->with('error', 'User not Found');
        }

        return view('distress-messages.show', compact('distressMessage', 'user'));
    }

    public function updatePriority(Request $request, $id)
    {
        // get the message
        $distressMessage = DistressMessage::where('id',$id)->first();
        if(!$distressMessage) {
            return redirect()->back()->with('error', 'No Distress Message Found');
        }

        // update distress message status
        $distressMessage->update([
            'priority' => $request->status
        ]);

        // update distress message status in guardians distress message table
        $guradianDistressMessages = GuardianDistressMessage::where('distress_message_id',$id)->get();
        if(count($guradianDistressMessages) > 0) {
            foreach($guradianDistressMessages as $gdm) {
                $gdm->update([
                    'priority' => $request->status
                ]);
            }
        }

        return redirect(route("distress-message.show",['message' => $id]))->with('message', 'Distress Message Updated Successfully');

    }
}
