<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reminder extends Mailable
{
    use Queueable, SerializesModels;
  
    public $user;
    public $type;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $type)
    {
        $this->user = $user;
        $this->type = $type;
    }
  
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->type == 'week'){
            return $this->subject('We Miss You! Complete Your Prokip Partners Registration')->view('emails.signup-reminder-week', ['name'=>$this->user->first_name]);
        }
        if($this->type == '3days'){
            return $this->subject('We Miss You! Complete Your Prokip Partners Registration')->view('emails.signup-reminder-3days', ['name'=>$this->user->first_name]);
        }
        if($this->type == '3hours'){
            return $this->subject('Hi '.$this->user->first_name.'! Complete Your Prokip Partners Registration')->view('emails.signup-reminder-3hours', ['name'=>$this->user->first_name]);
        }
    }
}
