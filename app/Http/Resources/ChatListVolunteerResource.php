<?php

namespace App\Http\Resources;

use App\Models\Chat;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VoluteerAppointment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ChatListVolunteerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $role = null;
            $role = "volunteer";
            $user = User::where('id', $this->user_id)->first([
                'id as _id',
                'status',
                'email',
                'phone',
                'username as name',
                \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/users-images/', profile_pic), '" . env('APP_URL') . "/users-images/avatar.JPG') as avatar"),
            ]);


        $owner = User::where('id', auth()->user()->id)->first([
            'id as _id',
            'username as name',
            \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/users-images/', profile_pic), '" . env('APP_URL') . "/users-images/avatar.JPG') as avatar"),
        ]);

        // Replace $date with your actual date
        $date = Carbon::parse($this->created_at);

        // Format the date in a human-readable way
        $formattedDate = $date->diffForHumans();

        // Fetch most recent text and time
        $chat = Chat::where(['chat_id' => $this->chat_id])
            ->latest('created_at')
            ->select('text', 'created_at')
            ->first();


            // $appointment = VoluteerAppointment::where([
            //     'user_id' => $this->user_id,
            //     'volunteer_id' => $this->volunteer_id,
            //     'appointment_id' =>$this->appointment_id,
            //     'status' => 'approved'
            // ])->whereNot('appointment_id', 0)
            // ->whereDate('appointment_date', '=', Carbon::now()->toDateString())
            // ->whereTime('appointment_time', '>=', Carbon::now())
            // ->first();


            $appointment = VoluteerAppointment::where([
                'id' =>$this->appointment_id,
                'status' => 'accepted'
            ])->first(['appointment_date','appointment_time','notes']);

            $resp = [];
            // Check if appointment date is in the future
            if ($appointment->appointment_date < Carbon::now()->toDateString()) {
                // Your code if the appointment time is less than now
                if (Carbon::parse($appointment->appointment_time) < Carbon::now()) {
                    $resp = [
                        'access' => false,
                        'message' => 'Uh-oh, It\'s past your appointment time'
                    ];
                }
                 else {
                    $resp = [
                        'access' => true,
                        'message' => 'Access granted'
                    ];
                }
            } else {
                $resp = [
                    'access' => false,
                    'message' => 'Today is not your appointment date'
                ];
            }



        return [
            'id' => $this->_id,
            'text' => Crypt::decrypt($chat->text),
            'created_at' => $formattedDate,
            'user' => $user,
            'chat_id' => $this->chat_id,
            'role' => $role,
            'owner' => $owner,
            'appointment' => $appointment,
            'access' =>$resp
        ];
    }
}
