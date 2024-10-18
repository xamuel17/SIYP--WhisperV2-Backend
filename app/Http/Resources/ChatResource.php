<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Carbon\Carbon;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

          $user = null;
          if($this->voluteer_id == auth()->user()->id){
            $user = User::where('id', $this->volunteer_id)->first([
                'id as _id',
                'username as name',
                \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/users-images/', profile_pic), '" . env('APP_URL') . "/users-images/avatar.jpg') as avatar")
            ]);

            }else{
                $user = User::where('id', $this->user_id)->first([
                    'id as _id',
                    'username as name',
                    \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/users-images/', profile_pic), '" . env('APP_URL') . "/users-images/avatar.jpg') as avatar")
                ]);
            }
            $image = null;
            if($this->image != null){
                $image = env("APP_URL") . "/users-chat-images/".$this->image;
            }

                        // Replace $date with your actual date
                        $date = Carbon::parse($this->created_at);


                        // Format the date to be compatible with JavaScript's new Date() constructor
                $formattedDate = $date->toISOString();


        return [
            '_id' => $this->_id,
            'text' => base64_decode($this->text),
            'image'=>$image,
            'sent'=>$this->sent,
            'received'=>$this->received,
            'createdAt' => $formattedDate,
            'user' => $user
         ];
    }
}
