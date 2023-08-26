<?php

namespace App\Mail\WebMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $code;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $code, $url)
    {
        $this->name = $name;
        $this->code = $code;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Setup your password')
                    ->markdown('web-emails.admin-activation-mail');
    }
}
