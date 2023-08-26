<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\LikeResource;
use App\Http\Resources\CommentLikeResource;
use App\Models\Post;
use App\Models\Likes;
use App\Models\CommentLike;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CommentReply;
use App\Models\FlagPost;
use App\Models\Report;
use App\Models\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allPosts()
    {
        return PostResource::collection(Post::orderBy('created_at', 'DESC')->where('status', '1')
            ->orderBy('created_at', 'DESC')->get());
    }

    public function userFetchAllPosts($id)
    {
        $conditions = array(
            'user_id' => $id

        );

        $postId = FlagPost::where($conditions)->pluck('post_id')->all();

        return PostResource::collection(Post::whereNotIn('id', $postId)->where('status', '1')->orderBy('created_at', 'DESC')->get());
    }



    /**
     * Show the form for creating a new resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userFlagPost(Request $request)
    {
        $user_id = $request->user_id;
        $post_id = $request->post_id;


        $conditions = array(
            'id' => $post_id
        );
        $post = Post::where($conditions)->first();
        if($post){

            $flagPost = new FlagPost();
            $flagPost->post_id = $post_id;
            $flagPost->user_id = $user_id;
            $flagPost->save();
            $response['responseMessage'] = 'post removed, you will no longer see this post';
            $response['responseCode'] = 00;
            return response($response, 200);

        }else{
            $response['responseMessage'] = 'post does not exist';
            $response['responseCode'] = -1001;
            return response($response, 200);

        }
    }






    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reportPost(Request $request){
        $user_id = $request->user_id;
        $post_id = $request->post_id;
        $content = $request->content;
        $rule_id = $request->rule_id;
        $user = User::where('id', $user_id)->first();
        $post = Post::where('id', $post_id)->first();

        if($user && $post){
             $report = new Report();
            $report->post_id = $post_id;
            $report->user_id = $user_id;
            $report->content=$content;
            $report->rule_id= $rule_id;
            $report->save();

            $response['responseMessage'] = 'post has been reported';
            $response['responseCode'] = 00;
            return response($response, 200);

        }else{
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response($response, 200);

        }

    }








    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPost($id)
    {
        $conditions = array(
            'status' => '1',
            'id' => $id
        );
        $post = Post::where($conditions)->first();
        if ($post) {
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $post = Post::where($conditions)->first();
            $response['data'] = new PostResource($post);
            return response($response, 200);
        } else {
            $response['responseMessage'] = 'unpublished';
            $response['responseCode'] = -1001;
            $response['data'] = [];
            return response($response, 200);
        }
    }









    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPostLikes($id)
    {
        $conditions = array(
            'status' => '1',
            'id' => $id
        );
        $post = Post::where($conditions)->first();
        if ($post) {
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $likes = Likes::where('post_id', $id)->get();
            $response['data'] = new LikeResource($likes);
            return response($response, 200);
        } else {
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['data'] = [];
            return response($response, 200);
        }
    }






















    /**
     * Show the form for creating a new resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function likePost(Request $request)
    {

        $post_id = $request->post_id;
        $user_id = $request->user_id;


        $conditions = array(
            'post_id' => $post_id,
            'user_id' => $user_id
        );
        //check if user has liked previously
        $post = Likes::where($conditions)->first();
        if ($post) {
            Likes::where($conditions)->delete();
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $post = Post::where('id', $post_id)->first();
            $response['data'] = new PostResource($post);
            return response($response, 200);
        } else {
            $likes = new Likes();
            $likes->post_id = $post_id;
            $likes->user_id = $user_id;
            $likes->save();
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;

            $post = Post::where('id', $post_id)->first();
            $response['data'] = new PostResource($post);
            return response($response, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function commentPost(Request $request)
    {
        $post_id = $request->post_id;
        $user_id = $request->user_id;
        $content = $request->content;
        $post = Post::findOrFail($post_id);
        if ($post) {
            $comments = new Comments();
            $comments->post_id = $post_id;
            $comments->user_id = $user_id;
            $comments->content = $content;
            $comments->save();
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $post = Post::where('id', $post_id)->first();
            $response['data'] = new PostResource($post);
            return response($response, 200);
        }
        //
    }







    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function replyComment(Request $request)
    {
        $post_id = $request->post_id;
        $comment_id = $request->comment_id;
        $user_id = $request->user_id;
        $content = $request->content;
        $comment = Comments::findOrFail($comment_id);

        if ($comment) {
            $comments = new CommentReply();
            $comments->comment_id = $comment_id;
            $comments->user_id = $user_id;
            $comments->content = $content;
            $comments->save();
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $post = Post::where('id', $post_id)->first();
            $response['data'] = new PostResource($post);
            return response($response, 200);
        } else {

            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $post = Post::where('id', $post_id)->first();
            $response['data'] = new PostResource($post);
            return response($response, 200);
        }
        //
    }










    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCommentLikes($id)
    {

        $likes =  CommentLike::where('comment_id', $id)->get();

        if ($likes) {
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] = new CommentLikeResource($likes);
            return response($response, 200);
        } else {
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            $response['data'] = [];
            return response($response, 200);
        }
    }



















    /**
     * Show the form for creating a new resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function likeComment(Request $request)
    {

        $comment_id = $request->comment_id;
        $user_id = $request->user_id;
        $post_id = $request->post_id;

        $conditions = array(
            'comment_id' => $comment_id,
            'user_id' => $user_id
        );
        //check if user has liked previously
        $post = CommentLike::where($conditions)->first();
        if ($post) {
            CommentLike::where($conditions)->delete();
            $response['responseMessage'] = 'comment unliked';
            $response['responseCode'] = 00;
            $post = CommentLike::where('comment_id', $comment_id)->first();
            $response['data'] = new CommentLikeResource($post);
            return response($response, 200);
        } else {
            $likes = new CommentLike();
            $likes->comment_id = $comment_id;
            $likes->user_id = $user_id;
            $likes->save();
            $response['responseMessage'] = 'comment liked';
            $response['responseCode'] = 00;

            $like = CommentLike::where('comment_id', $comment_id)->get();
            $response['data'] = new CommentLikeResource($like);
            return response($response, 200);
        }
    }

























    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
