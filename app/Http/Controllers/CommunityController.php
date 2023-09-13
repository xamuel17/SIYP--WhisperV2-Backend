<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommunityHasPostResource;
use App\Http\Resources\CommunityResource;
use App\Models\Community;
use App\Models\CommunityHasComments;
use App\Models\CommunityHasPosts;
use App\Models\CommunityMember;
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
        if ($request->privacy == true) {

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
            $communityRemove = CommunityMember::where('community_id', $request->community_id)->first();
            if (isset($communityRemove)) {
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


    public function getCommunityPost($community_id){

        // //check if user belongs to community.
        // if (CommunityMember::where(['user_id' => auth()->user()->id, 'community_id' => $community_id])->count() == 0) {
        //     $response['responseMessage'] = 'You are not a community member';
        //     $response['responseCode'] = -1001;
        //     return response()->json($response, 200);
        // }

        //check if user is blocked
        if (CommunityMember::where(['user_id' => auth()->user()->id, 'community_id' => $community_id, 'status' => 'blocked'])->count() != 0) {
            $response['responseMessage'] = 'You have been blocked from accessing this community';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = CommunityHasPostResource::collection(CommunityHasPosts::where(['community_id' =>  $community_id, 'status' => '1'])->get());
        return response()->json($response, 200);
    }


    public function likeDislikeCommunityPostOrReply(Request $request){

        $post= null;
        if (isset($request->action) && ($request->action == "likePost" || $request->action == "dislikePost")) {
            $post = CommunityHasPosts::where('id' , $request->id)->first();
        }

        switch ($request->action) {
            case "likePost":

                break;
            case "dislikePost":
                // Code to execute if $variable matches value2
                break;
            case "dislikeReply":
                // Code to execute if $variable matches value2
                break;
            case "likeReply":
                // Code to execute if $variable matches value2
                break;
            default:
            $response['responseMessage'] = 'Ohh Snap! something went wrong.';
            $response['responseCode'] = -1001;
                break;
        return response()->json($response, 200);
        }

    }

    public function replyCommunityPost(Request $request){}
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
        } else if ($request->type == "comment") {

            $rules = array(
                'id' => 'required|exists:community_has_comments,id'
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {

                $response['responseMessage'] = 'Opps, community post doesn\'t exist!';
                $response['responseCode'] = -1001;
                $response['data'] = $validator->errors();
                return response()->json($response, 200);
            }


                // Validate the incoming request data
                $request->validate([
                    'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust validation rules as needed
                ]);

                $uploadedPhotos = [];

                foreach ($request->file('photos') as $photo) {

                    // Store each photo in a directory, generate unique file names, and save them
                    $fileName = uniqid('photo_') . '.' . $photo->getClientOriginalExtension();

                    $photo->move(public_path('/users-community-images'), $fileName);
                    // Optionally, you can save file paths to the database or return URLs
                    $uploadedPhotos[] = $fileName;
                    $data = [
                        'photo' => $fileName,
                    ];
                }

                $data = [
                    'photos' => $uploadedPhotos,
                ];

                CommunityHasComments::where('id', $request->comment_id)->update($data);



        }

    }
}
