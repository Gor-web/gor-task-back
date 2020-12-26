<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    public $random_code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details,$random_code)
    {
        $this->details = $details;
        $this->random_code = $random_code;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('email request')->view('email');
    }

}
