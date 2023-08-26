<?php

namespace App\Mail\WebMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin, $url)
    {
        $this->admin = $admin;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Password Reset Link')
            ->markdown('web-emails.password-reset-mail');
    }
}
