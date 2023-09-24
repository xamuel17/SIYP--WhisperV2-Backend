<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'username' => $this->username,
            'photo' => env("APP_URL")."/users-community-images/" . $this->photo,
            'role'=> $this->role,
            'session'=>$this->session,
            'status'=> $this->status,
            'description'=> $this->description,
            'email'=>$this->email,
            'phone' => $this->phone,
            'rank' =>$this->rank,
            'created_at'=>$this->created_at

        ];
    }
}
