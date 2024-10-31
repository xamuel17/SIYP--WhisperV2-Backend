<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use App\Models\Notifications;
use App\Models\User;
use App\Notifications\PushNotification;
use App\Services\OneSignalService;
use Berkayk\OneSignal\OneSignalServiceProvider;

class NotificationsController extends Controller
{

    protected $oneSignal;

    public function __construct(OneSignalServiceProvider $oneSignal)
    {
        $this->oneSignal = $oneSignal;
    }

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



    public function sendNotification(Request $request)
    {
        try {
            $response = $this->oneSignal->sendNotificationToUsers(
                [$request->player_id],
                $request->title,
                $request->message,
                $request->additional_data ?? []
            );

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sendToAll(Request $request)
    {
        try {
            $response = $this->oneSignal->sendNotificationToAll(
                $request->title,
                $request->message,
                $request->additional_data ?? []
            );

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

       // Using Laravel's notification system
       public function sendUsingNotification(Request $request)
       {
           $user = User::where('id', $request->user_id)->first();
           $user->notify(new PushNotification(
            $request->title,
            $request->message,
               ['type' => 'personal']
           ));
       }

       // Send based on filters
       public function sendFiltered(Request $request)
       {
           $filters = [
               [
                   'field' => 'tag',
                   'key' => 'level',
                   'relation' => '>',
                   'value' => '10'
               ],
               [
                   'operator' => 'AND'
               ],
               [
                   'field' => 'amount_spent',
                   'relation' => '>',
                   'value' => '100'
               ]
           ];

           $response = $this->oneSignal->sendNotificationByFilter(
               $filters,
               $request->title,
               $request->message,
               ['type' => 'promo']
           );

           return response()->json($response);
       }

       // Schedule a notification
       public function scheduleNotification(Request $request)
       {
           $playerIds = $request->playerIds;
           $response = $this->oneSignal->scheduleNotification(
               $playerIds,
               $request->title,
               $request->message,
               $request->time,
               ['type' => 'scheduled']
           );

           return response()->json($response);
       }
}
