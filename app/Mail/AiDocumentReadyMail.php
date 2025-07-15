<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AiDocumentReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $body;
    /**
     * Create a new message instance.
     */
    public function __construct($body)
    {
        $this->body = $body;
    }


    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject('Your AI Document is Ready')
            ->html($this->body);
    }
 
}
