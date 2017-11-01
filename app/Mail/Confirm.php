<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Auth;
use Symfony\Component\CssSelector\Tests\Parser\ReaderTest;

class Confirm extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
//        $this->user = Auth::user();
        $this->user['confirmation_code']= $user->confirmation_code;
        $this->user['other_email']= $user->other_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.emailconfirm');
    }
}
