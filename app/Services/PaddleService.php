<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaddleService
{
    protected string $vendorId;
    protected string $apiKey;

     public function __construct()
    {
        $this->vendorId = Setting::where('param', 'paddle_vendor_id')->value('value') ?? '';
        $this->apiKey   = Setting::where('param', 'paddle_apikey')->value('value') ?? '';

        if (empty($this->vendorId) || empty($this->apiKey)) {
            throw new \Exception('Paddle credentials are missing. Please configure them in settings.');
        }
    }
    
    public function generatePayLink(float $price, string $title, ?string $email = null): string
    {
        
        $apiKey = Setting::where('param', 'paddle_apikey')->value('value');
        $existingProductId = Setting::where('param', 'paddle_ai_product_id')->value('value');
        $existingPriceId = Setting::where('param', 'paddle_ai_price_id')->value('value');

        $dynamicAmountInCents = (int) ($price * 100); 
        $customerEmail = $email; // Or get this dynamically from your form/user

        // Define your success URL
        // $successUrl = route('your.success.route'); // Example: 'https://yourwebsite.com/payment/success'
        // You can append transaction ID or other data to the success URL if needed
        // $successUrl = route('paddle.success', ['transaction_id' => '{CHECKOUT_ID}']);

        
        $ngrokBaseUrl = 'https://154e-103-16-24-141.ngrok-free.app'; // Your Ngrok URL without trailing slash

        // Define your success URL using the Ngrok URL
        // Ensure the path after the base URL is correct for your route
        $successUrl = $ngrokBaseUrl . '/paddle/payment-success?transaction_id=%7BCHECKOUT_ID%7D';

        // Paddle will replace {CHECKOUT_ID} with the actual transaction ID.
        // Or, if you use custom_data, you can retrieve it from your database on the success page.



        //  $payload = [
        //     'items' => [
        //         [
        //             'product_id' => $existingProductId,
        //             'quantity' => 1,
        //             'price' => [
        //                 'amount' => $dynamicAmountInCents,
        //                 'currency_code' => 'USD',
        //                 'description' => 'Dynamic price for ' . $title,
        //                 'type' => 'standard',
        //                 'tax_mode' => 'external',
        //             ],
        //         ],
        //     ],
        //     'collection_mode' => 'automatic',
        //     'customer' => [
        //         'email' => $customerEmail,
        //     ],
        //     'checkout' => [
        //         'return_url' => $successUrl,
        //     ],
        //     // Optional: Add some custom data to help debug
        //     // 'custom_data' => [
        //     //     'debug_timestamp' => now()->timestamp,
        //     //     'source' => 'laravel_app'
        //     // ]
        // ];

        // Log::debug('Paddle Request Payload Attempt:', $payload);



// pri_01jz8sx1rgb3ev7grsxbdd1r3k

        try {
            $transactionRes = Http::withToken($apiKey)->post('https://sandbox-api.paddle.com/transactions', [
                // 'items' => [
                //     [
                //         'product_id' => $existingProductId, // Link to your existing product
                //         'quantity' => 1, // Number of units
                //         'price' => [    // <--- DEFINE PRICE INLINE HERE
                //             'amount' => $dynamicAmountInCents,
                //             'currency_code' => 'USD',
                //             'description' => 'Dynamic price for ' . $title, // Optional, but good for clarity
                //             'type' => 'standard', // For one-time charges with a varying price
                //             'tax_mode' => 'external', // Or 'account_setting' if Paddle handles your taxes
                //         ],
                //     ],
                // ],

                // 'items' => [
                //     [
                //         'item_id' => 'txnitm_' . strtolower(Str::random(26)), // required
                //         'quantity' => 1,
                //         'price' => [
                //             'unit_price' => [
                //                 'amount' => (string) $dynamicAmountInCents,
                //                 'currency_code' => 'USD',
                //             ],
                //             'description' => 'Dynamic price for ' . $title,
                //             'product_id' => $existingProductId,
                //             'tax_mode' => 'external',
                //         ]
                //     ]
                // ],

                // 'items' => [
                //     [
                //         'quantity' => 1,
                //         'price' => [
                //             'unit_price' => [
                //                 'amount' => (string) $dynamicAmountInCents,
                //                 'currency_code' => 'USD',
                //             ],
                //             'product_id' => $existingProductId,
                //             'description' => 'AI PDF download',
                //             'tax_mode' => 'external'
                //         ]
                //     ]
                // ],
                'items' => [
                    [
                        'price_id' => $existingPriceId,
                        'quantity' => 1,
                    ]
                ],
                'collection_mode' => 'checkout', // Essential for self-serve checkouts
                'customer' => [
                    'email' => $customerEmail, // Customer's email (Paddle will create/use existing customer)
                    // 'name' => 'John Doe', // Optional
                ],
                'checkout' => [
                    // 'url' => true, // Request a hosted checkout URL
                    'return_url' => $successUrl, // <--- ADD YOUR SUCCESS URL HERE
                    // 'cancel_url' => route('your.cancel.route'), // Optional: URL to redirect if customer cancels
                ],
                // 'custom_data' => [...], // Use this to store your internal order IDs, etc.
            ]);

            if ($transactionRes->successful()) {
                $transactionData = $transactionRes->json('data');
                $checkoutUrl = $transactionData['checkout']['url'];
                $transactionId = $transactionData['id'];

                Log::info('Paddle payment link generated for existing product with dynamic price.', [
                    'transaction_id' => $transactionId,
                    'checkout_url' => $checkoutUrl,
                    'success_url_set' => $successUrl // Log the success URL you set
                ]);

                // Redirect your customer to the generated checkout URL
                // return redirect()->away($checkoutUrl);
                return $checkoutUrl;

            } else {
                Log::error('Failed to generate Paddle payment link.', ['response' => $transactionRes->json()]);
                return back()->with('error', 'Failed to generate payment link. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Exception during Paddle payment link generation: ' . $e->getMessage());
            return back()->with('error', 'An unexpected error occurred. Please try again later.');
        }
    }
}
