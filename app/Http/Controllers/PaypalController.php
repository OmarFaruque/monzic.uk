<?php


namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Quote;
use App\Models\Setting;
use App\Models\PromoCode;
use App\Models\AiDocument;
use Illuminate\Http\Request;
use App\Mail\VerifyEmailMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\AiDocumentReadyMail;


use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;

class PaypalController extends Controller
{





    public function paypalAction(Request $request)
    {

        $type = $request->input('type', 'quote'); // Default to 'quote' if not provided

        if ($type === 'ai') {
            $validatedData = Validator::make($request->all(), [
                'id' => 'required|exists:ai_documents,id',
            ]);
        } else {
            $validatedData = Validator::make($request->all(), [
                'id' => 'required|exists:quotes,id',
            ]);
        }

        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'errors' => $validatedData->errors()
            ], 400);
        }

        if ($type === 'ai') {
            $document = AiDocument::findOrFail($request->id);
            if ($document->status === 'paid') {
                return response()->json(['status' => 'success']);
            }
            $amount = Setting::where('param', 'ai_document_price')->value('value');
        } else {
            $quote = Quote::where("id", $request->id)->first();
            if ($quote->payment_status == 1) {
                return response()->json(['status' => 'success']);
            }
            $amount = $quote->update_price;
        }


        if (config('app.env') == "local") {
         
            $paypalURL = 'https://api-m.sandbox.paypal.com/v2/';

        }
        else{

            $paypalURL = 'https://api-m.paypal.com/v2/';

        }


        $setn = Setting::where("param", "paypal_client_id")->first();
        if ($setn == null) {
            $client_id = config('services.paypal.client_id');
        } else {
            $client_id = $setn->value;
        }

        $setn = Setting::where("param", "paypal_client_secret")->first();
        if ($setn == null) {
            $client_secret = config('services.paypal.client_secret');
        } else {
            $client_secret = $setn->value;
        }

        
        if ($request->action == "create_order") {
            $body = [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "value" => $amount,
                            "currency_code" => "GBP",
                        ]
                    ]
                ],
            ];

            // Get payment details
            $response = Http::withBasicAuth($client_id, $client_secret)
                ->withBody(json_encode($body), "application/json")
                ->post($paypalURL . 'checkout/orders');

            if ($response->failed()) {
                return response()->json([
                    "status" => false,
                    "message" => "Unable to resolve this request" . $response->status(),
                ], 500);
            }

            $responseData = $response->json();

            return $responseData;

        } elseif ($request->action == 'capture_order') {
            try {
                $validator = Validator::make($request->all(), [
                    'orderID' => 'required',
                    'details' => 'required|array'
                ]);
    
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'validation error',
                        'errors' => $validator->errors()
                    ], 400);
                }
    
                $orderID = $request->orderID;
                $responseData = $request->details;

                // Log the full response for debugging
                Log::info('PayPal Capture Details:', $responseData);
    
                if (isset($responseData['status']) && strtolower($responseData['status']) == 'completed') {
    
                    if ($type === 'ai') {
                        $document = AiDocument::findOrFail($request->id);
                        $document->status = 'paid';
                        $document->paddle_checkout_id = "payp_" . $orderID;
                        $amount = $responseData['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                        $document->amount = $amount;
    
                        // Generate PDF from content
                        $pdf = Pdf::loadView('ai-pdf-template', [
                            'content' => $document->content,
                            'title' => $document->prompt,
                        ]);
    
                        $pdfFileName = 'ai_doc_' . now()->timestamp . '.pdf';
                        $pdfPath = "ai-docs/{$pdfFileName}";
    
                        Storage::disk('public')->put($pdfPath, $pdf->output());
    
                        $document->pdf_path = $pdfPath;
    
                        $document->save();
    
                        $emailTemplate = Setting::where('param', 'page[ai_email]')->value('value');
    
                        $placeholders = [
                            '[username]'      => $document->user->name ?? 'Customer',
                            '[document_link]' => asset('storage/' . $pdfPath),
                            '[document_title]' => $document->prompt,
                            '[created_at]'    => $document->created_at->format('F j, Y'),
                        ];
    
                        $finalEmailBody = str_replace(array_keys($placeholders), array_values($placeholders), $emailTemplate);
                        Mail::to($document->user->email)->send(new AiDocumentReadyMail($finalEmailBody));
                    } else {
                        $quote = Quote::where("id", $request->id)->first();
                        $quote->payment_status = 1;
                        $quote->spayment_id = "payp_" . $orderID;
                        $quote->save();
    
                        if ($quote->promo_code != "") {
                            $promo = PromoCode::where("promo_code", $quote->promo_code)->first();
                            if ($promo != null) {
                                $promo->used = $promo->used + 1;
                                $promo->save();
                            }
                        }
    
                        // ADJUST TIME
                        $quote = $this->adjustOrderStartTime($quote);
    
                        try {
                            //WE WILL SEND CONFIRMATION MESSAGE HERE                    
                            Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));
                        } catch (Exception $ex) {
                             Log::error('Order Confirmation Email failed: ' . $ex->getMessage());
                        }
                    }
                }
    
                return response()->json($responseData, 200);
            } catch (Exception $e) {
                Log::error('PayPal Capture Error: ' . $e->getMessage());
                return response()->json(['error' => 'An internal server error occurred.'], 500);
            }
        }


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
        $rdata["first_name"] = $user->first_name;
        $rdata["last_name"] = $user->last_name;

        $rdata["user_email"] = $user->email;
        $rdata["user_address"] = $user->address;
        $rdata["token"] = csrf_token();


        $executed = RateLimiter::attempt(
            'send-mail' . $user->user_id,
            $perTwoMinutes = 5,
            function () use ($user) {
                // We will send verification Email now;
                Mail::to($user)->send(new VerifyEmailMail($user));
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


}
