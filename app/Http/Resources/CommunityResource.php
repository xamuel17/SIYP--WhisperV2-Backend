<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunityResource extends JsonResource
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

            'id' =>$this->id,
            'title' => $this->name,
            'user_id' => $this->user_id,
            'purpose' => $this->purpose,
            'category' => $this->category,
            'photo'=> public_path("users-community-images/" . $this->photo) ,
            'privacy'=>$this->privacy,
            'content'=>$this->content,
            'status'=>$this->status,
            'secret_key'=>$this->secret_key,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
