<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\ChatListResource;
use App\Http\Resources\ChatListVolunteerResource;
use App\Http\Resources\VolunteerResource;
use App\Models\Chat;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VoluteerAppointment;
use App\Models\VoluteerAvailableDays;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createVolunteer(Request $request)
    {
        $rules = array(
            'email' => 'required|email|unique:volunteers',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response['responseMessage'] = 'Email address for a volunteer has already been registered.';
            $response['responseCode'] = -1001;
            $response['data'] = $validator->errors();
            return response()->json($response, 200);
        }else{
           $volunteer= Volunteer::create([
                'user_id' => auth()->user()->id,
                'username' => $request->username,
                'role' => $request->role,
                'session'=> 0,
                'description'=> $request->description,
                'email' => $request->email,
                'phone' =>$request->phone
            ]);
            if(isset($volunteer)){
                $response['responseMessage'] = 'Your application to volunteer as a '.$request->role.' for the SYIP app has been accepted';
                $response['responseCode'] = 00;
                return response()->json($response, 200);
            }else{
                $response['responseMessage'] = 'volunteer application failed';
                $response['responseCode'] = -1001;
                return response()->json($response, 200);
            }


        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewVolunteers()
    {
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = VolunteerResource::collection(Volunteer::where('status', 'active')->get());
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadPhoto(Request $request)
    {
        $input = $request->all();
        $extension = $request->file('photo')->extension();

        $fileName = time() . "." . $extension;
        $fileName = "volunteer(" . auth()->user()->id . ")" . $fileName;
        $path = $request->file('photo')->move(public_path("/volunteer-images"), $fileName);

        $photoURL = url('/' . $fileName);

        $data = [
            'photo' => $fileName,
        ];

       if(Volunteer::where('user_id', auth()->user()->id)->update($data)){
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = $photoURL;
        return response()->json($response, 200);
       }else{
        $response['responseMessage'] = 'failed';
        $response['responseCode'] = -1001;
        return response()->json($response, 200);
       }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function activateDeactivateVolunteer(Request $request)
    {
        if(Volunteer::where('user_id', auth()->user()->id)->update(['status' => $request->status])){
            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
           }else{
            $response['responseMessage'] = 'failed';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
           }
    }


    public function volunteerAvailableDays($id)
    {
        $response['responseMessage'] = "success";
        $response['responseCode'] = 00;
        $response['data'] = VoluteerAvailableDays::where('volunteer_id', $id)->get();
        return response()->json($response, 200);
    }

    public function volunteerDeleteAvailableDays($id){

        $volunteer = VoluteerAvailableDays::where([
            'volunteer_id' => auth()->user()->id,
            'id' => $id
        ])->delete();
        if(isset( $volunteer )){
            $response['responseMessage'] = 'Available time has been removed';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
           }else{
            $response['responseMessage'] = 'Ohh Snap! Something went wrong';
            $response['responseCode'] = 1001;
            return response()->json($response, 200);
           }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function volunteerSetAppointmentDates(Request $request)
    {

        $volunteerId = Volunteer::where('user_id', auth()->user()->id)->first()->id;
        $volunteer = VoluteerAvailableDays::create([
            'volunteer_id'=>auth()->user()->id,
            'day_of_week' => $request->day_of_week,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ]);
        $date = Carbon::parse($request->date);
        $formattedDate = $date->format('F j, Y');
        if(isset( $volunteer )){
            $response['responseMessage'] = 'Appointment  time for ' .$formattedDate. ' has been set.';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
           }else{
            $response['responseMessage'] = 'Ohh Snap! Something went wrong';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
           }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function retrieveVolunteerAvailableTime(Request $request)
    {

        $availableTimeSlots = VoluteerAvailableDays::where('volunteer_id', $request->volunteer_id)
    ->where('day_of_week', $request->day_of_week)
    ->whereTime('start_time', '>=', $request->current_time) // Filter out past time slots
    ->get();

        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] =  $availableTimeSlots;
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function deleteAvailableTime($id)
    {
        $volunteer = VoluteerAvailableDays::where([
            'volunteer_id' => auth()->user()->id,
            'id' => $id
        ])->delete();
        if(isset( $volunteer )){
            $response['responseMessage'] = 'Available time has been removed';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
           }else{
            $response['responseMessage'] = 'Ohh Snap! Something went wrong';
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
           }
    }


    public function userMakeBooking(Request $request){
        if( $request->volunteer_id === auth()->user()->id){
            $response['responseMessage'] = 'You cannot book yourself!';
            $response['responseCode'] = 1001;
            return response()->json($response, 200);
        }
        $volunteer= VoluteerAppointment::create([
            'user_id' => auth()->user()->id,
            'volunteer_id' => $request->volunteer_id,
            'appointment_date'=> $request->appointment_date,
            'appointment_time'=> $request->appointment_time,
            'notes' => $request->notes,
        ]);
        if(isset($volunteer)){
            $response['responseMessage'] = 'Your request for booking is currently pending approval.';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = 'Appointment booking failed';
            $response['responseCode'] = 1001;
            return response()->json($response, 200);
        }
    }

    public function volunteerViewBooking($status){
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] = VoluteerAppointment::where(['volunteer_id' => auth()->user()->id, 'status' =>$status ])->get();
        return response()->json($response, 200);
    }


    public function updateBookingStatus($status, $id){

        $booking = VoluteerAppointment::where('volunteer_id', auth()->user()->id)
    ->whereNotIn('status', ['cancelled', 'closed', 'completed', 'accepted'])
    ->where('id', $id)
    ->first();

        $volunteer = VoluteerAppointment::where(['volunteer_id' => auth()->user()->id, 'id' =>$id ])->update(['status' => $status]);

        if(isset($booking)){

            if($status == 'accepted'){

                if(Chat::where(['user_id'=> $booking->user_id, 'volunteer_id' => auth()->user()->id])->exists()){
                }else{
                Chat::Create([
                    'user_id' => $booking->user_id,
                    'volunteer_id' => auth()->user()->id,
                    'chat_id' => (string) Str::uuid(),
                    'started' => true,
                    'appointment_id' => $booking->id,
                    'text' =>base64_encode("Chat Appointment Booking for $booking->appointment_date  $booking->appointment_time  has been accepted!")
               ]);
            }
            }
            $response['responseMessage'] = 'Appointment booking status has been updated';
            $response['responseCode'] = 00;
            return response()->json($response, 200);
        }else{
            $response['responseMessage'] = "You cant update booking status to  $status ";
            $response['responseCode'] = -1001;
            return response()->json($response, 200);
        }
    }


    public function retrieveChatList(){

        //check user role
        $chatList= null;
        if(Volunteer::where('user_id', auth()->user()->id)->exists()){
           $chatList= ChatListVolunteerResource::collection(Chat::where(['volunteer_id' => auth()->user()->id,  'started' => true])->get());
        }else{
            $chatList= ChatListResource::collection(Chat::where(['user_id' => auth()->user()->id,  'started' => true])->get());
        }
        $response['responseMessage'] = 'success';
        $response['responseCode'] = 00;
        $response['data'] =$chatList;
        return response()->json($response, 200);
    }


    public function retrieveChats($chat_id){

            $response['responseMessage'] = 'success';
            $response['responseCode'] = 00;
            $response['data'] =ChatResource::collection(Chat::where(['chat_id' => $chat_id,  'started' => null])->orderByDesc('created_at')->get());
            return response()->json($response, 200);
    }



    public function createChat(Request $request)
    {
        $userId = auth()->id();
        $volunteerId = $request->volunteer_id;
<<<<<<< Updated upstream

=======
    
>>>>>>> Stashed changes
        $chat = Chat::firstOrCreate(
            [
                'user_id' => $userId,
                'volunteer_id' => $volunteerId,
                'started' => true,
            ],
            [
                'chat_id' => Str::uuid(),
                'text' => $this->createGreetingMessage($volunteerId),
            ]
        );
<<<<<<< Updated upstream

        $photo = $request->has('photo') ? $this->uploadChatImage($request, $userId) : null;

=======
    
        $photo = $request->has('photo') ? $this->uploadChatImage($request, $userId) : null;
    
>>>>>>> Stashed changes
        $newChat = Chat::create([
            'text' => base64_encode($request->text),
            'sent' => true,
            'chat_id' => $chat->chat_id,
            'image' => $photo,
            $this->determineUserType($userId) => $userId,
        ]);
<<<<<<< Updated upstream

        if ($newChat) {
            return $this->successResponse($newChat);
        }

        return $this->failureResponse();
    }

=======
    
        if ($newChat) {
            return $this->successResponse($newChat);
        }
    
        return $this->failureResponse();
    }
    
>>>>>>> Stashed changes
    private function createGreetingMessage($volunteerId)
    {
        $volunteer = Volunteer::where('user_id', $volunteerId)->value('username');
        return base64_encode("Greetings, {$volunteer}!");
    }
<<<<<<< Updated upstream

=======
    
>>>>>>> Stashed changes
    private function determineUserType($userId)
    {
        return Volunteer::where('user_id', $userId)->exists() ? 'volunteer_id' : 'user_id';
    }
<<<<<<< Updated upstream

    private function successResponse($newChat)
    {
        $chatData = ChatResource::collection(
            Chat::where(['_id' => $newChat->_id, 'started' => null])
                ->latest('created_at')
                ->get()
        );

        return response()->json([
            'responseMessage' => 'success',
            'responseCode' => 0,
            'message' => $chatData,
            'data' => $chatData,
        ], 200);
    }

=======
    
    private function successResponse($newChat)
    {
        $chatRecord = Chat::where([
            'text' => $newChat->text,
            'chat_id' => $newChat->chat_id,
            'user_id' => auth()->id()
        ])
        ->latest('created_at')
        ->first();
    
        if (!$chatRecord) {
            return $this->failureResponse('Chat record not found');
        }
    
        $chatData = new ChatResource($chatRecord);
    
        return response()->json([
            'responseMessage' => 'success',
            'responseCode' => 0,
            'message' => $chatData
        ], 200);
    }
    
>>>>>>> Stashed changes
    private function failureResponse()
    {
        return response()->json([
            'responseMessage' => 'failed',
            'responseCode' => -1001,
        ], 200);
    }

public function uploadChatImage(Request $request, $userId)
{

    $fileName = "";
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
    $extension = $request->file('photo')->extension();
    $fileName = $userId . '-' . time() . '.' . $extension;
    $path = $request->file('photo')->move(public_path('users-chat-images'), $fileName);
    $photoURL = url('/users-chat-images/' . $fileName);

    return $fileName;
}



}
