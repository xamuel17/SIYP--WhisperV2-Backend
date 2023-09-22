<?php

namespace App\Http\Resources;

use App\Models\Community;
use App\Models\CommunityMember;
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
        //check if user is a community member
        $memberStatus = false;
        $memberBlock = "";
        try {
            $member=  CommunityMember::where(['community_id' => $this->id,'user_id', auth()->user()->id ]);
            if ($member->count() > 0){ $memberStatus = true; };
            if(isset($member->status)){ $memberBlock =$member->status; };
        } catch (\Throwable $th) {
            //throw $th;
        }

        return [

            'id' =>$this->id,
            'title' => $this->name,
            'user_id' => $this->user_id,
            'purpose' => $this->purpose,
            'category' => $this->category,
            'photo'=> env('APP_URL'). "/users-community-images/" . $this->photo,
            'privacy'=>$this->privacy,
            'content'=>$this->content,
            'status'=>$this->status,
            'secret_key'=>$this->secret_key,
            'membership' => $memberStatus,
            'membership_status' => $memberBlock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
