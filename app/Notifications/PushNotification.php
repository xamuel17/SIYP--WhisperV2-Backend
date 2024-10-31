<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\OneSignalService;

class PushNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $additionalData;

    public function __construct(string $title, string $message, array $additionalData = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->additionalData = $additionalData;
    }

    public function via($notifiable)
    {
        return ['onesignal'];
    }

    public function toOneSignal($notifiable)
    {
        $oneSignal = new OneSignalService();

        return $oneSignal->sendNotificationToUsers(
            [$notifiable->player_id], // Assuming player_id is stored in your user model
            $this->title,
            $this->message,
            $this->additionalData
        );
    }
}
