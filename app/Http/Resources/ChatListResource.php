<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Chat;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class ChatListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $role= null;
        if($this->user_id != null){
            $role= "user";
        // $user = User::where('id', $this->volunteer_id)->first([
        //     'id as _id',
        //     'username as name',
        //     \DB::raw("COALESCE(profile_pic, '" . env("APP_URL") . "/users-images/avatar.JPG') as avatar")
        // ]);
        $user = Volunteer::where('user_id', $this->volunteer_id)->first([
            'user_id as volunteer_id', 'role', 'status', 'email', 'phone', 'username',
            \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/volunteer-images/', photo), '" . env('APP_URL') . "/users-images/avatar.JPG') as avatar")
        ]);
        
    }else{
        $role= "volunteer";
        $user = User::where('id', $this->user_id)->first([
            'id as _id',
            'username as name',
            \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/users-images/', profile_pic), '" . env('APP_URL') . "/users-images/avatar.JPG') as avatar")
        ]);  
    }

    $owner=User::where('id', $this->user_id)->first([
        'id as _id',
        'username as name',
        \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/users-images/', profile_pic), '" . env('APP_URL') . "/users-images/avatar.JPG') as avatar")
    ]); 

            // Replace $date with your actual date
            $date = Carbon::parse($this->created_at);

            // Format the date in a human-readable way
            $formattedDate = $date->diffForHumans();

// Fetch most recent text and time
$chat = Chat::where(['chat_id' => $this->chat_id, 'started' => null])
    ->latest('created_at')
    ->select('text', 'created_at')
    ->first();
      return [
          'id' => $this->_id,
          'text' => Crypt::decrypt($chat->text),
          'created_at' => $formattedDate,
          'user' => $user,
          'chat_id' => $this->chat_id,
          'role' => $role,
          'owner' => $owner
       ];
    }
}
