<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\helpers;

use Illuminate\Support\Facades\Log;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $lastname, $email, $password)
    {
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $content = view('emails.user_created', [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => $this->password,
        ])->render();

        sendMailApiLac(
            $this->email, 
            'Welcome to Latin American Cargo – Your New Account', 
            $content,
            [],
            []
        );

        return $this->html($content);
    }
}
