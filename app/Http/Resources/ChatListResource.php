<?php

namespace App\Http\Resources;

use App\Models\Chat;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VoluteerAppointment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ChatListResource extends JsonResource
{
    public function toArray($request)
    {
        $role = $this->user_id != null ? "user" : "volunteer";

        $user = $this->getUserInfo($role);
        $owner = $this->getOwnerInfo();
        $appointment = $this->getAppointmentInfo();
        $resp = $this->getAccessInfo($appointment);

        $chat = $this->getMostRecentChat();
        $formattedDate = $this->getFormattedDate();

        return [
            'id' => $this->_id,
            'text' => $this->getDecryptedText($chat),
            'created_at' => $formattedDate,
            'user' => $user,
            'chat_id' => $this->chat_id,
            'role' => $role,
            'owner' => $owner,
            'appointment' => $appointment,
            'access' => $resp
        ];
    }

    private function getUserInfo($role)
    {
        if ($role === "user") {
            return Volunteer::where('user_id', $this->volunteer_id)->first([
                'user_id as volunteer_id', 'role', 'status', 'email', 'phone', 'username',
                \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/volunteer-images/', photo), '" . env('APP_URL') . "/users-images/avatar.jpg') as avatar"),
            ]);
        } else {
            return User::where('id', $this->user_id)->first([
                'id as _id',
                'username as name',
                \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/users-images/', profile_pic), '" . env('APP_URL') . "/users-images/avatar.jpg') as avatar"),
            ]);
        }
    }

    private function getOwnerInfo()
    {
        return User::where('id', $this->user_id)->first([
            'id as _id',
            'username as name',
            \DB::raw("COALESCE(CONCAT('" . env('APP_URL') . "/users-images/', profile_pic), '" . env('APP_URL') . "/users-images/avatar.jpg') as avatar"),
        ]);
    }

    private function getAppointmentInfo()
    {
        return VoluteerAppointment::where([
            'id' => $this->appointment_id,
            'status' => 'accepted'
        ])->first(['appointment_date', 'appointment_time', 'notes']);
    }

    private function getAccessInfo($appointment)
    {
        if (!$appointment) {
            return [
                'access' => false,
                'message' => 'No appointment found'
            ];
        }

        $now = Carbon::now();
        $appointmentDate = Carbon::parse($appointment->appointment_date);
        $appointmentTime = Carbon::parse($appointment->appointment_time);

        if ($appointmentDate->isPast()) {
            if ($appointmentTime->isPast()) {
                return [
                    'access' => false,
                    'message' => 'Uh-oh, It\'s past your appointment time'
                ];
            } else {
                return [
                    'access' => true,
                    'message' => 'Access granted'
                ];
            }
        } else {
            return [
                'access' => false,
                'message' => 'Today is not your appointment date'
            ];
        }
    }

    private function getMostRecentChat()
    {
        return Chat::where(['chat_id' => $this->chat_id])
            ->latest('created_at')
            ->select('text', 'created_at')
            ->first();
    }

    private function getFormattedDate()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    private function getDecryptedText($chat)
    {
        if (!$chat) {
            return null;
        }

        try {
            return base64_decode($chat->text);
        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error('Failed to decrypt chat text: ' . $e->getMessage());
            return '[Encrypted]'; // or return a placeholder text
        }
    }
}
