<?php

namespace App\Http\Resources;

use App\Models\CommunityPostReplyLike;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
            $photo  = User::where('id', $this->user_id)->value('profile_pic');
            $_photo  = $photo != null ? $photo : "avatar.JPG";
            $photoStrings = json_decode($this->photos);
            $baseUrl =  env("APP_URL")."/users-community-images/" ;

            $photoUrls = [];
            if (is_array($photoStrings)) {
            foreach ($photoStrings as $photoString) {
                // Combine the base URL and the photo string to create the full URL
                $photoUrl = $baseUrl . $photoString;

                // Add the full URL to the $photoUrls array
                $photoUrls[] = $photoUrl;
            }
        }


        $likes =  CommunityPostReplyLike::where([
            'type' => 'comment',
            'selected_id' => $this->id,
            'action'=> true
        ])->count();

        $dislikes =  CommunityPostReplyLike::where([
            'type' => 'comment',
            'selected_id' => $this->id,
            'action'=> false
        ])->count();

        return [

            'id' =>$this->id,
            'user_id' => $this->user_id,
            'user_img' =>env("APP_URL")."/users-images/".  $_photo,
            'content'=>$this->content,
            'photos'=>$photoUrls,
            'likes'=> $likes ,
            'dislikes'=>$dislikes


        ];
    }
}
