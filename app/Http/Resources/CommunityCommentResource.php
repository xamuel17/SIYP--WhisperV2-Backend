<?php

namespace App\Http\Resources;

use App\Models\CommentReply;
use App\Models\CommunityCommentHasReply;
use App\Models\CommunityPostReplyLike;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
            $_photo  = $photo != null ? $photo : "avatar.jpg";
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
            'type' => 'reply',
            'selected_id' => $this->id,
            'action'=> true
        ])->count();

        $dislikes =  CommunityPostReplyLike::where([
            'type' => 'reply',
            'selected_id' => $this->id,
            'action'=> false
        ])->count();

        $user = User::where('id', $this->user_id)->first();
        $user_photo = $user->profile_pic ? env("APP_URL")."/users-images/" . $user->profile_pic : env("APP_URL")."/users-images/" . "avatar.jpg";


                // Replace $date with your actual date
                $date = Carbon::parse($this->created_at);

                // Format the date in a human-readable way
                $formattedDate = $date->diffForHumans();

                $replyCount = CommunityCommentHasReply::where(['community_comment_id' => $this->id])->count();

        return [

            'id' =>$this->id,
            'user_id' => $this->user_id,
            'user_photo'=>$user_photo,
            'user_name'=> Str::limit($user->username, 8,'...'),
            "user_firstname" => Str::limit($user->firstname, 6,'...'),
            'content'=>$this->content,
            'photos'=>$photoUrls,
            'likes'=> $likes  ,
            'reply_count' => $replyCount,
            'dislikes'=>$dislikes,
            'created_at'=>$formattedDate

        ];
    }
}
