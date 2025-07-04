<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $message;


    public $emailType;   
    public $emailBody;
    public $ticketLink;

    public $replyToID;



    /**
     * Create a new message instance.
     */
    public function __construct($message, $replyToID=null)
    {
        
        $this->ticket = $message->ticket;
        $this->message = $message;
        $this->replyToID = $replyToID;



        if($replyToID != null){
            if($message->is_admin){
                $this->emailType = 'admin_reply'; // 'receipt', 'user_reply', 'admin_reply'
            }
            else{
                $this->emailType = 'user_reply';
            }

        }
        else{
            $this->emailType = "receipt";
        }
        
        
        $this->emailBody = $message->message;
        $this->ticketLink = url("/ticket/".$this->ticket->token.'?fn='.urlencode($this->ticket->email).'&tm='.time());


        if($this->ticket->policy_number != null){

            // $this->policy_number = $request['policy_number'];
            $this->subject = "Policy #".$this->ticket->policy_number.' - '.  strip_tags($this->ticket->subject);

    
        }
        else{
            
            $this->subject = "Ticket #" . $this->ticket->ticket_id . ' - ' . strip_tags($this->ticket->subject);
        }
        

    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        
        $domain = config('imap.domain');

        $ticket_address = $this->ticket->token.'@'.$domain;

        return new Envelope(
            // Use the default in config.mail.php
            from: new Address($ticket_address, config('app.name').' '.'Ticket'),
            replyTo: [
                new Address($ticket_address, config('app.name').' '.'Ticket'),
            ],
            subject: $this->subject,
        );
    }


    /**
 * Get the message headers.
    */
    public function headers(): Headers
    {



        if($this->replyToID != null){
            return new Headers(
                messageId: $this->message->message_id,
                references: [$this->replyToID],
                text: [
                    'In-Reply-To' => $this->replyToID,
                ],
            );

        }
        else{
            return new Headers(
                messageId: $this->message->message_id,
                references: [],
                // text: [
                //     // 'In-Reply-To' => $this->message->message_id,
                // ],
            );

        }    
    }




    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
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
