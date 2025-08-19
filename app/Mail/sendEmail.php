<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail_details;

    public function __construct($mail_details)
    {
        $this->mail_details = $mail_details;
    }

    public function build()
    {
        return $this->subject($this->mail_details['subject'])
                    ->view('emails.sendEmail')
                    ->with(['body' => $this->mail_details['body']]);
    }
}
