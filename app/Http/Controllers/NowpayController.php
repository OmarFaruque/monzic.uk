<?php


namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\PromoCode;
use App\Models\Quote;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailMail;
use Illuminate\Support\Facades\RateLimiter;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;

class NowpayController extends Controller
{


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





    public function paymentInvoice(Request $request)
    {

        // Save imntent seoartedly
        $validatedData = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:quotes,id',
            ]
        );

        if (config('app.env') == "local") {
            $callBackUrl = 'https://799c-105-113-111-246.ngrok-free.app/now-ipn';
        } else {
            $callBackUrl = url('/now-ipn');
        }

        $callBackUrl = url('/now-ipn');

        $apiUrl = 'https://api.nowpayments.io/v1/invoice';



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
                'client_secret' => "",
            ]);
        }

        $setn = Setting::where("param", "now_api_key")->first();
        if ($setn == null) {
            $nowApiKey = config('services.nowpay.api_key');
        } else {
            $nowApiKey = $setn->value;
        }



        $amount = $quote->update_price;

        $setn = Setting::where("param", "nowp_per_off")->first();
        if ($setn == null) {
            $nowp_per_off = 0;
        } else {
            $nowp_per_off = floatval($setn->value);
        }
        if($nowp_per_off > 0){

            $amount = $amount - ($amount * $nowp_per_off / 100);

            $quote->update_price = $amount;
            $quote->save();

        }



        // Prepare the payload
        $payload = [
            'price_amount' => $amount,
            'price_currency' => 'gbp',
            'order_id' => $quote->id,
            'order_description' => 'Policy #' . $quote->policy_number,
            'ipn_callback_url' => $callBackUrl,
            'success_url' => url('/now-confirm-payment'),
            'cancel_url' => url('/now-cancelled-payment'),
            // 'is_fixed_rate' => true,
            // 'is_fee_paid_by_user' => false,
        ];



        // Make the HTTP request
        $response = Http::withHeaders([
            'x-api-key' => $nowApiKey, // Replace with actual API key or env variable
            'Content-Type' => 'application/json',
        ])->post($apiUrl, $payload);

        // Handle response
        if ($response->successful()) {
            $data = $response->json();
            $inv_id = $data["id"];
            $invoice_url = $data["invoice_url"];

            $quote->spayment_id = "nowp_" . $inv_id;
            $quote->save();

            return response()->json(
                [
                    "invoice_url" => $invoice_url,
                ],
                200
            );

        } else {
            // Optionally handle failure
            return response()->json(
                [
                    "invoice_url" => "Error Creating invoice. Please try again",
                ],
                500
            );

        }


    }




    public function webHook(Request $request)
    {



        // $valid = $this->handleIpn($request);
        // if (!$valid) {
        //     return response()->json(['error' => 'IPN Error'], 400);
        // }

        // $requestData = $request->all();
        $requestData = json_decode($request->getContent(), true);


        $paymentId = $requestData['payment_id'] ?? null;
        $status = $requestData['payment_status'] ?? null;
        $orderId = $requestData['order_id'] ?? null;



        $acceptPartial = false;

        if ($status == "partially_paid" || $status == "confirmed") {
            $pay_amount = $requestData['pay_amount'] ?? null;
            $actually_paid = $requestData['actually_paid'] ?? null;

            if (!is_null($pay_amount) && !is_null($actually_paid)) {
                $difference = abs($actually_paid - $pay_amount);
                $percentageDiff = ($difference / $pay_amount) * 100;

                if ($actually_paid >= $pay_amount || $percentageDiff <= 5) {
                    $acceptPartial = true;
                }
            }
        }


        // Update your order or quote status based on `$orderId` and `$status`

        $quote = Quote::find($orderId);
        if ($quote) {

            if ($status == "finished" || $acceptPartial) {


                if($quote->payment_status == 1){
                    return response()->json(['message' => 'IPN processed']);
                }


                $quote->payment_status = 1;
                $quote->save();

                Log::info('NOWPayments: Valid IPN received-----');


                if ($quote->promo_code != "") {
                    $promo = PromoCode::where("promo_code", $quote->promo_code)->first();
                    if ($promo != null) {
                        $promo->used = $promo->used + 1;
                        $promo->save();
                    }
                }

                // ADJUST TIME
                $quote = $this->adjustOrderStartTime($quote);

                //WE WILL SEND CONFIRMATION MESSAGE HERE                    
                Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));

            }

        }

        return response()->json(['message' => 'IPN processed']);
    }





    private function handleIpn(Request $request)
    {

        $setn = Setting::where("param", "now_api_key")->first();
        if ($setn == null) {
            $nowApiKey = config('services.nowpay.api_key');
        } else {
            $nowApiKey = $setn->value;
        }
        $setn = Setting::where("param", "now_ipn_secret")->first();
        if ($setn == null) {
            $ipnSecret = config('services.nowpay.ipn_secret');
        } else {
            $ipnSecret = $setn->value;
        }



        $receivedHmac = $request->header('x-nowpayments-sig');
        if (!$receivedHmac) {
            Log::warning('NOWPayments: No signature header present');
            return response()->json(['error' => 'No signature'], 400);
        }

        $rawBody = $request->getContent();
        $requestData = json_decode($rawBody, true);

        if (!$requestData) {
            Log::warning('NOWPayments: Invalid JSON');
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        $this->tksort($requestData);
        $this->normalizeFloats($requestData);
        $sortedJson = json_encode($requestData, JSON_UNESCAPED_SLASHES);

        $generatedHmac = hash_hmac('sha512', $sortedJson, trim($ipnSecret));

        if (!hash_equals($generatedHmac, $receivedHmac)) {
            Log::warning('NOWPayments IPN failed', [
                'error' => 'HMAC signature does not match',
                'received_hmac' => $receivedHmac,
                'expected_hmac' => $generatedHmac,
                'raw_body' => $rawBody,
                'sorted_data' => $requestData,
            ]);
            return false;
        }

        // All good — proceed with your logic
        Log::info('NOWPayments IPN verified');

        return true;
    }


    private function tksort(&$array)
    {
        ksort($array);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->tksort($value);
            }
        }
    }

    private function normalizeFloats(&$array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->normalizeFloats($value);
            } elseif (is_float($value)) {
                // Keep 8 decimal precision, remove trailing zeros
                $value = rtrim(rtrim(number_format($value, 8, '.', ''), '0'), '.');
            }
        }
    }



    public function confirmed(Request $request)
    {

        if (!$request->has("NP_id")) {

            abort(500, "Invalid link");

        }

        $html = "";


        $setn = Setting::where("param", "now_api_key")->first();
        if ($setn == null) {
            $nowApiKey = config('services.nowpay.api_key');
        } else {
            $nowApiKey = $setn->value;
        }




        $paymentId = $request->NP_id;


        $apiUrl = "https://api.nowpayments.io/v1/payment/{$paymentId}";


        $response = Http::withHeaders([
            'x-api-key' => $nowApiKey, // Replace or load from .env
        ])->get($apiUrl);


        $paymentStatus = "";

        $requestData = [];

        // echo $response->body().$paymentId; die();

        if ($response->successful()) {
            $data = $response->json();
            $paymentStatus = $data["payment_status"];
            $requestData = $data;


            $quote = Quote::where("id", $data["order_id"])->first();

            if ($quote == null) {
                abort(500, "Invalid link");
            }


        } else {

            $html = '<div class="text-center alert alert-info py-5 my-3 my-md-5">
                <i class="fa fa-info-circle fa-5x"></i>
                <br>
                <h3>Error getting payment details</h3>
                <p>We will send you an email  confirming this order once we confirmed your payment. Thanks!</p>
                <a href="/my-account" class="btn btn-success px-5">My Account</a>
            </div>';
            return view('confirmed', ["html" => $html]);


        }


        $paymentStatuses = [
            'waiting' => 'We are waiting for your payment. Please make the payment and check back shortly.',
            'confirming' => 'Your payment has been received and is being confirmed. This may take a few minutes.',
            'confirmed' => 'Your payment has been successfully confirmed.',
            'sending' => 'We are processing your payment. Please wait a moment.',
            'partially_paid' => 'We received less than the full amount. You should receive confirmation onces we have confirmed the payment or contact support if you need help.',
            'finished' => 'Your payment is complete. Thank you!',
            'failed' => 'Something went wrong with your payment. Please try again or contact support.',
            'refunded' => 'Your payment has been refunded.',
            'expired' => 'This payment link has expired. Please start a new payment.',
        ];



        // Just return if already done;
        if ($quote->payment_status == 1) {

            $html = '<div class="text-center alert alert-success py-5 my-3 my-md-5">
                <i class="far fa-check-circle fa-5x"></i>
                <br>
                <h3>Payment Successfully Confirmed</h3>
                <p>You will receive an email confirming this order. Thanks!</p>
                <a href="/my-account" class="btn btn-success px-5">My Account</a>
            </div>';


        } else {




            $acceptPartial = false;

            if ($paymentStatus == "partially_paid" || $paymentStatus == "confirmed") {
                $pay_amount = $requestData['pay_amount'] ?? null;
                $actually_paid = $requestData['actually_paid'] ?? null;

                if (!is_null($pay_amount) && !is_null($actually_paid)) {
                    $difference = abs($actually_paid - $pay_amount);
                    $percentageDiff = ($difference / $pay_amount) * 100;

                    if ($actually_paid >= $pay_amount || $percentageDiff <= 5) {
                        $acceptPartial = true;
                    }
                }
            }


            if ($paymentStatus == "finished" || $acceptPartial) {

                $quote->payment_status = 1;
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

                //WE WILL SEND CONFIRMATION MESSAGE HERE                    
                Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));


                $html = '<div class="text-center alert alert-success py-5 my-3 my-md-5">
                    <i class="far fa-check-circle fa-5x"></i>
                    <br>
                    <h3>Payment Successfully Confirmed</h3>
                    <p>You will receive an email confirming this order. Thanks!</p>
                    <a href="/my-account" class="btn btn-success px-5">My Account</a>
                </div>';

            } elseif (isset($paymentStatuses[$paymentStatus])) {

                $html = '<div class="text-center alert alert-info py-5 my-3 my-md-5">
                    <i class="fa fa-info-circle fa-5x"></i>
                    <br>
                    <h3>' . $paymentStatuses[$paymentStatus] . '</h3>
                    <p>We will send you an email  confirming this order once we confirmed your payment. Thanks!</p>
                    <a href="/my-account" class="btn btn-success px-5">My Account</a>
                </div>';

            } else {

                $html = '<div class="text-center alert alert-info py-5 my-3 my-md-5">
                    <i class="fa fa-info-circle fa-5x"></i>
                    <br>
                    <h3>Error getting payment details</h3>
                    <p>We will send you an email  confirming this order once we confirmed your payment. Thanks!</p>
                    <a href="/my-account" class="btn btn-success px-5">My Account</a>
                </div>';
            }

        }

        return view('confirmed', ["html" => $html]);

    }




    public function cancelled(Request $request)
    {

        $html = "";

        $html = '<div class="text-center alert alert-info py-5 my-3 my-md-5">
                    <i class="fa fa-info-circle fa-5x"></i>
                    <br>
                    <h3>Payment Cancelled</h3>
                    <p>You cancelled the payment</p>
                    <a href="/my-account" class="btn btn-success px-5">My Account</a>
                </div>';
        return view('confirmed', ["html" => $html]);

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
