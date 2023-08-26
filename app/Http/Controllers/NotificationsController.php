<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use App\Models\Notifications;
use Illuminate\Notifications\Notification;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
         * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getNotification($id)
    {
        //fetch notifications
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] =NotificationResource::collection(Notifications::orderBy('created_at', 'DESC')->where('user_id',$id)
           ->whereNotIn('status', ['commit'])
           ->get());
        return response()->json($response, 200);



    }








 /**
     * Show the form for creating a new resource.
         * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkNotifications($id)
    {

       $conditions = array(
            'user_id'=>$id,
            'status'=> 'unread'
        );



        $notification=Notifications::where($conditions)->get()->count();
        if($notification == 0){
              $response['responseMessage'] = 'no';
              $response['count']=$notification;
        }else{
                  $response['responseMessage'] = 'yes';
                       $response['count']=$notification;
        }



        $response['responseCode'] = 00;

              return response()->json($response, 200);

    }





    /**
     * Show the form for creating a new resource.
         * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function readNotifications($id,$postId)
    {
        //
        $conditions = array(
            'user_id'=>$id,
            'id'=> $postId
        );

        Notifications::where($conditions)->update(array('status' => 'read'));

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] =Notifications::where($conditions)->get();
        return response()->json($response, 200);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $msg = new Notifications();
        $msg->admin_id = $request->admin_id;
          $msg->user_id=$request->user_id;
        $msg->title =$request->title;
        $msg->content = $request->content;
        $msg->status = 'unread';
        $msg->save();
        $response['responseMessage'] = 'Notification Created';
        $response['responseCode'] = 00;
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function show(Notifications $notifications)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function edit(Notifications $notifications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notifications $notifications)
    {
        //
    }

  /**
     * Display a listing of the resource.
         * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteNotification($id)
    {
        //
      //  Notifications::where('id', $id)->delete();
      Notifications::where('id', $id)->update(array('status' => 'commit'));
         $response['responseMessage'] = 'Notification Deleted';
        $response['responseCode'] = 00;
        return response()->json($response, 200);
    }
}
