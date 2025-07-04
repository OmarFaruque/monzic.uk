<?php


namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Intent;
use App\Models\PromoCode;
use App\Models\Quote;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Customer;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;


use Illuminate\Support\Facades\Validator;






class AirWallexController extends Controller
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

        $stripeSecret = Setting::where("param", "stripe_secret")->first();
        if ($stripeSecret == null) {
            $stripeSecretKey = config('services.stripe.secret');
        } else {
            $stripeSecretKey = $stripeSecret->value;
        }
        Stripe::setApiKey($stripeSecretKey);



        //  Create the customer
        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name,
        ]);
        // Update the new customer ID
        $user->stripe_customer_id = $customer->id;
        $user->save();


        $rdata["user_name"] = $user->first_name . " " . $user->last_name;
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




    public function paymentIntent(Request $request)
    {

        // Save imntent seoartedly
        $validatedData = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:quotes,id',
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
                'client_secret' => "",
            ]);
        }




        $amount = $quote->update_price;

        $user = $quote->user;





        $accessToken = $this->getAirwallexAccessToken(); // Use bearer token from our method

        if (config('app.env') == "locald") {

            $endpoint = 'https://api-demo.airwallex.com/api/v1/pa/payment_intents/create';
        } else {

            $endpoint = 'https://api.airwallex.com/api/v1/pa/payment_intents/create';
        }



        $quote->spayment_id = 'order_' . $quote->id . '_' . time();

        $quote->save();


        $response = Http::withToken($accessToken)
            ->post($endpoint, [
                'request_id' => uniqid('req_', true),
                'amount' => $amount, // GBP  in minor units
                'currency' => 'GBP',
                'merchant_order_id' => $quote->spayment_id,
                'customer' => [
                    // 'address' => $quote->address . ', ' . $quote->postcode,
                    'email' => $user->email,
                    'first_name' => $quote->first_name,
                    'last_name' => $quote->last_name,
                    'phone_number' => $quote->countact_number,
                ],
                'descriptor' => 'Vehicle Detail Document',
                'metadata' => [
                    'order_id' => $quote->id,
                    'order_number' => $quote->order_number,
                    'full_name' => $quote->first_name. ' '.$quote->middle_name.' '.$quote->last_name,
                    'address' => $quote->address . ', ' . $quote->postcode,
                    'phone_number' => $quote->countact_number,
                    'reg' => @$quote->reg_number,
                ],
                'return_url' => url('/airwallex/confirmed'),
            ]);

        if ($response->successful()) {

            $data = $response->json();

            return response()->json([
                'success' => true,
                'intent_id' => $data["id"],
                "client_secret" => $data["client_secret"],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $response->json(),
        ], $response->status());
    }





    public function confirmed(Request $request)
    {


        $accessToken = $this->getAirwallexAccessToken(); // Use bearer token from our method


        $html = "";
        if ($request->has("id")) {


            $intentId = $request->input('id');


            if (config('app.env') == "locald") {

                $url = "https://api-demo.airwallex.com/api/v1/pa/payment_intents/{$intentId}";
            } else {

                $url = "https://api.airwallex.com/api/v1/pa/payment_intents/{$intentId}";
            }


            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->get($url);

            if ($response->successful()) {

                $paymet_status  = $response->json('status'); // <-- status field

                $order_id = $response->json('metadata.order_id');



                $quote = Quote::where("id", $order_id)->first();

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




                    if ($paymet_status === 'SUCCEEDED') {

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
                    } else {

                        $html = '<div class="text-center alert alert-info py-5 my-3 my-md-5">
                    <i class="fa fa-info-circle fa-5x"></i>
                    <br>
                    <h3>Payment not yet Confirmed</h3>
                    <p>You will send you an email  confirming this order once we confirmed your payment. Thanks!</p>
                    <a href="/my-account" class="btn btn-success px-5">My Account</a>
                </div>';
                    }
                }
            } else {


                $html = '        <div class="text-center alert alert-warning py-5 my-3 my-md-5">
            <i class="fa fa-info-circle fa-5x"></i>
            <br>
            <h3>Unknown Request</h3>
            <p>We are sorry we can\'t process your request. If you made payment, Don\'t worry, we will send you an email shortly confirming your order. Thanks</p>
            <a href="/my-account" class="btn btn-success px-5">My Account</a>
        </div>';
            }
        } else {

            $html = '        <div class="text-center alert alert-warning py-5 my-3 my-md-5">
            <i class="fa fa-info-circle fa-5x"></i>
            <br>
            <h3>Unknown Request</h3>
            <p>We are sorry we can\'t process your request. If you made payment, Don\'t worry, we will send you an email shortly confirming your order. Thanks</p>
            <a href="/my-account" class="btn btn-success px-5">My Account</a>
        </div>';
        }



        return view('confirmed', ["html" => $html]);
    }






    public function webhook(Request $request)
    {


        $stn = Setting::where("param", "airwallex_whook_secret")->first();
        if ($stn == null) {
            $aSecretKey = '';
        } else {
            $aSecretKey = $stn->value;
        }





        $signature = $request->header('x-signature');
        $timestamp = $request->header('x-timestamp');
        $secret = $aSecretKey;

        // Retrieve the raw body of the request
        $rawBody = $request->getContent();

        // Concatenate timestamp and raw body
        $dataToSign = $timestamp . $rawBody;

        // Compute HMAC SHA-256 hash
        $computedSignature = hash_hmac('sha256', $dataToSign, $secret);

        // Compare signatures
        if (!hash_equals($computedSignature, $signature)) {
            Log::warning('Airwallex webhook signature verification failed.');
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // Process the webhook payload
        $event = $request->input('name');
        $data = $request->input('data');

        // Log::info('Webhook received', [
        //     'event' => $event,
        //     'data' => $data
        // ]);

        // Handle specific event types
        if ($event === 'payment_intent.succeeded') {

            $order_id = $data['object']['metadata']['order_id'] ?? null;

            $quote = Quote::where("id", $order_id)->first();

            // Just return if already done;
            if ($quote->payment_status != 1) {

                // if ($paymet_status === 'paid') {

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

                try {
                    //WE WILL SEND CONFIRMATION MESSAGE HERE                    
                    Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));
                } catch (\Exception) {
                }
                // }
            }
        }

        return response()->json(['message' => 'Webhook processed'], 200);





        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        if (
            $event->type == 'checkout.session.completed'  || $event->type == 'checkout.session.async_payment_succeeded'
        ) {


            $session = $event->data->object;
        }

        return response()->json([
            'status' => "success",
        ], 200);
    }







    public function cancelled(Request $request)
    {

        $html = "";
        $html = '        <div class="text-center alert alert-warning py-5 my-3 my-md-5">
                <i class="fa fa-info-circle fa-5x"></i>
                <br>
                <h3>Payment Canceled</h3>
                <p>You canceled your payment request</p>
                <a href="/my-account" class="btn btn-success px-5">My Account</a>
            </div>';





        return view('confirmed', ["html" => $html]);
    }



    private function getAirwallexAccessToken()
    {
        $setting = Setting::where('param', 'airwallex_token')->first();
        $now = now();

        if ($setting) {
            $data = json_decode($setting->value, true);

            if (isset($data['token'], $data['expires_at'])) {
                // Apply 2-minute buffer
                $safeExpiry = Carbon::parse($data['expires_at'])->subMinutes(2);

                if ($safeExpiry->gt($now)) {
                    return $data['token'];
                }
            }
        }



        $stn = Setting::where("param", "airwallex_client_id")->first();
        if ($stn == null) {
            $airwallex_client_id = '';
        } else {
            $airwallex_client_id = $stn->value;
        }

        $stn = Setting::where("param", "airwallex_api_key")->first();
        if ($stn == null) {
            $airwallex_api_key = '';
        } else {
            $airwallex_api_key = $stn->value;
        }



        // Token missing or expired – fetch new one
        $clientId = $airwallex_client_id;
        $apiKey = $airwallex_api_key;


        if (config('app.env') == "locald") {

            $endpoint = 'https://api-demo.airwallex.com/api/v1/authentication/login';
        } else {

            $endpoint = 'https://api.airwallex.com/api/v1/authentication/login';
        }



        $response = Http::withHeaders([
            'x-client-id' => $clientId,
            'x-api-key'   => $apiKey,
        ])->post($endpoint);



        if (!$response->successful()) {
            throw new \Exception('Failed to fetch Airwallex access token');
        }

        $data = $response->json();
        $token = $data['token'];
        $expiresAt = $data['expires_at']; // ISO 8601

        // Save token and expiry as JSON
        Setting::updateOrCreate(
            ['param' => 'airwallex_token'],
            ['value' => json_encode([
                'token' => $token,
                'expires_at' => $expiresAt,
            ])]
        );

        return $token;
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
