<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Services\PaddleService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AiDocumentController extends Controller
{


    /**
     * Paddle success page 
     */
    public function paddlePaymentSuccess(): View
    {
        return view('payments.success'); 
    }

    public function getToken(){
  $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . env('PADDLE_API_KEY'),
    'Content-Type' => 'application/json',
])->post('https://sandbox-api.paddle.com/api/checkout/one-time/create', [
    'vendor_id' => (int) env('PADDLE_VENDOR_ID'),
    'product_id' => 'pro_01jz994q8vq32en8ej1748ayre',
    'prices' => ['USD' => '9.99'],
    'quantity' => 1,
    'customer_email' => 'customer@example.com',
    'return_url' => 'https://yourapp.com/thank-you',
]);



$checkoutToken = $response->json('checkout.token');

dd($checkoutToken);

$paymentUrl = "https://sandbox-pay.paddle.io/hsc_{$checkoutToken}";
return $paymentUrl;
    }


    /**
     * Padle webhook process
     */
    public function paddleWebhook(Request $request){
            // 1. Get and verify the Paddle webhook data
            $data = $request->all();

            // (Optional) Log for testing
            Log::info('Paddle Webhook received:', $data);

            // 2. Check event type
            if ($data['alert_name'] === 'payment_succeeded') {
                // 3. Match order or email, store payment, unlock access
                // Example:
                // User::where('email', $data['email'])->first()?->markAsPaid();
            }

            // 4. Respond 200 to Paddle
            return response('OK', 200);
    }


    /**
     * Paddle paymewnt for AI Document
     */
    public function processAIPayments(Request $request){


        try {

             $apiKey = Setting::where('param', 'paddle_apikey')->value('value');
            $existingProductId = Setting::where('param', 'paddle_ai_product_id')->value('value');
            $existingPriceId = Setting::where('param', 'paddle_ai_price_id')->value('value');


            $response = Http::withToken($apiKey)->post('https://sandbox-api.paddle.com/checkout/prices', [
                'items' => [
                    [
                        'price_id' => $existingPriceId,
                        'quantity' => 1
                    ]
                ]
            ]);

            $token = $response->json('token');
            // return response()->json(['token' => $token]);

            return response()->json([
                'product_id' => $existingProductId,
                'price_id' => $existingPriceId,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate Document throw AI
     */
    public function generateDocument(Request $request){

        $setting = Setting::where('param', 'openai_api_key')->first();
        $openai_api_key = $setting->value ?? null;

        if (empty($openai_api_key)) {
            return response()->json([
                'error' => 'OpenAI API key is missing. Please configure it first.'
            ], 400); // Bad Request
        }

        $request->validate([
            'prompt' => 'required|string|min:3'
        ]);


        try {
            $response = Http::withToken($openai_api_key)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that writes structured and formatted documents in HTML using <h1>, <h2>, <p>, <ul>, <strong>, etc.'],
                    ['role' => 'user', 'content' => $request->input('prompt')],
                ],
                'temperature' => 0.7,
                'max_tokens' => 1024,
            ]);

            // 5. Check for OpenAI errors (like invalid API key)
            if ($response->failed()) {
                return response()->json([
                    'error' => $response->json()['error']['message'] ?? 'Failed to connect to OpenAI API.',
                ], $response->status());
            }

            // 6. Success: return the OpenAI response
            return response()->json($response->json());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error while contacting OpenAI: ' . $e->getMessage()
            ], 500);
        }
    }
}
