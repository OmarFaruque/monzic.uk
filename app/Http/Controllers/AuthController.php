<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use App\Models\Quote;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


use App\Mail\ContactMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;


use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Customer;



class AuthController extends Controller
{


    /**
     * Login The User
     * @param Request $request
     * 
     */
    public function login(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'username' => 'required|email',
                'password' => 'required'
            ]
        );


        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'errors' => $validateUser->errors()
            ], 400);
        }

        // If the user want to remember login
        if ($request->has('remember')) {
            $remember = ($request->remember == 1) ? true : false;
        } else {
            $remember = false;
        }


        try {

            if (!Auth::attempt(["email" => $request->username, "password" => $request->password], $remember)) {
                return response()->json([
                    'status' => false,
                    'message' => "Wrong login details",
                ], 400);
            }


            $user = Auth::user();

        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => "Wrong login details",
            ], 400);
        }


        if ($remember) {
            Auth::login($user, true); // Save the remember token
        } else {
            Auth::login($user);
        }

        if ($request->has('quote_id') && !empty($request->quote_id)) {
            $quote = Quote::find($request->quote_id);
            if ($quote != null) {
                $quote->user_id = $user->user_id;
                $quote->save();
            }


            $settn = Setting::where("param", "payment_gateway")->first();
            if ($settn == null) {
                $payment_gateway = "stripe";
            } else {
                $payment_gateway = $settn->value;
            }


            if ($payment_gateway != "stripe") {


            } elseif ($payment_gateway == "stripe") {

                $stripeSecret = Setting::where("param", "stripe_secret")->first();
                if ($stripeSecret == null) {
                    $stripeSecretKey = config('services.stripe.secret');
                } else {
                    $stripeSecretKey = $stripeSecret->value;
                }

                Stripe::setApiKey($stripeSecretKey);

                // Check if the user already has a Stripe customer ID
                if (!$user->stripe_customer_id) {
                    // Create a new customer in Stripe
                    $customer = Customer::create([
                        'email' => $user->email,
                        'name' => $user->first_name . ' ' . $user->last_name,
                    ]);
                    // Save the customer ID in the users table
                    $user->stripe_customer_id = $customer->id;
                    $user->save();
                } else {
                    // Use the existing customer ID
                    $customerId = $user->stripe_customer_id;
                    // Verify if the customer still exists in Stripe
                    try {
                        $customer = Customer::retrieve($customerId);
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                        // Customer not found, recreate the customer
                        $customer = Customer::create([
                            'email' => $user->email,
                            'name' => $user->first_name . ' ' . $user->last_name,
                        ]);
                        // Update the new customer ID
                        $user->stripe_customer_id = $customer->id;
                        $user->save();
                    }
                }
                

            }

        }

        // Regenerate the current sesssion 
        $request->session()->regenerate();


        if ($user->email_verified_at == null && $request->has('quote_id') && !empty($request->quote_id)) {

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
        }


        return response()->json([
            'status' => true,
            'email' => $user->email,
            'name' => $user->first_name . '  ' . $user->last_name,
            'token' => csrf_token(),
            'email_verified_at' => $user->email_verified_at,
            'message' => 'Login successfully',
        ], 200);

    }


    /**
     * Register the  User
     * @param Request $request
     * 
     */
    public function register(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|unique:users,email',
                'first_name' => 'required|string|min:2',
                'last_name' => 'required|string|min:2',
                'password' => 'required|min:6',
                // 'confirm_password' => 'required|same:password',
            ],
            [
                // 'username.regex' => 'The username may only contain letters, numbers, and underscores.',
            ]
        );


        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'errors' => $validateUser->errors()
            ], 400);
        }

        $user = new User();
        $user->first_name = strip_tags($request->first_name);
        $user->last_name = strip_tags($request->last_name);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();


        if ($request->has('quote_id') && !empty($request->quote_id)) {

            $quote = Quote::find($request->quote_id);
            if ($quote != null) {
                $quote->user_id = $user->user_id;
                $quote->save();
            }

            Auth::login($user);

            // Regenerate the current sesssion 
            $request->session()->regenerate();

            $csrf_token = csrf_token();

        }
        else{

            $executed = RateLimiter::attempt(
                'send-mail' . $user->user_id,
                $perTwoMinutes = 5,
                function () use ($user) {
                    // We will send verification Email now;
                    Mail::to($user)->send(new VerifyEmailMail($user));
                },
                $decayRate = 120,
            );
            
            $csrf_token = "";
        }

        return response()->json([
            'status' => true,
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name,
            'token' => $csrf_token,
            'message' => 'Account created successfully',
        ], 200);


    }




    public function forgotPassword(Request $request)
    {


        //Validated
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ]
        );


        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Not a valid Email',
                'errors' => $validateUser->errors()
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if ($user != null) {

            $executed = RateLimiter::attempt(
                'send-mail' . $user->user_id,
                $perTwoMinutes = 5,
                function () use ($user) {
                    Mail::to($user)->send(new ForgotPasswordMail($user, 'user'));
                },
                $decayRate = 120,
            );

            if (!$executed) {

                return response()->json([
                    'status' => false,
                    'message' => "Email sent",
                ], 400);
            }


        }

        return response()->json([
            'status' => true,
            'message' => "Message sent",
        ], 200);


    }


    public function resetPasswordPage(Request $request, $token)
    {


        return view('reset-password', ["token" => $token]);


    }



    /**
     * Handle Reset apassword.
     */
    public function resetPassword(Request $request)
    {

        //Validated
        $validateUser = Validator::make(
            $request->all(),
            [
                'token' => 'required|string',
                'password' => 'required|regex:/^[\s\S]{6,}$/',
                'confirm_password' => 'required|same:password',
            ],
            [
                'password.regex' => "Not a valid password. Password should be at least 6 characters",
            ]
        );


        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'erreur de validation',
                'errors' => $validateUser->errors()
            ], 400);
        }


        $token = $request->token;

        try {
            $decrypted = Crypt::decryptString($token);
        } catch (DecryptException $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => "Invalid token. seems broken. Please try again",
                ]
                ,
                200
            );
        }


        // return response()->json(
        //     [
        //         'status' => false,
        //         'message' => $decrypted,
        //     ],
        //     400
        // );


        $data = explode(":", $decrypted);

        $tm_before = intval($data[1]);
        if ((time() - $tm_before) > (2 * 60 * 60)) {

            return response()->json(
                [
                    'status' => false,
                    'message' => "Link expired. Please try again with another forgot password request",
                ]
                ,
                400
            );
        }
        $user = User::where("user_id", $data[0])->first();
        if ($user == null) {
            return response()->json(
                [
                    'status' => false,
                    'message' => "User not found. Please try again",
                ]
                ,
                400
            );
        }

        $executed = RateLimiter::attempt(
            'reset_' . $user->user_id . '_' . $tm_before,
            $perTwoHours = 1,
            function () use ($user, $request) {
                $user->password = Hash::make($request->password);
                $user->save();
            },
            $decayRate = 7200,
        );

        if (!$executed) {

            return response()->json([
                'status' => false,
                'message' => "Link seems to have been used",
            ], 400);
        }


        return response()->json([
            'status' => true,
            'message' => 'successfully',
        ], 200);
    }




    public function contactUs(Request $request)
    {



        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3|max:100',
            'last_name' => 'required|string|min:3|max:100',
            'email' => 'required|email',
            'telephone' => 'nullable|string',
            'subject' => 'required|string|min:5',
            'comment' => 'required|string|min:10',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $contact_data = config('mail.contact');

        Mail::to($contact_data["address"])->send(new ContactMail($request->all()));

        // Mail::to($contact_data["address"])->queue(new ContactMail($request->all()));

        return response()->json([
            'status' => true,
            'message' => 'Message Sent',
        ], 200);

    }



    public function sendClaims(Request $request)
    {



        $validator = Validator::make($request->all(), [
            'policy_number' => 'required|exists:quotes,policy_number',
            'first_name' => 'required|string|min:3|max:100',
            'last_name' => 'required|string|min:3|max:100',
            'email' => 'required|email',
            'telephone' => 'required|string',
            'comment' => 'required|string|min:10',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $contact_data = config('mail.contact');

        Mail::to($contact_data["address"])->send(new ContactMail($request->all(), 'claim'));

        // Mail::to($contact_data["address"])->queue(new ContactMail($request->all()));

        return response()->json([
            'status' => true,
            'message' => 'Message Sent',
        ], 200);

    }




    public function verifyEmail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required|digits:6',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $code = session('verification_code');
        $createdAt = session('created_at');
        $email = session('verification_email');
        $isWithinTwoHours = false;
        if ($createdAt) {
            $isWithinTwoHours = \Carbon\Carbon::parse($createdAt)->addHours(2)->isFuture();
        }
        if (!$isWithinTwoHours) {
            session()->forget(['verification_code', 'verification_email', 'created_at']);
            return response()->json([
                'status' => false,
                'message' => 'Verification code has expired or invalid',
            ], 400);
        }



        if ($code != $request->code) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid verification code.',
            ], 400);
        }

        $user = User::where("email", $email)->first();
        if ($user == null) {
            return response()->json([
                'status' => false,
                'message' => 'Error! Please try resend code',
            ], 400);
        }

        $user->email_verified_at = date("Y-m-d H:i:s");
        $user->save();

        session()->forget(['verification_code', 'verification_email', 'created_at']);


        // Get Stripe secret key from DB or fallback to config
        $stripeSecret = Setting::where("param", "stripe_secret")->first();
        $stripeSecretKey = $stripeSecret ? $stripeSecret->value : config('services.stripe.secret');

        Stripe::setApiKey($stripeSecretKey);

        try {
            if ($user->stripe_customer_id) {
                // Try to retrieve the customer
                $customer = Customer::retrieve($user->stripe_customer_id);

                // Update the customer info
                $customer->email = $user->email;
                $customer->name = $user->first_name . ' ' . $user->last_name;
                $customer->save();
            } else {
                // Create a new customer
                $customer = Customer::create([
                    'email' => $user->email,
                    'name' => $user->first_name . ' ' . $user->last_name,
                ]);

                // Save new customer ID to user
                $user->stripe_customer_id = $customer->id;
                $user->save();
            }
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            // Log::error("Stripe Customer Error: " . $e->getMessage());
            // Optionally, you could rethrow or return a response
        }


        return response()->json([
            'status' => true,
            'message' => 'Email verified',
        ], 200);

    }



    public function resendVerificationCode(Request $request)
    {

        if (Auth::check()) {
            $user = $request->user();
        } else {
            $email = session('verification_email');
            $user = User::where("email", $email)->first();
        }


        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email,' . $user->user_id . ',user_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        

        if ($user->email_verified_at != null) {
            return response()->json([
                'status' => false,
                'message' => 'Email has already been verified',
            ], 400);
        }

        if ($request->email != null) {
            $user->email = $request->email;
        }
        $user->save();

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



        return response()->json([
            'status' => true,
            'message' => 'message sent',
        ], 200);


    }


}

