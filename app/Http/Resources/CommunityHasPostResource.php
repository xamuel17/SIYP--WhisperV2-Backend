<?php

namespace App\Http\Resources;

use App\Models\CommunityHasComments;
use App\Models\CommunityPostReplyLike;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
class CommunityHasPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

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
    $video = "";
    if(!empty($this->videos)){ $video = env('APP_URL')."/users-community-videos/". $this->videos;}

        $likes =  CommunityPostReplyLike::where([
            'type' => 'post',
            'selected_id' => $this->id,
            'action'=> true
        ])->count();

        $dislikes =  CommunityPostReplyLike::where([
            'type' => 'post',
            'selected_id' => $this->id,
            'action'=> false
        ])->count();

        $commentCount= CommunityHasComments::where([
            'community_post_id' => $this->id
        ])->count();

        $comments = CommunityCommentResource::collection(CommunityHasComments::where('community_post_id', $this->id)->get());

        $user = User::where('id', $this->user_id)->first();
        $user_photo = $user->profile_pic ? env("APP_URL")."/users-images/" . $user->profile_pic : env("APP_URL")."/users-images/" . "avatar.jpg";


        // Replace $date with your actual date
        $date = Carbon::parse($this->created_at);

        // Format the date in a human-readable way
        $formattedDate = $date->diffForHumans();


        return [

            'id' =>$this->id,
            'community_id'=>$this->community_id,
            'user_photo'=>$user_photo,
            'user_name'=> Str::limit($user->username, 32,'...'),
            "user_firstname" => Str::limit($user->firstname, 32,'...'),
            'user_id'=>$this->user_id,
            'title' => $this->name,
            'photo' =>$photoUrls,
            'videos'=>  $video,
            'likes'=> $likes ,
            'dislikes'=>$dislikes,
            'comment_count' => $commentCount,
            "comment" => $comments,
            'content'=>$this->content,
            'status' => $this->status,
            'is_flagged'=>$this->is_flagged,
            'created_at'=>$formattedDate

        ];
    }




}
