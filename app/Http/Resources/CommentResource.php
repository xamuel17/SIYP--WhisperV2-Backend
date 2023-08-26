<?php

namespace App\Http\Resources;
use App\Models\CommentLike;
use App\Models\CommentReply;
use App\Http\Resources\CommentReplyResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
     //   return parent::toArray($request);

     return [
        'id' => $this->id,
        'post_id' => $this->post_id,
        'user_id'=>$this->user_id,
     

          'username'=>DB::table('users')->where('id', $this->user_id)->value('username'),
          'user_img' =>DB::table('users')->where('id', $this->user_id)->value('profile_pic'),
          'created_at'=>$this->created_at,
        'content'=>$this->content,
        'comment_likes_count'=>CommentLike::where('comment_id',$this->id )->get()->count(),
        'comment_replies_count'=>CommentReply::where('comment_id',$this->id )->get()->count(),
        'comment_replies' =>  CommentReplyResource::collection(CommentReply::where('comment_id',$this->id )->get()),
     ];
    }
}
