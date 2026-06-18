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
use Symfony\Component\HttpFoundation\StreamedResponse;

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
                    'output_type' => 'document',
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


    /**
     * Generate AI image (or edited image) and save securely.
     */
    public function generateImage(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Please log in first.'], 401);
        }

        $setting = Setting::where('param', 'openai_api_key')->first();
        $openaiApiKey = $setting->value ?? null;

        if (empty($openaiApiKey)) {
            return response()->json([
                'error' => 'OpenAI API key is missing. Please configure it first.',
            ], 400);
        }

        $request->validate([
            'prompt' => 'required|string|min:3',
            'images' => 'nullable|array|max:6',
            'images.*' => 'file|image|max:8192|mimes:jpg,jpeg,png,webp',
        ]);

        try {
            $prompt = $request->input('prompt');
            $hasReferenceImages = $request->hasFile('images');

            if ($hasReferenceImages) {
                $httpRequest = Http::withToken($openaiApiKey);
                foreach ($request->file('images', []) as $file) {
                    $httpRequest = $httpRequest->attach(
                        'image[]',
                        file_get_contents($file->getRealPath()),
                        $file->getClientOriginalName()
                    );
                }

                $response = $httpRequest
                    ->post('https://api.openai.com/v1/images/edits', [
                        'model' => 'gpt-image-1',
                        'prompt' => $prompt,
                        'size' => '1024x1024',
                    ]);
            } else {
                $response = Http::withToken($openaiApiKey)
                    ->post('https://api.openai.com/v1/images/generations', [
                        'model' => 'gpt-image-1',
                        'prompt' => $prompt,
                        'size' => '1024x1024',
                    ]);
            }

            if ($response->failed()) {
                return response()->json([
                    'error' => $response->json()['error']['message'] ?? 'Failed to connect to OpenAI image API.',
                ], $response->status());
            }

            $imageBase64 = $response->json('data.0.b64_json');

            if (!$imageBase64) {
                return response()->json(['error' => 'No image generated by AI.'], 500);
            }

            $uuid = Str::uuid()->toString();
            $imageBinary = base64_decode($imageBase64);
            $imagePath = 'ai-images/' . $uuid . '.png';

            Storage::disk('local')->put($imagePath, $imageBinary);

            $document = AiDocument::create([
                'uuid' => $uuid,
                'prompt' => $prompt,
                'content' => 'AI image generated',
                'email' => auth()->user()->email,
                'status' => 'pending',
                'output_type' => 'image',
                'image_path' => $imagePath,
            ]);

            Session::forget('quotation_id');
            Session::put('aidocument_id', $document->id);
            Session::forget('quotation_id');

            return response()->json([
                'uuid' => $uuid,
                'preview_url' => route('ai.image.preview', ['uuid' => $uuid]),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Server error while contacting OpenAI image API: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Download generated image securely, ensuring only the owner can access it and that payment is complete.
     */
    public function downloadImage(string $uuid): StreamedResponse
    {
        $doc = AiDocument::where('uuid', $uuid)
            ->where('output_type', 'image')
            ->firstOrFail();

        if (!auth()->check() || auth()->user()->email !== $doc->email) {
            abort(403, 'You are not allowed to access this image.');
        }

        if ($doc->status !== 'paid') {
            abort(403, 'Complete payment to download this image.');
        }

        if (!$doc->image_path || !Storage::disk('local')->exists($doc->image_path)) {
            abort(404, 'Image not found.');
        }

        return Storage::disk('local')->download($doc->image_path, 'ai-image-' . $doc->uuid . '.png');
    }


    /**
     * Preview generated image securely, ensuring only the owner can access it, without forcing download.
     */
    public function previewImage(string $uuid): StreamedResponse
    {
        $doc = AiDocument::where('uuid', $uuid)
            ->where('output_type', 'image')
            ->firstOrFail();

        if (!auth()->check() || auth()->user()->email !== $doc->email) {
            abort(403, 'You are not allowed to access this image.');
        }

        if (!$doc->image_path || !Storage::disk('local')->exists($doc->image_path)) {
            abort(404, 'Image not found.');
        }

        return Storage::disk('local')->response($doc->image_path, null, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }
}
