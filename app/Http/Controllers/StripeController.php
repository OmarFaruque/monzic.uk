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
use Exception;
use Illuminate\Support\Facades\RateLimiter;


use Illuminate\Support\Facades\Validator;

class StripeController extends Controller
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

        $stripeSecret = Setting::where("param", "stripe_secret")->first();
        if ($stripeSecret == null) {
            $stripeSecretKey = config('services.stripe.secret');
        } else {
            $stripeSecretKey = $stripeSecret->value;
        }
        Stripe::setApiKey($stripeSecretKey);



        $amount = intval($quote->update_price * 100);

        $user = $quote->user;

        $session = \Stripe\Checkout\Session::create([
            'customer' => $user->stripe_customer_id, // or 'customer_email' => $quote->user->email,
            // 'payment_method_types' => ['card'],
            'client_reference_id' => $quote->id,
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'gbp',
                    'product_data' => [
                        'name' => 'Vehicle Detail Document',
                        'description' => "A personalized digital document generated based on submitted vehicle and personal information. Delivered as a PDF file for informational or record-keeping purposes only.",
                        // 'metadata' => []
                    ],
                    'unit_amount' => $amount, // amount in pence (e.g., 1000 = £10.00)
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'order_id' => $quote->id,
            ],
            'success_url' => url("/confirmed?session_id={CHECKOUT_SESSION_ID}"),
            'cancel_url' => url('/confirmed'),
            'ui_mode' => 'hosted',
        ]);





        $rdata = [
            'url' => $session->url,
        ];

        return response()->json($rdata);
    }




    public function confirmPayment(Request $request)
    {

        // Save imntent seoartedly
        $validatedData = Validator::make(
            $request->all(),
            [
                'intent_id' => 'required|exists:payment_intents,intent_id',
            ]
        );
        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'errors' => $validatedData->errors()
            ], 400);
        }

        $quote = Quote::where("intent_id", $request->intent_id)->first();

        // Just return if already done;
        if ($quote->payment_status == 1) {

            return response()->json([
                'status' => "success",
            ]);
        }

        $stripeSecret = Setting::where("param", "stripe_secret")->first();
        if ($stripeSecret == null) {
            $stripeSecretKey = config('services.stripe.secret');
        } else {
            $stripeSecretKey = $stripeSecret->value;
        }
        Stripe::setApiKey($stripeSecretKey);


        $paymentIntent = PaymentIntent::retrieve($quote->intent_id);

        if ($paymentIntent->status === 'succeeded') {
            // Payment succeeded
            // echo "Payment verified!";


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
        } else {
        }


        // DELETE THE SESSION KEY
        // Session::remove('quotation_id');

        return response()->json([
            'status' => "success",
        ]);
    }




    public function confirmed(Request $request)
    {


        $html = "";
        if ($request->has("session_id")) {



            $stripeSecret = Setting::where("param", "stripe_secret")->first();
            if ($stripeSecret == null) {
                $stripeSecretKey = config('services.stripe.secret');
            } else {
                $stripeSecretKey = $stripeSecret->value;
            }
            Stripe::setApiKey($stripeSecretKey);

            $sessionId = $request->input('session_id'); // or $request->get('session_id')

            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            // echo json_encode($session); die();

            $paymet_status = $session["payment_status"];
            $order_id = $session["metadata"]["order_id"];




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




                if ($paymet_status === 'paid') {

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
        } elseif ($request->has("id")) {


            $quote = Quote::where("id", $request->id)->first();

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


        $stripeSecret = Setting::where("param", "stripe_secret")->first();
        if ($stripeSecret == null) {
            $stripeSecretKey = config('services.stripe.secret');
        } else {
            $stripeSecretKey = $stripeSecret->value;
        }

        $stn = Setting::where("param", "stripe_whook_secret")->first();
        if ($stn == null) {
            $stripeWHookSecret = "";
        } else {
            $stripeWHookSecret = $stn->value;
        }

        Stripe::setApiKey($stripeSecretKey);


        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        // Use the secret provided by Stripe CLI for local testing
        // or your webhook endpoint's secret.
        $endpoint_secret = $stripeWHookSecret;



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

            $paymet_status = $session["payment_status"];
            $order_id = $session["metadata"]["order_id"];

            $quote = Quote::where("id", $order_id)->first();

            // Just return if already done;
            if ($quote->payment_status != 1) {

                if ($paymet_status === 'paid') {

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

                    try{
                        //WE WILL SEND CONFIRMATION MESSAGE HERE                    
                        Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));

                    }catch(\Exception){

                    }
                }
            }
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
