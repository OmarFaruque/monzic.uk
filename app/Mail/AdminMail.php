<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email_message;
    public $subject;



    /**
     * Create a new message instance.
     */
    public function __construct($user, $subject, $email_message)
    {
        
        $this->subject = $subject;
        $this->email_message = $email_message;        

    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        
        return new Envelope(
            // Use the default in config.mail.php
            // from: new Address($ticket_address, config('app.name').' '.'Ticket'),
            // replyTo: [
            //     new Address($ticket_address, config('app.name').' '.'Ticket'),
            // ],
            subject: $this->subject,
        );
    }




    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-mail',
        );
    }
    


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }



}
