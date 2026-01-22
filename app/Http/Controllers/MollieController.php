<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
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

class MollieController extends Controller
{

    public function createMolliePayment(Request $request)
    {
        $type = $request->input('type', 'quote'); // Default to 'quote' if not provided

        if ($type === 'ai') {
            $validatedData = Validator::make($request->all(), [
                'id' => 'required|exists:ai_documents,id',
                'tip' => 'nullable|numeric|min:0',
            ]);
        } else {
            $validatedData = Validator::make($request->all(), [
                'id' => 'required|exists:quotes,id'
            ]);
        }

        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'errors' => $validatedData->errors()
            ], 400);
        }

        $tipAmount = $request->input('tip', 0);

        if ($type === 'ai') {
            $document = AiDocument::findOrFail($request->id);
            if ($document->status === 'paid') {
                return response()->json(['status' => 'success']);
            }
            $amount = Setting::where('param', 'ai_document_price')->value('value') + $tipAmount;
        } else {
            $quote = Quote::where("id", $request->id)->first();
            if ($quote->payment_status == 1) {
                return response()->json(['status' => 'success']);
            }
            $amount = $quote->update_price;
        }

        $mollieApiKey = Setting::where('param', 'mollie_api_key')->value('value');

        $response = Http::withToken($mollieApiKey)->post('https://api.mollie.com/v2/payments', [
            'amount' => [
                'currency' => 'GBP',
                'value' => number_format($amount, 2, '.', ''),
            ],
            'description' => 'Payment for order #' . $request->id,
            'redirectUrl' => route('mollie.confirmed') . '?payment_id=' . $request->id,
            'cancelUrl'   => route('mollie.cancelled'),
            'webhookUrl' => route('mollie.webhook'),
            'metadata' => [
                'order_id' => $request->id,
                'type' => $type,
            ],
        ]);

        if ($response->failed()) {
            Log::error('Mollie API request failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Error creating payment',
            ], 500);
        }

        return response()->json([
            'checkout_url' => $response->json()['_links']['checkout']['href'],
        ]);
    }

    public function confirmed(Request $request)
    {
        $paymentId = $request->query('payment_id');

        if (!$paymentId) {
            return view('confirmed', [
                "html" => '<div class="text-center alert alert-danger py-5 my-3 my-md-5">
                            <i class="far fa-times-circle fa-5x"></i>
                            <br>
                            <h3>Payment Status Unknown</h3>
                            <p>No payment ID found. Please check your email or try again.</p>
                            <a href="/my-account" class="btn btn-danger px-5">My Account</a>
                        </div>'
            ]);
        }

        $mollieApiKey = Setting::where('param', 'mollie_api_key')->value('value');
        $response = Http::withToken($mollieApiKey)->get("https://api.mollie.com/v2/payments/{$paymentId}");

        if ($response->failed()) {
            return view('confirmed', [
                "html" => '<div class="text-center alert alert-danger py-5 my-3 my-md-5">
                            <i class="far fa-times-circle fa-5x"></i>
                            <br>
                            <h3>Payment Error</h3>
                            <p>We couldn’t retrieve your payment status. Please try again later.</p>
                            <a href="/my-account" class="btn btn-danger px-5">My Account</a>
                        </div>'
            ]);
        }

        $payment = $response->json();
        $status = $payment['status'];

        if ($status === 'paid') {
            $html = '<div class="text-center alert alert-success py-5 my-3 my-md-5">
                        <i class="far fa-check-circle fa-5x"></i>
                        <br>
                        <h3>Payment Successfully Confirmed</h3>
                        <p>You will receive an email confirming this order. Thanks!</p>
                        <a href="/my-account" class="btn btn-success px-5">My Account</a>
                    </div>';
        } else {
            $html = '<div class="text-center alert alert-warning py-5 my-3 my-md-5">
                        <i class="far fa-hourglass fa-5x"></i>
                        <br>
                        <h3>Payment ' . ucfirst($status) . '</h3>
                        <p>Your payment is currently ' . $status . '. Please wait for confirmation or try again.</p>
                        <a href="/my-account" class="btn btn-warning px-5">My Account</a>
                    </div>';
        }

        return view('confirmed', ["html" => $html]);
    }

    public function cancelled(Request $request)
    {
        $html = '<div class="text-center alert alert-warning py-5 my-3 my-md-5">
                <i class="fa fa-info-circle fa-5x"></i>
                <br>
                <h3>Payment Canceled</h3>
                <p>You canceled your payment request</p>
                <a href="/my-account" class="btn btn-success px-5">My Account</a>
            </div>';

        return view('confirmed', ["html" => $html]);
    }

    public function mollieWebhook(Request $request)
    {
        $mollieApiKey = Setting::where('param', 'mollie_api_key')->value('value');

        $response = Http::withToken($mollieApiKey)->get('https://api.mollie.com/v2/payments/' . $request->id);

        if ($response->failed()) {
            return response()->json(['status' => false], 400);
        }

        $payment = $response->json();

        $order_id = $payment['metadata']['order_id'];
        $type = $payment['metadata']['type'];


        if ($payment['status'] == 'paid') {
            if ($type === 'ai') {
                $aiDoc = AiDocument::findOrFail($order_id);
                if ($aiDoc->status != 'paid') {
                    // Generate PDF from content
                    $pdf = Pdf::loadView('ai-pdf-template', [
                        'content' => $aiDoc->content,
                        'title' => $aiDoc->prompt,
                    ]);

                    $pdfFileName = 'ai_doc_' . now()->timestamp . '.pdf';
                    $pdfPath = "ai-docs/{$pdfFileName}";

                    Storage::disk('public')->put($pdfPath, $pdf->output());

                    $aiDoc->status = 'paid';
                    $aiDoc->amount = $payment['amount']['value'];
                    $aiDoc->currency = $payment['amount']['currency'];
                    $aiDoc->pdf_path = $pdfPath;
                    $aiDoc->paddle_checkout_id = 'mollie_' . $payment['id'];
                    $aiDoc->save();

                    $emailTemplate = Setting::where('param', 'page[ai_email]')->value('value');

                    $placeholders = [
                        '[username]'      => $aiDoc->user->name ?? 'Customer',
                        '[document_link]' => asset('storage/' . $pdfPath),
                        '[document_title]'=> $aiDoc->prompt,
                        '[created_at]'    => $aiDoc->created_at->format('F j, Y'),
                    ];

                    $finalEmailBody = str_replace(array_keys($placeholders), array_values($placeholders), $emailTemplate);
                     try {
                        Mail::to($aiDoc->email)->send(new AiDocumentReadyMail($finalEmailBody));
                    } catch (\Exception $e) {
                        Log::error('AI Document mail failed', ['error' => $e->getMessage()]);
                    }
                }
            } else {
                $quote = Quote::where("id", $order_id)->first();
                if ($quote->payment_status != 1) {
                    $quote->payment_status = 1;
                    $quote->mail_sent = Carbon::now();
                    $quote->spayment_id = 'mollie_' . $request->id;
                    $quote->save();

                    if ($quote->promo_code != "") {
                        $promo = PromoCode::where("promo_code", $quote->promo_code)->first();
                        if ($promo != null) {
                            $promo->used = $promo->used + 1;
                            $promo->save();
                        }
                    }

                    try{
                        //WE WILL SEND CONFIRMATION MESSAGE HERE                    
                        Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));

                    }catch(\Exception){

                    }
                }
            }
        }
        return response()->json(['status' => true]);
    }




    /**
     * Molly checkout with registration
     */
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
