<?php


namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\PromoCode;
use App\Models\Quote;
use App\Models\User;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


use Square\Customers\Requests\CreateCustomerRequest;
use Square\Customers\Requests\GetCustomersRequest;
use Square\SquareClient;
use Square\Types\Money;
use Square\Types\Currency;
use Square\Environments;
use Square\Payments\Requests\CreatePaymentRequest;
use Square\Exceptions\SquareApiException;






use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailMail;
use Illuminate\Support\Facades\RateLimiter;



use Illuminate\Support\Facades\Validator;

class SquareController extends Controller
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





    public function confirmPayment(Request $request)
    {

        $user = $request->user();


        // Save imntent seoartedly
        $validatedData = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:quotes,id',
                'token' => 'required|string',
            ]
        );
        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'errors' => $validatedData->errors()
            ], 400);
        }



        $paymentToken = $request->token;

        $quote = Quote::where("id", $request->id)->first();

        // Just return if already done;
        if ($quote->payment_status == 1) {

            return response()->json([
                'status' => "success",
            ]);


        }


        $amount = intval($quote->update_price * 100);


        $squareAccess = Setting::where("param", "square_access_token")->first();
        if ($squareAccess == null) {
            $squareAccessToken = config('services.square.access_token');
        } else {
            $squareAccessToken = $squareAccess->value;
        }

        $squareLoc = Setting::where("param", "square_loc_id")->first();
        if ($squareAccess == null) {
            $squareLocID = config('services.square.access_loc_id');
        } else {
            $squareLocID = $squareLoc->value;
        }
        

        if (config('app.env') == "local") {

            $square_client = new SquareClient(
                $squareAccessToken,
                null,
                ['baseUrl' => Environments::Sandbox->value,]
            );
        } else {

            $square_client = new SquareClient(
                $squareAccessToken,
                null,
                ['baseUrl' => Environments::Production->value]
            );

        }



        
        // Check if the user already has a Square customer ID
        if (! $user->square_customer_id) {
            // Create a new customer in Square
            
            $customerObj = $square_client->customers->create(
                new CreateCustomerRequest([
                    'givenName' => $user->first_name,
                    'familyName' => $user->last_name,
                    'emailAddress' => $user->email,
                ]),
            );
            $customer = $customerObj->getCustomer();
            $customerId = $customer->getId();

            $user->square_customer_id =  $customerId;
            $user->save();


        } else {
            // Use the existing customer ID
            $customerId = $user->square_customer_id;
            // Verify if the customer still exists in Square
            try {
                
                $customerObj = $square_client->customers->get(
                    new GetCustomersRequest([
                        'customerId' => $user->square_customer_id,
                    ]),
                );
                $customer = $customerObj->getCustomer(); 
        
            } catch (\Exception $e) {
                // Customer not found, recreate the customer
                $customerObj = $square_client->customers->create(
                    new CreateCustomerRequest([
                        'givenName' => $user->first_name,
                        'familyName' => $user->last_name,
                        'emailAddress' => $user->email,
                    ]),
                );
                $customer = $customerObj->getCustomer();
                $customerId = $customer->getId();

                $user->square_customer_id =  $customerId;
                $user->save();
            }
        }




        $money = new Money();
        $money->setAmount($amount);
        $money->setCurrency(Currency::Gbp->value);

        // Every payment you process with the SDK must have a unique idempotency key.
        // If you're unsure whether a particular payment succeeded, you can reattempt
        // it with the same idempotency key without worrying about double charging
        // the buyer.
        $create_payment_request = new CreatePaymentRequest(
            [
                'sourceId' => $paymentToken,
                'idempotencyKey' => $quote->policy_number,
                'amountMoney' => $money,
                'locationId' => $squareLocID,
                'customerId' => $customerId,
                'buyerEmailAddress' => $user->email,
                'buyerPhoneNumber' => $quote->phone,

            ]
        );

        try {
            $response = $square_client->payments->create($create_payment_request);
        } catch (SquareApiException $e) {

            $errors = $e->getErrors();

            $errors = $e->getErrors();
            $errorMessages = array_map(fn($err) => $err->getDetail() ?? $err->getCode(), $errors);
            $errorString = implode(', ', $errorMessages);


            return response()->json([
                'success' => false,
                // 'message' => 'Payment failed',
                'message' => $errorString,
                'status_code' => $e->getStatusCode()
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], 500);
        }

        if ($response->getPayment()) {

            $payment = $response->getPayment();
            if ($payment->getStatus() == "COMPLETED") {

                $spayment_id = $payment->getId();

                $quote->payment_status = 1;
                $quote->spayment_id = $spayment_id;
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

                return response()->json([
                    'status' => "success",
                ], 200);


            } else {

                return response()->json([
                    'status' => "Payment not yet verified",
                ], 400);
            }


        } else {

            echo json_encode($response->getErrors());
        }


    }





    public function confirmed(Request $request)
    {



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
