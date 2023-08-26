<?php

namespace App\Mail\WebMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendUserNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $subject;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $subject, $message)
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
              ->markdown('web-emails.send-user-notification-mail');
    }
}
