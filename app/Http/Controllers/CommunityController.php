<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommunityCommentResource;
use App\Http\Resources\CommunityHasPostCommentResource;
use App\Http\Resources\CommunityHasPostResource;
use App\Http\Resources\CommunityHasReplyCommentResource;
use App\Http\Resources\CommunityResource;
use App\Http\Resources\RuleResource;
use App\Models\CommentReply;
use App\Models\Community;
use App\Models\CommunityCommentHasReply;
use App\Models\CommunityHasComments;
use App\Models\CommunityHasPosts;
use App\Models\CommunityMember;
use App\Models\CommunityPostReplyLike;
use App\Models\Post;
use App\Models\Report;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{

    /**
     * Create a community.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createCommunity(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'purpose' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'Community Title and Purpose is Required!';
            $response['responseCode'] = -1001;
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        $secretKey = null;
        if ($request->privacy == 1) {

            $secretKey = rand(pow(10, 5 - 1), pow(10, 5) - 1);
        }
        $community = Community::create([
            "name" => $request->title,
            "purpose" => $request->purpose,
            "privacy" => $request->privacy,
            "user_id" => auth()->user()->id,
            "privacy" => $request->privacy ?? "public",
            "category" => $request->category ?? "all",
            "secret_key" => $secretKey,
        ]);

        //upload photo
        $title = strtok($request->title, ' ');
        if(isset($request->photo)){
            $photo = $this->uploadCommunityImage($request, $community->id, $title);
            Community::where(['id' => $community->id])->update(['photo' => $photo]);
        }


        CommunityMember::create([
            'user_id' => auth()->user()->id,
            'community_id' => $community->id,
            'role' => 'admin',
        ]);
        if ($community) {
            $response['responseMessage'] = 'Community Creation Successful';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        } else {
            $response['responseMessage'] = 'Community Creation Failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }

    public function DeleteMyCommunityPost($post_id){

        $post = CommunityHasPosts::where(['id' =>  $post_id , 'user_id' => auth()->user()->id])->first()->delete();
        if($post){
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] = "Post Successfully Deleted";
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'Failed';
            $response['responseCode'] = -1001;
            $response['data'] = "Post Deletion Failed";
            return response()->json($response, 200);
        }
    }

    public function DeleteMyCommunityComment($comment_id){
        $post = CommunityHasComments::where(['id' =>  $comment_id , 'user_id' => auth()->user()->id])->first();
        $postId = $post->community_post_id;
        $post->delete();
        if($post){
        $commentsFirstQuery =CommunityCommentResource::collection(CommunityHasComments::where(['community_post_id' => $postId, 'is_flagged' => false , 'user_id' => auth()->user()->id,])->latest()->limit(3)->get());
        $commentsSecondQuery =CommunityCommentResource::collection(CommunityHasComments::where(['community_post_id' => $postId, 'is_flagged' => false])->whereNotIn('id', $commentsFirstQuery->pluck('id'))->orderBy('created_at', 'desc')->get());
        $comments = $commentsFirstQuery->concat($commentsSecondQuery);
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] = $comments;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'Failed';
            $response['responseCode'] = -1001;
            $response['data'] = "Comment Deletion Failed";
            return response()->json($response, 200);
        }

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CountriesController  $countriesController
     * @return \Illuminate\Http\Response
     */
    public function getCommunities()
    {
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = CommunityResource::collection(Community::all());
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CountriesController  $countriesController
     * @return \Illuminate\Http\Response
     */
    public function getCommunity($community_id)
    {
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = CommunityResource::collection(Community::where(['id' => $community_id, 'status' => 'active'])->first());
        return response()->json($response, 200);
    }


    public function getCommunitySinglePost($post_id){

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = CommunityHasPostCommentResource::collection(CommunityHasPosts::where(['id' =>  $post_id])->get());
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CountriesController  $countriesController
     * @return \Illuminate\Http\Response
     */
    public function getCommunityByUser()
    {
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = CommunityResource::collection(Community::where(['user_id' => auth()->user()->id])->get());
        return response()->json($response, 200);
    }



    public function getFollowingCommunity()
    {

        $communities = CommunityMember::leftJoin('communities', 'community_members.community_id', '=', 'communities.id')
        ->select('communities.*')
        ->get();

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = CommunityResource::collection($communities);
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CountriesController  $countriesController
     * @return \Illuminate\Http\Response
     */
    public function joinCommunity(Request $request)
    {

        $rules = array(
            'community_id' => 'required|exists:communities,id',
            'action' => 'required|in:add,remove',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'Opps, selected community doesn\'t exist!';
            $response['responseCode'] = -1001;
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }
        $action = $request->action ?? "add";
        $_community = Community::where('id', $request->community_id)->first();
        $communityName = $_community->name;
        $conditions = [
            'user_id' => auth()->user()->id,
            'community_id' => $request->community_id,
        ];
        if ($action == "remove") {
            //check if user is admin

            $communityRemove = CommunityMember::where(['community_id'=> $request->community_id, 'user_id' => auth()->user()->id])->first();


            if (isset($communityRemove)) {

                if($communityRemove->status == "admin"){
                    $response['responseMessage'] = "You are unable to exit this {$communityName} as you hold the role of a community administrator. ";
                    $response['responseCode'] = 00;
                    $response['data'] = $validator->errors();
                    return response()->json($response, 200);
                }
                $communityRemove->forceDelete();
                $response['responseMessage'] = 'You\'ve unfollowed ' . $communityName . ' community';
                $response['responseCode'] = 00;
                $response['data'] = $validator->errors();
                return response()->json($response, 200);
            } else {
                $response['responseMessage'] = 'Oops, it seems the chosen community doesn\'t exist 😅🌟!';
                $response['responseCode'] = -1001;
                $response['data'] = $validator->errors();
                return response()->json($response, 200);
            }
        } else {

            if (CommunityMember::where($conditions)->count()) {
                $response['responseMessage'] = 'You are already following ' . $communityName . ' community!';
                $response['responseCode'] = -1001;
                $response['data'] = $validator->errors();
                return response()->json($response, 200);
            } else {

                if(!empty($_community->secretKey)){
                    if($request->secretKey != $_community->secretKey){
                        $response['responseMessage'] = 'Incorrect secret key, you can not join ' . $communityName . ' community!';
                        $response['responseCode'] = -1001;
                        $response['data'] = $validator->errors();
                        return response()->json($response, 200);
                    }
                }
                CommunityMember::create([
                    'user_id' => auth()->user()->id,
                    'community_id' => $request->community_id,
                ]);
                $response['responseMessage'] = 'You are now following ' . $communityName . ' community!';
                $response['responseCode'] = 00;
                return response()->json($response, 200);
            }
        }

    }


    public function getCommunityPost($community_id, $page){
        //check if user is blocked
        if (CommunityMember::where(['user_id' => auth()->user()->id, 'community_id' => $community_id, 'status' => 'blocked'])->count() != 0) {
            $response['responseMessage'] = 'You have been blocked from accessing this community';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = CommunityHasPostCommentResource::collection(CommunityHasPosts::where(['community_id' =>  $community_id, 'status' => '1'])->simplePaginate($page));
        return response()->json($response, 200);
    }


    public function CommunityDashboard($page)
    {
        // Get the authenticated user's community IDs
        $communityIds = CommunityMember::where('user_id', auth()->user()->id)->pluck('community_id')->toArray();

        // Fetch posts from communities where the user is a member and the status is '1'
        $posts = CommunityHasPosts::whereIn('community_id', $communityIds)
            ->where('status', '1')
            ->simplePaginate($page);

        // Prepare the JSON response
        $response = [
            'responseMessage' => 'success',
            'responseCode' => 00, // Use an appropriate HTTP status code, e.g., 200 for success
            'data' => CommunityHasPostResource::collection($posts),
        ];
        return response()->json($response, 200);
    }


    public function getPostCommentReply($comment_id){
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = CommunityHasReplyCommentResource::collection(CommunityCommentHasReply::where(['community_comment_id' =>  $comment_id])->get());
        return response()->json($response, 200);
    }


    public function likeDislikePost($selectedId, $userId , $state, $communityId ){

      if($state == "likePost" || $state == "dislikePost" ){
        $post = CommunityPostReplyLike::where([
            'type' => 'post',
            'user_id' => $userId,
            'selected_id' => $selectedId,
            'community_id' => $communityId
        ])->first();
           if(isset($post)){
            //delete
            if($state == "likePost" && $post->action == 0){
                CommunityPostReplyLike::where(['id' => $post->id])->update(['action' => true]);
            }
             if($state == "dislikePost" && $post->action == 1) {

                CommunityPostReplyLike::where(['id' => $post->id])->update(['action' => false]);
            }

           }else{
            //create
            CommunityPostReplyLike::create([
                'type' => 'post',
                'user_id' => $userId,
                'selected_id' => $selectedId,
                'action' => ($state == "likePost") ? true: false,
                'community_id' => $communityId
            ]);
           }
      }else if ($state == "dislikeReply" || $state === "likeReply"){
        $post = CommunityPostReplyLike::where([
            'type' => 'reply',
            'user_id' => $userId,
            'selected_id' => $selectedId,
            'community_id' => $communityId
        ])->first();
           if(isset($post->id)){

            if($state == "likeReply" && $post->action == 0){
                CommunityPostReplyLike::where(['id' => $post->id])->update(['action' => true]);
            }

             if($state == "dislikeReply" && $post->action == 1) {
                CommunityPostReplyLike::where(['id' => $post->id])->update(['action' => false]);
            }
           }else{
            //create
            CommunityPostReplyLike::create([
                'type' => 'reply',
                'user_id' => $userId,
                'selected_id' => $selectedId,
                'action' => ($state == "likeReply") ? true: false,
                'community_id' => $communityId
            ]);
           }
      }
    }
    public function likeDislikeCommunityPostOrReply(Request $request){

        $post= null;
        if (isset($request->action) && ($request->action == "likePost" || $request->action == "dislikePost")) {
            $post = CommunityHasPosts::where('id' , $request->communityId)->first();
            if(empty($post)){
                $response['responseMessage'] = 'Community Post doesn\'t exist';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }
        }

        $state= null;
        switch ($request->action) {
            case "likePost":
                $status ="Liked Post";
                $this->likeDislikePost($request->selectedId, auth()->user()->id, "likePost",$request->communityId);
                break;
            case "dislikePost":
                $status ="Disliked Post";
                $this->likeDislikePost($request->selectedId, auth()->user()->id, "dislikePost",$request->communityId);
                break;
            case "dislikeReply":
                $status ="Disliked Reply";
                $this->likeDislikePost($request->selectedId, auth()->user()->id, "dislikeReply",$request->communityId);
                break;
            case "likeReply":
                $status ="Liked Reply";
                $this->likeDislikePost($request->selectedId, auth()->user()->id, "likeReply",$request->communityId);
                break;
            default:
            $response['responseMessage'] = 'Ohh Snap! something went wrong.';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
        $response['responseMessage'] = $status;
        $response['responseCode'] = 00;
        return response()->json($response, 200);
    }

    public function replyCommunityPost(Request $request){
        //check if user is blocked
        if (CommunityMember::where(['user_id' => auth()->user()->id, 'community_id' => $request->community_id, 'status' => 'blocked'])->count() != 0) {
            $response['responseMessage'] = 'You have been blocked from accessing this community';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

        //check if community post exists
        if(CommunityHasPosts::where(['id'=> $request->post_id])->count() != 0){
            $response['responseMessage'] = 'Community doesn\'t exist';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
        $comment =CommunityHasComments::create([
            'user_id' => auth()->user()->id,
            'community_post_id' =>$request->community_post_id,
            'content' =>$request->content
        ]);
        //type= comment
        if (!is_null($request->photos) && !empty($request->photos)) {
            $this->uploadPhoto($request, $comment->id);
        }

        if(isset($comment)){
            $response['responseMessage'] = 'The post reply successful.';
            $response['responseCode'] = 200;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'The post reply successfully.';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }




    public function commentReply(Request $request){
                //check if user is blocked
            if (CommunityMember::where(['user_id' => auth()->user()->id, 'community_id' => $request->community_id, 'status' => 'blocked'])->count() != 0) {
                $response['responseMessage'] = 'You have been blocked from accessing this community';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }
        $comment =CommunityCommentHasReply::create([
            'user_id' => auth()->user()->id,
            'community_comment_id' =>$request->community_comment_id,
            'content' =>$request->content
        ]);
        //type= reply
        if (!is_null($request->photos) && !empty($request->photos)) {
            $this->uploadPhoto($request, $comment->id);
        }

        if(isset($comment)){
            $response['responseMessage'] = 'Reply sent!.';
            $response['responseCode'] = 200;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'Opps,something went wrong.';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }


        /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CountriesController  $countriesController
     * @return \Illuminate\Http\Response
     */
    public function makeAnotherCommunityPost(Request $request)
    {

        $rules = array(
            'community_id' => 'required|exists:communities,id',
            'content' => 'required',
            'type' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'Opps, selected community doesn\'t exist!';
            $response['responseCode'] = -1001;
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        if (CommunityMember::where(['user_id' => auth()->user()->id, 'community_id' => $request->community_id])->count() == 0) {
            $response['responseMessage'] = 'You are not a community member';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

       $post= CommunityHasPosts::create([
            'content' => $request->content,
            'user_id' => auth()->user()->id,
            'community_id' => $request->community_id
        ]);

        if(isset($post)){
            $response['responseMessage'] = 'The post creation was successful.';
            $response['responseCode'] = 200;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'The post has been successfully created.';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CountriesController  $countriesController
     * @return \Illuminate\Http\Response
     */
    public function makeCommunityPost(Request $request)
    {

        $rules = array(
            'community_id' => 'required|exists:communities,id',
            'content' => 'required',
            'type' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'Opps, selected community doesn\'t exist!';
            $response['responseCode'] = -1001;
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }

        if (CommunityMember::where(['user_id' => auth()->user()->id, 'community_id' => $request->community_id])->count() == 0) {
            $response['responseMessage'] = 'You are not a community member';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

       $post= CommunityHasPosts::create([
            'content' => $request->content,
            'user_id' => auth()->user()->id,
            'community_id' => $request->community_id
        ]);

        if (!is_null($request->photos) && !empty($request->photos)) {
            $this->uploadPhoto($request, $post->id);
        }

        if (!is_null($request->video) && !empty($request->video)) {
           $video =  $this->uploadVideo($request, $post->id);
        }

        if(isset($post)){
            $response['responseMessage'] = 'The post creation was successful.';
            $response['responseCode'] = 200;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'The post has been successfully created.';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

    }

    public function uploadCommunityImage(Request $request,$communityId,$communityName  )
    {

        $input = $request->all();
        $extension = $request->file('photo')->extension();

        $fileName = time() . "." . $extension;
        $fileName = $communityName . "-" . $fileName;
        $path = $request->file('photo')->move(public_path("/users-community-images"), $fileName);

        $photoURL = url('/' . $fileName);

        $data = [
            'photo' => $fileName,
        ];

        Community::where('id', $communityId)->update($data);

        return $fileName;
    }


    public function uploadVideo(Request $request , $postId)
{
    // Validate the incoming request data
    // $request->validate([
    //     'video' => 'required|mimes:mp4,mov,avi,wmv|max:102400', // Adjust validation rules as needed
    // ]);

    // Store the uploaded video in a directory, generate a unique file name, and save it
    $fileName = uniqid('video_') . '.' . $request->file('video')->extension();

    $path = $request->file('video')->move(public_path("/users-community-videos"), $fileName);

    $data = [
        'videos' => $fileName,
    ];

    if (CommunityHasPosts::where('id', $postId)->update($data)) {

        $response['responseMessage'] = 'Photos uploaded successfully';
        $response['responseCode'] = 00;
        return response()->json($response, 200);
    }
    return $fileName;
}


    public function viewCommunityRules(){

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = Rule::orderBy('created_at', 'DESC')->get(['id', 'content']);
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reportCommunityComment(Request $request){
        $post = CommunityHasPosts::where(['id'=> $request->post_id, 'community_id' =>$request->community_id])->first();

        if(isset($post)){

          Report::updateOrInsert(
                ['post_id' => $request->post_id, 'comment_id' => $request->comment_id, 'user_id' => auth()->user()->id], // Conditions to check for an existing record
                ['content' => $request->content,'comment_id' => $request->comment_id,  'post_id' => $request->post_id, 'rule_id' => $request->rule_id, 'user_id' => auth()->user()->id] // Data to insert or update
            );
            $response['responseMessage'] = 'post has been reported';
            $response['responseCode'] = 00;
            return response($response, 200);
        }else{
            $response['responseMessage'] = 'post not found';
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
    public function reportCommunityPost(Request $request){
        $post = CommunityHasPosts::where(['id'=> $request->post_id, 'community_id' =>$request->community_id])->first();

        if(isset($post)){

          Report::updateOrInsert(
                ['post_id' => $request->post_id , 'user_id' => auth()->user()->id , 'comment_id' => ''], // Conditions to check for an existing record
                ['content' => $request->content,  'post_id' => $request->post_id, 'rule_id' => $request->rule_id, 'user_id' => auth()->user()->id] // Data to insert or update
            );
            $response['responseMessage'] = 'post has been reported';
            $response['responseCode'] = 00;
            return response($response, 200);
        }else{
            $response['responseMessage'] = 'post not found';
            $response['responseCode'] = -1001;
            return response($response, 200);
        }

    }


    public function uploadPhoto(Request $request, $postId)
    {

        if ($request->type == "post") {
            try {
                // Validate the incoming request data
                $request->validate([
                    'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
                ]);

                $uploadedPhotos = [];

                foreach ($request->file('photos') as $photo) {

                    // Store each photo in a directory, generate unique file names, and save them
                    $fileName = uniqid('photo_') . '.' . $photo->extension();

                    // Move and save each photo to the desired directory
                    $photo->move(public_path('/users-community-images'), $fileName);
                    $uploadedPhotos[] = $fileName;
                }

                $data = [
                    'photos' => $uploadedPhotos,
                ];

                if (CommunityHasPosts::where('id', $postId)->update($data)) {

                    $response['responseMessage'] = 'Photos uploaded successfully';
                    $response['responseCode'] = 00;
                    return response()->json($response, 200);
                } else {
                    $response['responseMessage'] = 'failed';
                    $response['responseCode'] = -1001;
                    return response()->json($response, 200);
                }
            } catch (\Throwable $th) {
                $response['responseMessage'] = 'Oh snap! something went wrong!';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }
        }
         if ($request->type == "comment") {

                // Validate the incoming request data
                $request->validate([
                    'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
                ]);

                $uploadedPhotos = [];

                foreach ($request->file('photos') as $photo) {

                    // Store each photo in a directory, generate unique file names, and save them
                    $fileName = uniqid('photo_') . '.' . $photo->extension();

                    // Move and save each photo to the desired directory
                    $photo->move(public_path('/users-community-images'), $fileName);
                    $uploadedPhotos[] = $fileName;
                }


                $data = [
                    'photos' => $uploadedPhotos,
                ];

                CommunityHasComments::where('id', $postId)->update($data);



        }

         if ($request->type == "reply") {

            // Validate the incoming request data
            $request->validate([
                'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
            ]);

            $uploadedPhotos = [];

            foreach ($request->file('photos') as $photo) {

                // Store each photo in a directory, generate unique file names, and save them
                $fileName = uniqid('photo_') . '.' . $photo->extension();

                // Move and save each photo to the desired directory
                $photo->move(public_path('/users-community-images'), $fileName);
                $uploadedPhotos[] = $fileName;
            }


            $data = [
                'photos' => $uploadedPhotos,
            ];
            CommunityCommentHasReply::where('id', $postId)->update($data);
    }

    }
}
