<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

use Illuminate\Support\Facades\Validator;


class TicketController extends Controller
{

    // Display form to create a new ticket (Contact form)
    public function showContactForm()
    {
        return view('contact');
    }



    // Store new ticket and message from the user
    public function newTicket(Request $request)
    {
        
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3|max:100',
            'last_name' => 'required|string|min:3|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'subject' => 'required|string|min:5',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $domain = config('imap.domain');

        // Create a new ticket
        $ticket = Ticket::create([
            'first_name' => strip_tags($request->first_name),
            'last_name' => strip_tags($request->last_name),
            'subject' => strip_tags($request->subject),
            'email' => $request->email,
            'phone' => strip_tags($request->phone),
            'token' => Str::random(36),
            'is_closed' => false,
        ]);

        if($request->has("policy_number")){
            $ticket->policy_number = strip_tags($request->policy_number);
            $ticket->save();
        }

        if(!$ticket){
            return response()->json([
                'status' => false,
                'message' => 'Error creating Ticket',
            ], 500);
        }

        // Create a message for the ticket
        $message = Message::create([
            'ticket_id' => $ticket->ticket_id,
            'message_id' => Str::random(36).'@'.$domain,  // Use a UUID or custom unique id
            'message' =>  nl2br(strip_tags($request->message)),
            'is_admin' => false,
        ]);


        config(['mail.reply_to' => null]);
        
        Mail::to($ticket->email)->send(new ContactMail($message));
        
        return response()->json([
            'status' => true,
            'message' => 'Message Sent',
        ], 200);

    }





    // View the ticket page with all messages
    public function viewTicket(Request $request, $token)
    {
        $ticket = Ticket::where('token', $token)->first();

        if($ticket != null){
            $messages = $ticket->messages;    
        }
        $emailPassed = false;
        if($request->has('fn') && $request->fn == $ticket->email){
            $emailPassed = true;
        }

        return view('view-ticket', compact('ticket', 'messages', 'emailPassed'));

    }





    // Reply to a ticket by the user
    public function replyToTicket(Request $request)
    {
        
        
        $validator = Validator::make($request->all(), [
            'message' => 'required|min:10',
            'token' => 'required|exists:tickets,token',
            'email' => 'required|email|exists:tickets,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $domain = config('imap.domain');

        $ticket = Ticket::where('token', $request->token)
        ->where('email', $request->email)->firstOrFail();

        if($ticket->is_closed == 1){

            return response()->json([
                'status' => false,
                'message' => 'Ticket has been closed',
            ], 400);

        }

        $replyToN = $ticket->messages()->orderBy("created_at", "DESC")->first();
        $replyTo = $replyToN->message_id;

        
        // Create a message for the ticket
        $message = Message::create([
            'ticket_id' => $ticket->ticket_id,
            'message_id' => Str::random(36).'@'.$domain,  // Use a UUID or custom unique id
            'message' =>  nl2br(strip_tags($request->message)),
            'is_admin' => false,
        ]);

        $ticket->unread += 1;
        $ticket->save();

        

        config(['mail.reply_to' => null]);
        
        Mail::to($ticket->email)->send(new ContactMail($message, $replyTo));
        
        return response()->json([
            'status' => true,
            'message' => 'Message Sent',
        ], 200);




    }



}
