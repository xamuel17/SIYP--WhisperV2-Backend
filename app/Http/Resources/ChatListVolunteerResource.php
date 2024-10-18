<?php

namespace App\Http\Resources;

use App\Models\Chat;
use App\Models\User;
use App\Models\VoluteerAppointment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;

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
        $avatarUrl = env('APP_URL') . '/users-images/';
        $defaultAvatar = $avatarUrl . 'avatar.jpg';

        $user = User::select(
            'id as _id',
            'status',
            'email',
            'phone',
            'username as name',
            \DB::raw("COALESCE(CONCAT('$avatarUrl', profile_pic), '$defaultAvatar') as avatar")
        )->find($this->user_id);

        $owner = User::select(
            'id as _id',
            'username as name',
            \DB::raw("COALESCE(CONCAT('$avatarUrl', profile_pic), '$defaultAvatar') as avatar")
        )->find(auth()->id());

        $chat = Chat::where('chat_id', $this->chat_id)
            ->latest('created_at')
            ->select('text', 'created_at')
            ->first();

        $appointment = VoluteerAppointment::where([
            'id' => $this->appointment_id,
            'status' => 'accepted'
        ])->select('appointment_date', 'appointment_time', 'notes')->first();

        $resp = $this->getAppointmentAccess($appointment);

        return [
            'id' => $this->_id,
            'text' => $this->getDecryptedText($chat),
            'created_at' => $this->created_at->diffForHumans(),
            'user' => $user,
            'chat_id' => $this->chat_id,
            'role' => 'volunteer',
            'owner' => $owner,
            'appointment' => $appointment,
            'access' => $resp
        ];
    }

    private function getAppointmentAccess($appointment)
    {
        if (!$appointment) {
            return ['access' => false, 'message' => 'No appointment found'];
        }

        $now = Carbon::now();
        $appointmentDate = Carbon::parse($appointment->appointment_date);
        $appointmentTime = Carbon::parse($appointment->appointment_time);

        if ($appointmentDate->isPast()) {
            if ($appointmentTime->isPast()) {
                return ['access' => false, 'message' => 'Uh-oh, It\'s past your appointment time'];
            }
            return ['access' => true, 'message' => 'Access granted'];
        }

        return ['access' => false, 'message' => 'Today is not your appointment date'];
    }

    private function getDecryptedText($chat)
    {
        if (!$chat) {
            return null;
        }

        try {
            return base64_decode($chat->text);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt chat text: ' . $e->getMessage(), [
                'chat_id' => $this->chat_id,
                'exception' => $e
            ]);
            return '[Encrypted message]';
        }
    }
}
