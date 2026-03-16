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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PaddleController extends Controller
{


    /**
     * Padle webhook process
    */
    public function paddleWebhook(Request $request){
        $payload = $request->all();

        // Log::info('Received Paddle Webhook: ' . json_encode($payload, JSON_PRETTY_PRINT));
        $eventType = (string) ($payload['event_type'] ?? '');

        if (($eventType === 'adjustment.updated' || $eventType === 'adjustment.created') && isset($payload['data']['action'])) {
            $action = strtolower((string) ($payload['data']['action'] ?? ''));
            $status = strtolower((string) ($payload['data']['status'] ?? ''));

            $isChargeback = str_contains($action, 'chargeback') || str_contains($action, 'charge_back');
            $isRefund = str_contains($action, 'refund');

            if ($isRefund || $isChargeback) {
                $eventLabel = $isChargeback ? 'chargeback' : 'refund';
                $transactionId = $payload['data']['transaction_id'] ?? null;

                if ($transactionId) {
                    $quote = Quote::where('spayment_id', 'paddle_' . $transactionId)->first();
                    if ($quote) {
                        $quote->refund_state = 'paddle_' . $eventLabel . '_' . ($status ?: 'pending');
                        $quote->save();
                    }
                }

                $discordWebhookUrl = Setting::where('param', 'paddle_discord_webhook_url')->value('value');
                if ($discordWebhookUrl) {
                    $amount = $payload['data']['totals']['total'] ?? null;
                    $currency = $payload['data']['totals']['currency_code'] ?? ($payload['data']['currency_code'] ?? '');
                    $reason = $payload['data']['reason'] ?? 'N/A';
                    $orderNumber = isset($quote) && $quote ? $quote->policy_number : 'N/A';
                    $customerEmail = isset($quote) && $quote && $quote->user ? $quote->user->email : 'N/A';

                    $amountText = is_numeric($amount) ? number_format(((float) $amount) / 100, 2) : 'N/A';

                    try {
                        Http::timeout(8)->post($discordWebhookUrl, [
                            'content' => "🚨 Paddle " . strtoupper($eventLabel) . " 
                            Order: {$orderNumber}
                            Transaction: " . ($transactionId ?: 'N/A') . "
                            Customer: {$customerEmail}
                            Amount: {$currency} {$amountText}
                            Status: " . ($status ?: 'N/A') . "
                            Reason: {$reason}",
                        ]);
                    } catch (\Throwable $e) {
                        Log::warning('Failed to send Paddle refund/chargeback notification to Discord', [
                            'error' => $e->getMessage(),
                            'event_type' => $eventType,
                            'transaction_id' => $transactionId,
                        ]);
                    }
                }

                return response()->json(['success' => true]);
            }
        }

        // [2026-02-10 16:41:48] local.INFO: Received Paddle Webhook {"payload":{"data":{"id":"txn_01kh46vk3rv3827xwq8kdxysfb","items":[{"price":{"id":"pri_01kh46v5zqexjrst0wwx70fd4t","name":null,"type":"standard","status":"active","quantity":{"maximum":100,"minimum":1},"tax_mode":"internal","created_at":"2026-02-10T16:41:07.831057Z","product_id":"pro_01kh3m2wp7jdtv5b5chz8wp13x","unit_price":{"amount":"12705","currency_code":"GBP"},"updated_at":"2026-02-10T16:41:07.831057Z","custom_data":null,"description":"One-time purchase for Car Insurance Quote","trial_period":null,"billing_cycle":null,"unit_price_overrides":[]},"price_id":"pri_01kh46v5zqexjrst0wwx70fd4t","quantity":1,"proration":null}],"origin":"web","status":"paid","details":{"totals":{"fee":null,"tax":"1657","total":"12705","credit":"0","balance":"0","discount":"0","earnings":null,"subtotal":"11048","grand_total":"12705","currency_code":"GBP","credit_to_balance":"0"},"line_items":[{"id":"txnitm_01kh46vrym9ddazacp43gjmgtb","totals":{"tax":"1657","total":"12705","discount":"0","subtotal":"11048"},"item_id":null,"product":{"id":"pro_01kh3m2wp7jdtv5b5chz8wp13x","name":"Quote","type":"standard","status":"active","image_url":null,"created_at":"2026-02-10T11:13:17.511Z","updated_at":"2026-02-10T11:13:17.511Z","custom_data":null,"description":"Quote for Car Registration","tax_category":"standard","consents_required":[]},"price_id":"pri_01kh46v5zqexjrst0wwx70fd4t","quantity":1,"tax_rate":"0.15","unit_totals":{"tax":"1657","total":"12705","discount":"0","subtotal":"11048"},"is_tax_exempt":false,"revised_tax_exempted":false}],"payout_totals":null,"tax_rates_used":[{"totals":{"tax":"1657","total":"12705","discount":"0","subtotal":"11048"},"tax_rate":"0.15"}],"adjusted_totals":{"fee":null,"tax":"1657","total":"12705","earnings":null,"subtotal":"11048","grand_total":"12705","retained_fee":"0","currency_code":"GBP"}},"checkout":{"url":"https://75af-103-16-25-24.ngrok-free.app?_ptxn=txn_01kh46vk3rv3827xwq8kdxysfb"},"payments":[{"amount":"12705","status":"captured","created_at":"2026-02-10T16:41:44.700041Z","error_code":null,"captured_at":"2026-02-10T16:41:46.630639Z","method_details":{"card":{"type":"visa","last4":"4242","expiry_year":2029,"expiry_month":11,"cardholder_name":"sfsf"},"type":"card","south_korea_local_card":null},"payment_method_id":"paymtd_01kh46w9yz9tx4z7v84rsdn9xt","payment_attempt_id":"cce9e2b1-0ebf-4412-915c-d8033278ce16","stored_payment_method_id":"1e4d6010-80c2-40a7-904a-ed2bcc12db97"}],"billed_at":"2026-02-10T16:41:47.235323568Z","address_id":"add_01kh46vrqj1d46gw2z6cvp2e2z","created_at":"2026-02-10T16:41:21.305162Z","invoice_id":null,"revised_at":null,"updated_at":"2026-02-10T16:41:47.235325202Z","business_id":null,"custom_data":{"qid":3847,"tip":0,"item_type":"quote"},"customer_id":"ctm_01kh3tkwe96g8yqzt1wcx9tkg6","discount_id":null,"receipt_data":null,"currency_code":"GBP","billing_period":null,"invoice_number":null,"billing_details":null,"collection_mode":"automatic","subscription_id":null},"event_id":"evt_01kh46wcfdbkewm7w0kz03rp9y","event_type":"transaction.paid","occurred_at":"2026-02-10T16:41:47.245787Z","notification_id":"ntf_01kh46wctgvn8gr1vj037417mg"}} 
        if(!isset($payload['data']['status'])){
            Log::warning('Paddle Webhook Missing Status', ['payload' => $payload]);
            return response()->json(['error' => 'Missing status in payload'], 400);
        }

            

        if (( $payload['data']['status'] ?? '') === 'paid' && isset($payload['data']['custom_data']['doc_uuid'])) {
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
                'amount'             => (isset($payload['data']['details']['totals']['total']) ? ((float) $payload['data']['details']['totals']['total']) / 100: null),
                'currency'           => $payload['data']['currency_code'] ?? 'GBP',
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
 

        if (($payload['data']['status'] ?? '') === 'paid' && isset($payload['data']['custom_data']['qid'])) {
            $qid = $payload['data']['custom_data']['qid'];

            $quote = Quote::find($qid);
            $quote->payment_status = 1;
            
            $quote = $this->adjustOrderStartTime($quote);

            Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));

            $quote->mail_sent = Carbon::now();
            $quote->spayment_id = $payload['data']['id'] ? 'paddle_' . $payload['data']['id'] : null;

            $quote->save();
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
