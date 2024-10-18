<?php

namespace App\Http\Resources;

use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\User;
use App\WebClasses\Util;
use Carbon\Carbon;
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
            $member=  CommunityMember::where(['community_id' => $this->id,'user_id'=> auth()->user()->id ]);
            if ($member->count() > 0){
                $memberBlock = $member->first()->status;
                $memberStatus = true;
            };
        } catch (\Throwable $th) {
            //throw $th;
        }


// Fetch 4 community members' photos
$membersPhotos = [];
$membersCount = null;
try {
    $membersUserIds = CommunityMember::where(['community_id' => $this->id])->limit(4)->pluck('user_id');
    $_membersPhotos = User::whereIn('id', $membersUserIds)->pluck('profile_pic');
    $membersCount = Util::numberFormatShort(CommunityMember::where(['community_id' => $this->id])->count());
    foreach ($_membersPhotos as $photo) {
        $membersPhotos[] = $photo ? env("APP_URL") . "/users-images/" . $photo : env("APP_URL") . "/users-images/" . "avatar.jpg";
    }
} catch (\Throwable $th) {
    // Handle exceptions here
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
            'member_count' => $membersCount,
            'member_photos' => $membersPhotos,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at' => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
