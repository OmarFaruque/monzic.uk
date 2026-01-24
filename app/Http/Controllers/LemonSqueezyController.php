<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Quote;
use App\Models\Setting;
use App\Models\AiDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LemonSqueezyController extends Controller
{
    public function createCheckout(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        $validated = $request->validate([
            'id' => 'required|integer',
            'type' => 'nullable|string|in:ai',
            'tip' => 'nullable|numeric|min:0',
        ]);

        try {
            $apiKey = Setting::where('param', 'lemonsqueezy_api_key')->firstOrFail()->value;
            $storeId = Setting::where('param', 'lemonsqueezy_store_id')->firstOrFail()->value;
            $variantId = Setting::where('param', 'lemonsqueezy_variant_id')->firstOrFail()->value;
        } catch (\Exception $e) {
            Log::error('Lemon Squeezy settings are not configured.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Lemon Squeezy settings are not configured correctly.'], 500);
        }
        

        $entityId = $validated['id'];
        $tip = $validated['tip'] ?? 0;
        $isAiDoc = isset($validated['type']) && $validated['type'] === 'ai';

        $description = '';
        $amount = 0;
        $metadata = [];

        if ($isAiDoc) {
            $document = AiDocument::findOrFail($entityId);
            $description = "AI Document - " . ($document->name ?? 'Document');
            $amount = $document->price;
            $metadata = ['ai_document_id' => $document->id, 'user_id' => $user->id];
        } else {
            $quote = Quote::findOrFail($entityId);

            $description = "Policy - " . ($quote->policy_name ?? 'Insurance');
            $amount = $quote->cpw;
            $metadata = ['quote_id' => (string)$quote->id, 'user_id' => (string)$user->user_id];
        }

        $totalAmount = $amount + $tip;

        $redirectUrl = route('payment.success.view');

        $response = Http::withToken($apiKey)
            ->withHeaders([
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
            ])->post('https://api.lemonsqueezy.com/v1/checkouts', [
                'data' => [
                    'type' => 'checkouts',
                    'attributes' => [
                        'checkout_data' => [
                            'email' => $user->email,
                            'custom' => $metadata,
                        ],
                        'product_options' => [
                            'name' => $description,
                            'description' => $description,
                            'receipt_button_text' => "View Your Purchase",
                            'redirect_url' => $redirectUrl,
                        ],
                        'custom_price' => round($totalAmount * 100),
                    ],
                    'relationships' => [
                        'store' => [
                            'data' => [
                                'type' => 'stores',
                                'id' => $storeId,
                            ],
                        ],
                        'variant' => [
                            'data' => [
                                'type' => 'variants',
                                'id' => $variantId,
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->successful() && $response->json('data.attributes.url')) {
            return response()->json(['checkoutUrl' => $response->json('data.attributes.url')]);
        }
        
        return response()->json(['error' => 'Could not initiate Lemon Squeezy payment.', 'details' => $response->json()], 500);
    }

    /**
     * Display success page and handle payment confirmation via localStorage
     */
    public function paymentSuccess(Request $request)
    {
        return view('payment.lemonsqueezy-success');
    }

    /**
     * API endpoint to confirm payment using stored entity ID from localStorage
     * This is the primary, secure method of confirming payments
     */
    public function confirmPayment(Request $request)
    {
        try {

            $validated = $request->validate([
                'entity_id' => 'required|integer',
                'entity_type' => 'required|string',
            ]);

            $entityId = $validated['entity_id'];
            $entityType = $validated['entity_type'];

            DB::beginTransaction();

            try {
                if ($entityType === 'quote') {
                    $quote = Quote::findOrFail($entityId);

                    // Check if already paid to prevent duplicate processing
                    if ($quote->payment_status == 1) {
                        DB::commit();
                       
                        return response()->json([
                            'success' => true,
                            'message' => 'Payment already processed',
                            'policy_number' => $quote->policy_number
                        ]);
                    }

                    $now = now()->toDateTimeString();

                    $quote->update([
                        'payment_status' => true
                    ]);

                    // Send confirmation email synchronously and update mailSent after success
                    try {
                        \Illuminate\Support\Facades\Mail::to($quote->user()->first())->send(new \App\Mail\OrderConfirmationMail($quote));
                        
                        // // Update mailSent timestamp after successful email send
                        $quote->update([
                            'mail_sent' => Carbon::now(),
                        ]);

                    } catch (\Exception $e) {
                        Log::error('Failed to send confirmation email for quote', [
                            'quote_id' => $quote->id,
                            'error' => $e->getMessage()
                        ]);
                    }

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Quote payment confirmed',
                        'policy_number' => $quote->policy_number
                    ]);

                } else if ($entityType === 'ai_document') {
                    $document = AiDocument::findOrFail($entityId);

                    // Check if already paid
                    if ($document->status === 'paid') {
                        DB::commit();
                        Log::info('AI Document already paid via previous confirmation', [
                            'doc_id' => $document->id,
                            'uuid' => $document->uuid
                        ]);
                        return response()->json([
                            'success' => true,
                            'message' => 'Payment already processed',
                            'doc_uuid' => $document->uuid
                        ]);
                    }

                    $document->update([
                        'status' => 'paid',
                    ]);

                    // Send confirmation email synchronously and update after success
                    if ($document->email && $document->prompt) {
                        try {
                            \Illuminate\Support\Facades\Mail::to($document->email)->send(new \App\Mail\AiDocumentReadyMail($document));
                            
                            Log::info('Confirmation email sent for AI document', [
                                'doc_id' => $document->id,
                                'uuid' => $document->uuid
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send confirmation email for AI document', [
                                'doc_id' => $document->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'AI Document payment confirmed',
                        'doc_uuid' => $document->uuid
                    ]);
                }

                DB::commit();
                return response()->json(['error' => 'Invalid entity type'], 400);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                DB::rollBack();
                Log::error('Entity not found for payment confirmation', [
                    'entity_id' => $entityId,
                    'entity_type' => $entityType
                ]);
                return response()->json(['error' => 'Entity not found'], 404);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment confirmation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to confirm payment'], 500);
        }
    }

    /**
     * Handle Lemon Squeezy webhook for order_created event
     * This is a fallback verification for payment completion
     */
    public function webhook(Request $request)
    {
        try {
            $rawBody = $request->getContent();
            $signature = $request->header('x-signature');

            if (!$signature) {
                Log::warning('Lemon Squeezy webhook: Missing signature');
                return response()->json(['error' => 'Missing signature'], 400);
            }

            // Get webhook secret from settings
            try {
                $webhookSecret = Setting::where('param', 'lemonsqueezy_webhook_secret')->firstOrFail()->value;
            } catch (\Exception $e) {
                Log::error('Lemon Squeezy webhook secret not configured', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Webhook secret not configured'], 500);
            }

            // Verify signature using HMAC-SHA256
            $hmac = hash_hmac('sha256', $rawBody, $webhookSecret, true);
            $computedSignature = bin2hex($hmac);

            if (!hash_equals($computedSignature, $signature)) {
                Log::warning('Lemon Squeezy webhook: Invalid signature', [
                    'expected' => $signature,
                    'computed' => $computedSignature
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            $payload = json_decode($rawBody, true);
            
            if (!isset($payload['meta']) || !isset($payload['data'])) {
                Log::warning('Lemon Squeezy webhook: Invalid payload structure');
                return response()->json(['error' => 'Invalid payload'], 400);
            }

            $meta = $payload['meta'];
            $data = $payload['data'];

            // Only process order_created events
            if ($meta['event_name'] !== 'order_created') {
                Log::info('Lemon Squeezy webhook: Event not order_created', ['event' => $meta['event_name']]);
                return response()->json(['received' => true, 'message' => 'Event type not processed']);
            }

            // Extract order details
            $userEmail = $data['attributes']['user_email'] ?? null;
            $orderId = $data['id'];
            $createdAt = new \DateTime($data['attributes']['created_at']);

            if (!$userEmail) {
                Log::warning('Lemon Squeezy webhook: No user email in payload');
                return response()->json(['error' => 'No user email provided'], 400);
            }

            // Time window for matching (24 hours to handle timezone differences between servers)
            $timeWindow = 24 * 60 * 60; // seconds
            $minTime = (clone $createdAt)->modify("-{$timeWindow} seconds");
            $maxTime = (clone $createdAt)->modify("+{$timeWindow} seconds");


            // Step 1: Try to find and update matching Quote
            // Query quotes through user relationship by email
            $matchingQuote = Quote::whereHas('user', function($query) use ($userEmail) {
                $query->where('email', $userEmail);
            })
            ->where(function($query) {
                $query->whereNull('payment_status')
                      ->orWhere('payment_status', 0);
            })
            ->whereBetween('created_at', [$minTime, $maxTime])
            ->orderBy('created_at', 'desc')
            ->first();

            if ($matchingQuote) {
                // Check if already paid to prevent duplicate processing
                if ($matchingQuote->payment_status == 1) {

                    return response()->json(['received' => true, 'message' => 'Payment already processed']);
                }

                $now = now()->toDateTimeString();

                $matchingQuote->update(['payment_status' => true]);

                // Send confirmation email synchronously and update mailSent after success
                try {
                    \Illuminate\Support\Facades\Mail::to($matchingQuote->user()->first())->send(new \App\Mail\OrderConfirmationMail($matchingQuote));
                    
                    // Update mailSent timestamp after successful email send
                    $matchingQuote->update([
                        'mail_sent' => now()->toDateTimeString(),
                    ]);

                } catch (\Exception $e) {
                    Log::error('Failed to send confirmation email for quote via webhook', [
                        'quote_id' => $matchingQuote->id,
                        'error' => $e->getMessage()
                    ]);
                }

                return response()->json([
                    'received' => true,
                    'message' => 'Quote payment processed',
                    'quote_id' => $matchingQuote->id
                ]);
            }

            // Step 2: Try to find and update matching AiDocument
            $matchingDoc = AiDocument::where('email', $userEmail)
                ->where(function($query) {
                    $query->whereNull('status')
                          ->orWhere('status', 'pending');
                })
                ->whereBetween('created_at', [$minTime, $maxTime])
                ->orderBy('created_at', 'desc')
                ->first();

            if ($matchingDoc) {
                // Check if already paid
                if ($matchingDoc->status === 'paid') {

                    return response()->json(['received' => true, 'message' => 'Payment already processed']);
                }

                $matchingDoc->update([
                    'status' => 'paid',
                    'paymentIntentId' => $orderId,
                ]);

                // Send confirmation email synchronously and update after success
                if ($matchingDoc->email && $matchingDoc->prompt) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($matchingDoc->email)->send(new \App\Mail\AiDocumentReadyMail($matchingDoc));
                        
                    } catch (\Exception $e) {
                        Log::error('Failed to send confirmation email for AI document via webhook', [
                            'doc_id' => $matchingDoc->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                return response()->json([
                    'received' => true,
                    'message' => 'AI Document payment processed',
                    'doc_id' => $matchingDoc->id
                ]);
            }



            return response()->json([
                'received' => true,
                'message' => 'No matching record found within time window'
            ]);

        } catch (\Exception $e) {
            Log::error('Lemon Squeezy webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to process webhook'], 500);
        }
    }
}
