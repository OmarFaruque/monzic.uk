<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Quote;
use App\Models\Setting;
use App\Models\AiDocument;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\AiDocumentReadyMail;
use App\Mail\OrderConfirmationMail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AiDocumentController extends Controller
{




    /**
     * Download ai document 
     */
    public function aiDocumentDownload(): View
    {


    }

    /**
     * Paddle success page 
     */
    public function paddlePaymentSuccess(Request $request): View
    {
        $hasQuote = $request->has('q'); 
        
        return view('payments.success', compact('hasQuote')); 
    }

    public function getTokenWithCreatePriceId(Request $request){

        $type = $request->input('type', 'ai_document');
        $setn = Setting::where("param", "paddle_vendor_id")->first();
        $paddle_mode = Setting::where("param", "paddle_mode")->first();
        if ($setn == null) {
            $paddle_vendor_id = "";
        } else {
            $paddle_vendor_id = $setn->value;
        }

        
        if($paddle_mode && $paddle_mode->value == 'live'){
            $setn = Setting::where("param", "paddle_client_token_live")->first();
            $paddle_vendor_id = $setn ? $setn->value : '';
        }

        // We have to make price id, bucause for aidocument we add tip amount
        $apiKey = Setting::where('param', 'paddle_apikey')->value('value');

        $paddle_mode = Setting::where("param", "paddle_mode")->value('value');
        
            

        $priceId = null;
        if($type == 'a_document' ){
            if($paddle_mode && $paddle_mode == 'live'){
                $apiKey = Setting::where("param", "paddle_apikey_live")->value('value');
            }
            $aiPrice = Setting::where("param", "ai_document_price")->pluck('value')->first();
            if($request->has('tip')){
                $aiPrice += $request->input('tip');
            }
            $productId = Setting::where('param', 'paddle_ai_product_id')->value('value');
            $amountis = (string) ($aiPrice * 100);
        
            $apiBaseUrl = $paddle_mode !== 'live' ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';

            $priceRes = Http::withToken($apiKey)->post($apiBaseUrl . '/prices', [
                                'product_id' => $productId,
                                'unit_price' => [
                                    'amount' => $amountis,
                                    'currency_code' => 'GBP',
                                ],
                                'description' => 'One-time purchase for AI-generated PDF'
                            ]);

                            if (!$priceRes->successful()) {
                                Log::error('Paddle Price Create Error', ['response' => $priceRes->json()]);
                                return back()->with('error', 'Failed to create Paddle price.');
                            }

                            $priceId = $priceRes->json('data.id');
        }


        return response()->json([
            'token' => $paddle_vendor_id,
            'paddle_mode' => $paddle_mode,
            'price_id' => $priceId
        ]);
    }







    /**
     * Paddle paymewnt for AI Document
     */
    public function processAIPayments(Request $request){
        try {
            // $paddle_mode = Setting::where('param', 'paddle_mode')->value('value');
            // $existingProductId = $paddle_mode != 'live' ? Setting::where('param', 'paddle_ai_product_id_test')->value('value') : Setting::where('param', 'paddle_ai_product_id')->value('value');
            // $existingPriceId = Setting::where('param', 'paddle_ai_price_id')->value('value');

            // return response()->json([
            //     'product_id' => $existingProductId,
            //     'price_id' => $existingPriceId,
            //     'success_url' => route('paddle.success')
            // ]);
            return response()->json([
                'message' => 'success'
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



        if ($request->expectsJson()) {
            $request->validate([
                'prompt' => 'required|string|min:3'
            ]);
        }

        try {
            $response = Http::withToken($openai_api_key)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that writes structured and formatted documents in HTML using <h1>, <h3>, <p>, <ul>, <strong>, etc.'],
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

            $content = $response->json()['choices'][0]['message']['content'] ?? null;

            if (!$content) {
                return response()->json(['error' => 'No content generated by AI.'], 500);
            }

            $uuid = Str::uuid()->toString();
            if (auth()->check()) {
                $document = AiDocument::create([
                    'uuid'    => $uuid,
                    'prompt'  => $request->input('prompt'),
                    'content' => $content,
                    'email'   => auth()->user()->email,
                    'status'  => 'pending',
                ]);
                
                Session::forget('quotation_id');
                Session::put('aidocument_id', $document->id);
                Session::forget('quotation_id'); // Clear the other ID
            }


            // return response()->json(['content' => $content, 'uuid'=>$uuid]);
            return response()->json([
                'content' => $content,
                'uuid' => $uuid
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error while contacting OpenAI: ' . $e->getMessage()
            ], 500);
        }
    }
}
