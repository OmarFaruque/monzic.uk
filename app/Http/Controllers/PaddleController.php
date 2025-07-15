<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quote;
use App\Models\Setting;
use App\Models\AiDocument;
use Illuminate\Http\Request;
use App\Mail\VerifyEmailMail;
use App\Mail\AiDocumentReadyMail;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PaddleController extends Controller
{


        /**
     * Padle webhook process
     */
    public function paddleWebhook(Request $request){
        $payload = $request->all();
        
        

        if (($payload['event_type'] ?? '') === 'transaction.completed' && isset($payload['data']['custom_data']['doc_uuid'])) {
            // Retrieve previously stored content from session or DB

            
            
            $uuid = $payload['data']['custom_data']['doc_uuid'] ?? null;

            if (!$uuid) {
                Log::warning('Missing doc_uuid in custom_data');
                return response('Missing UUID', 400);
            }

            $aiDoc = AiDocument::where('uuid', $uuid)->first();

            if (!$aiDoc) {
                Log::error('Document not found for UUID: ' . $uuid);
                return response('Not found', 404);
            }

            // Generate PDF from content
            $pdf = Pdf::loadView('ai-pdf-template', [
                'content' => $aiDoc->content,
                'title' => $aiDoc->prompt,
            ]);

            $pdfFileName = 'ai_doc_' . now()->timestamp . '.pdf';
            $pdfPath = "ai-docs/{$pdfFileName}";

            Storage::disk('public')->put($pdfPath, $pdf->output());

            // Save to DB
            $aiDoc->update([
                'paddle_checkout_id' => $payload['data']['id'] ?? null,
                'pdf_path'           => $pdfPath,
                'amount'             => isset($payload['data']['details']['payout_totals']['total']) ? ((float) $payload['data']['details']['payout_totals']['total']) / 100: null,
                'currency'           => $payload['data']['payout_totals']['currency_code'] ?? 'USD',
                'status'             => 'paid',
            ]);

            $emailTemplate = Setting::where('param', 'page[ai_email]')->value('value');

            $placeholders = [
                '[username]'      => $aiDoc->user->name ?? 'Customer',
                '[document_link]' => asset('storage/' . $pdfPath),
                '[document_title]'=> $aiDoc->prompt,
                '[created_at]'    => $aiDoc->created_at->format('F j, Y'),
            ];

            $finalEmailBody = str_replace(array_keys($placeholders), array_values($placeholders), $emailTemplate);
            Mail::to($aiDoc->user->email)->send(new AiDocumentReadyMail($finalEmailBody));

            return response()->json(['success' => true]);
        }


        if (($payload['event_type'] ?? '') === 'transaction.completed' && isset($payload['data']['custom_data']['qid'])) {
            $qid = $payload['data']['custom_data']['qid'];
            $quote = Quote::find($qid);

            $quote->payment_status = 1;
            $quote->save();

            $quote = $this->adjustOrderStartTime($quote);

            Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));
            return response()->json(['success' => true]);
        }

        return response()->json(['ignored' => true]);
            
    }



    private function adjustOrderStartTime($quote)
    {

        // Combine start date and time into a timestamp
        $startTimestamp = strtotime("{$quote->start_date} {$quote->start_time}");
        $currentTimestamp = time(); // Current time based on the server's timezone

        // Check if the start time is behind the current time
        if ($startTimestamp <= $currentTimestamp) {

            // Calculate the next nearest 5th minute mark
            $adjustedStartTimestamp = ceil($currentTimestamp / 300) * 300;

            $quote->start_date = date('Y-m-d', $adjustedStartTimestamp);
            $quote->start_time = date('H:i:s', $adjustedStartTimestamp);

            // Calculate the duration difference and adjust the end time accordingly
            $endTimestamp = strtotime("{$quote->end_date} {$quote->end_time}");
            $duration = $endTimestamp - $startTimestamp; // Maintain the original duration
            $adjustedEndTimestamp = $adjustedStartTimestamp + $duration;

            $quote->end_date = date('Y-m-d', $adjustedEndTimestamp);
            $quote->end_time = date('H:i:s', $adjustedEndTimestamp);

            // Save the updated quote
            $quote->save();
        }
        return $quote;
    }




    public function checkoutRegistration(Request $request)
    {

        // Save imntent seoartedly
        $validatedData = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:quotes,id',
                'new_email' => 'required|unique:users,email',
                'first_name' => 'required|string|min:1',
                'last_name' => 'required|string|min:1',
                'new_password' => 'required|min:6',
            ]
        );


        if ($validatedData->fails()) {

            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'errors' => $validatedData->errors()
            ], 400);
        }


        $quote = Quote::find($request->id);

        // If payment already completed
        if ($quote->payment_status == 1) {
            return response()->json([
                'message' => "This payment has already been confirmed",
            ], 500);
        }


        $rdata = [];




        $user = new User();
        $user->first_name = strip_tags($request->first_name);
        $user->last_name = strip_tags($request->last_name);
        $user->email = $request->new_email;
        $user->password = Hash::make($request->new_password);
        $user->save();

        if ($quote != null) {
            $quote->user_id = $user->user_id;
            $quote->save();
        }

        Auth::login($user);
        // Regenerate the current sesssion 
        $request->session()->regenerate();
        $rdata["user_name"] = $user->first_name . " " . $user->last_name;
        $rdata["user_email"] = $user->email;
        $rdata["user_address"] = $user->address;
        $rdata["token"] = csrf_token();


        $executed = RateLimiter::attempt(
            'send-mail' . $user->user_id,
            $perTwoMinutes = 5,
            function () use ($user) {
                // We will send verification Email now;
                Mail::to($user)->queue(new VerifyEmailMail($user));
            },
            $decayRate = 120,
        );

        if (!$executed) {

            return response()->json([
                'status' => false,
                'message' => "Too many messages, try again later",
            ], 400);
        }


        return response()->json($rdata);

    }
}
