<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use App\Models\Ticket;
use App\Models\Message;


use HTMLPurifier;
use HTMLPurifier_Config;



class ProcessSupportReplies extends Command
{
    protected $signature = 'support:process-replies';
    protected $description = 'Read catchall inbox, match user replies and save to messages table';

    public function handle()
    {
        $client = Client::account('default');
        $client->connect();

        $folder = $client->getFolder('INBOX');



        $perPage = 50;
        $page = 0;

        while (true) {



            // Fetch messages with pagination
            $messages = $folder->query()
                ->limit($perPage, $page)
                // ->page($page)
                ->setFetchOrder('asc')  // Oldest first
                ->all()->get();

            foreach ($messages as $message) {
                $subject = $message->getSubject();
                $body = $message->getHTMLBody(true) ?? $message->getTextBody();
                $from = $message->getFrom()[0]->mail;


                try {

                    $doc = new \DOMDocument();
                    libxml_use_internal_errors(true); // Disable warnings for malformed HTML
                    $doc->loadHTML('<?xml encoding="UTF-8">' . $body); // Load the HTML content

                    // Remove elements with class 'gmail_quote' and 'gmail_quote_container'
                    foreach ($doc->getElementsByTagName('div') as $div) {
                        $class = $div->getAttribute('class');
                        // strpos($class, 'gmail_quote') !== false || 
                        if (strpos($class, 'gmail_quote_container') !== false) {
                            $div->parentNode->removeChild($div); // Remove the element
                        }
                    }

                    // Get the cleaned HTML content
                    $cleanedBody = $doc->saveHTML();
                    $cleanedBody = preg_replace('/^.*<body.*?>/s', '', $cleanedBody);  // Remove the body tag wrapper added by loadHTML
                    $cleanedBody = preg_replace('/<\/body>.*$/s', '', $cleanedBody);  // Remove closing body tag

                    // Now, $cleanedBody contains the message without the quote sections

                    $body = $cleanedBody;
                } catch (\Exception $ex) {


                }




                // Remove quoted sections for HTML
                $pattern = '/<blockquote.*?>(.*?)<\/blockquote>/is';
                $body = preg_replace($pattern, '', $body);

                // Clean up unwanted parts
                $body = trim($body);        // Trim space




                $config = HTMLPurifier_Config::createDefault();
                $config->set('HTML.Allowed', 'p,a[href|title],ul,ol,li,b,strong,i,em,br,div');
                $purifier = new HTMLPurifier($config);

                $body = $purifier->purify($body);


                $to = $message->getTo()[0]->mail;  // Get the 'To' address (assuming there's only one recipient)

                $token = "nil";
                if (preg_match('/^(.+)@([a-zA-Z0-9.-]+)$/', $to, $matches)) {
                    $token = $matches[1];  // The token is in the first capture group
                    $domain = $matches[2];
                }

                $ticket = Ticket::where('token', $token)  // Lookup ticket by token (assuming you have it)
                    ->where('email', $from)  // Lookup by email (this should be the recipient's email)
                    ->first();


                if ($ticket && $ticket->is_closed == 0) {
                    try {
                        Message::create([
                            'ticket_id' => $ticket->ticket_id,
                            'message_id' => $message->getMessageId(),
                            'message' => $body,
                            'is_admin' => 0,
                        ]);
                    } catch (\Exception $ex) {

                    }

                    $ticket->unread += 1;
                    $ticket->save();

                    $this->info("Saved reply to Ticket #{$ticket->ticket_id} from {$from}");
                }


                $message->delete(); // move to trash

                // $this->info("{$from}:  {$to} {$token}");


            }
            if ($perPage > count($messages)) {
                break;
            }
            $page++;

        }



        // foreach ($folder->messages()->unseen()->get() as $message) {

        // }


    }


}
