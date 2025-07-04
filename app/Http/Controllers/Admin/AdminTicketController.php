<?php

namespace App\Http\Controllers\Admin;

use App\Mail\AdminMail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\Message;
use App\Models\Ticket;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;





use DataTables;
use CHelper;

class AdminTicketController extends Controller
{


    public function __construct(Request $request)
    {
    }


    public function index(Request $request)
    {

        $user = $request->user();
   
        // if (!$user->isAllowed(["SUPER_ADMIN"])) {
        //     return "Access Restricted";
        // }

        $users = User::select('user_id', 'first_name', 'last_name', 'email')->get();

        $tcount = Ticket::sum('unread');

        return view('admin.tickets', ["users" => $users, "tcount" => $tcount]);

    }



    public function data(Request $request)
    {

        $admin = $request->user();
        // if (!$admin->isAllowed(["SUPER_ADMIN"])) {
        //     return "Access Restricted";
        // }


        $model = Ticket::select(
            'ticket_id',
            'subject',
            'first_name',
            'last_name',
            'phone',
            'policy_number',
            'email',
            'unread',
            'is_closed',
            'updated_at',
        );

        return DataTables::of($model)
            ->escapeColumns([])
            ->make(false);


    }


    
    // View the ticket page with all messages
    public function viewTicket(Request $request, $ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->first();

        if($ticket != null){
            $messages = $ticket->messages;
            
            $ticket->unread = 0;
            $ticket->save();
        }


        return view('admin.view-ticket', compact('ticket', 'messages'));

    }


    
    
    // Reply to a ticket by the user
    public function replyToTicket(Request $request)
    {
        
        
        $validator = Validator::make($request->all(), [
            'message' => 'required|min:5',
            'ticket_id' => 'required|exists:tickets,ticket_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $domain = config('imap.domain');

        $ticket = Ticket::where('ticket_id', $request->ticket_id)->firstOrFail();


        $replyToN = $ticket->messages()->orderBy("created_at", "DESC")->first();
        $replyTo = $replyToN->message_id;

        
        // Create a message for the ticket
        $message = Message::create([
            'ticket_id' => $ticket->ticket_id,
            'message_id' => Str::random(36).'@'.$domain,  // Use a UUID or custom unique id
            'message' =>  nl2br(strip_tags($request->message)),
            'is_admin' => true,
        ]);

 
        config(['mail.reply_to' => null]);
        
        Mail::to($ticket->email)->send(new ContactMail($message, $replyTo));
        
        return response()->json([
            'status' => true,
            'message' => 'Message Sent',
        ], 200);




    }




    // Update Ticket State
    public function ticketState(Request $request)
    {
        
        $ticket = Ticket::where('ticket_id', $request->ticket_id)->firstOrFail();

        
        $ticket->is_closed = !$ticket->is_closed;
        $ticket->save();

        
        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);

    }

    public function deleteTicket(Request $request, $ticket_id)
    {

        $user = $request->user();
        // if (!$user->isAllowed(["SUPER_ADMIN"])) {
        //     return "Access Restricted";
        // }

        $validator = Validator::make(["ticket_id" => $ticket_id], [
            'ticket_id' => 'required|exists:tickets,ticket_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $ticket = Ticket::where("ticket_id", $ticket_id)->first();


        $ticket->delete();

        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }




    
    // Reply to a ticket by the user
    public function emailUsers(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email_message' => 'required|string|min:5',
            'email_subject' => 'required|string|min:5',
            'send_type' => 'required|string|min:3',
            'email_users' => 'required_unless:send_type,all|array',
            'email_users.*' => 'required|integer',
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        if($request->send_type == "all"){

            $users = User::select('user_id', 'first_name', 'last_name', 'email')
            ->whereNotNull('email_verified_at')
            ->get();
        }
        else{

            $users = User::select('user_id', 'first_name', 'last_name', 'email')
            ->whereNotNull('email_verified_at')
            ->whereIn('user_id', $request->email_users)
            ->get();

        }

        

        foreach($users as $user){

            $message = $request->email_message;
            $subject = $request->email_subject;

            Mail::to($user->email)->send(new AdminMail($user, $subject, $message));

        }

        
        


        return response()->json([
            'status' => true,
            'message' => 'Message Sent',
        ], 200);




    }





}
