<?php

namespace App\Http\Resources;

use App\Models\CommunityHasComments;
use App\Models\CommunityPostReplyLike;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;

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
            $baseUrl =  public_path("users-community-images/") ;

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
    if(!empty($this->videos)){ $video = public_path("users-community-videos/". $this->videos);}

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
        return [

            'id' =>$this->id,
            'title' => $this->name,
            'photo' =>$photoUrls,
            'videos'=>  $video,
            'likes'=> $likes ,
            'dislikes'=>$dislikes,
            'comment_count' => $commentCount,
            "comment" => $comments,
            'content'=>$this->content,
            'status' => $this->status,
            'is_flagged'=>$this->is_flagged

        ];
    }




}
