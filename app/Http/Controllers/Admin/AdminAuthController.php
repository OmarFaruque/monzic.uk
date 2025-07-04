<?php

namespace App\Http\Controllers\Admin;

use App\Mail\ForgotPasswordMail;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{



    public function __construct(Request $request)
    {

    }


    public function loginPage(Request $request)
    {

        return view('admin.auth.login');
    }


    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {

        $lang = $request->input('lang', 'fr');

        //Validated
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
                'remember' => 'nullable|int|min:0|max:1',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 400);
        }

        if ($request->has('remember')) {
            $remember = ($request->remember == 1) ? true : false;
        } else {
            $remember = false;
        }

        $admin = Admin::where('email', $request->email)->first();


        if ($admin != null && Hash::check($request->password, $admin->password)) {

            Auth('admin')->login($admin, $remember);

            $request->session()->regenerate();

            return response()->json([
                'status' => true,
                'message' => 'Login successfully',
            ], 200);


        }

        return response()->json([
            'status' => false,
            'message' => __('messages.login_failed'),
        ], 400);




    }



    public function forgotPasswordPage(Request $request)
    {

        return view('admin.auth.forgot_password');
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
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 400);
        }

        $admin = Admin::where('email', $request->email)->first();

        if ($admin != null) {
            $executed = RateLimiter::attempt(
                'a_send-mail' . $admin->admin_id,
                $perTwoMinutes = 5,
                function () use ($admin) {
                    Mail::to($admin)->send(new ForgotPasswordMail($admin, 'admin'));
                },
                $decayRate = 120,
            );

            if (!$executed) {

                return response()->json([
                    'status' => false,
                    'message' => "Too many messages, try again later",
                ], 400);
            }


        } else {
            return response()->json([
                'status' => false,
                'message' => "An error has occurred. Please ensure that this email address is correct",
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => "Message sent",
        ], 200);


    }


    public function resetPasswordPage(Request $request, $token){

        return view('admin.auth.reset-password', ["token" => $token]);

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
                'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W])[A-Za-z\d\s\W]{8,}$/',
                'confirm_password' => 'required|same:password',
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
                    'message' => 'Token not valid. Please request for a new email verification link',
                ]
                ,
                200
            );
        }
        $data = explode(":", $decrypted);

        $tm_before = intval($data[1]);
        if ((time() - $tm_before) > (2 * 60 * 60)) {

            return response()->json(
                [
                    'status' => false,
                    'message' => 'This verification link has expired. Kindly request for a new one',
                ]
                ,
                200
            );
        }
        $admin = Admin::where("admin_id", $data[0])->first();
        if ($admin == null) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'There is no user relating to this email address',
                ]
                ,
                200
            );
        }
        
        $executed = RateLimiter::attempt(
            'reset_'.$admin->admin_id.'_'.$tm_before,
            $perTwoHours = 1,
            function() use($admin, $request){
                $admin->password = Hash::make($request->password);
                $admin->save();
            },
            $decayRate = 7200,
        );

        if(! $executed){
            return response()->json([
                'status' => false,
                'message' => "Link has already been used",
            ], 400);
        }
        

        return response()->json([
            'status' => true,
            'message' => 'successfully',
        ], 200);
    }





}
