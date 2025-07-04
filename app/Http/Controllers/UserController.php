<?php

namespace App\Http\Controllers;

use App\Func\MyPdf;
use App\Models\Setting;
use App\Models\User;
use App\Models\Quote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{



    // List user orders page 
    public function orders(Request $request)
    {
        $user = $request->user();


        $expires_vis = Setting::where("param", "expires_vis")->first();
        if($expires_vis == null){
            $expiresVis = 0;;
        }
        else{
            $expiresVis = $expires_vis->value;
        }


        $date_time_now = date("Y-m-d H:i:s");



        DB::table('quotes')
            ->where("user_id", $request->user_id)
            ->whereRaw("CONCAT(end_date, ' ', end_time) < ?", [$date_time_now])
            ->update(['expired_state' => 'expired']);


        if($expiresVis == "1"){
            $orders = $user->orders()->where("payment_status", 1)
            ->whereNot("expired_state", "cancelled")
            ->orderBy("created_at", "DESC")
            ->get();
        }
        else{
            $orders = $user->orders()->where("payment_status", 1)
            ->whereNot("expired_state", "expired")
            ->whereNot("expired_state", "cancelled")
            ->orderBy("created_at", "DESC")
            ->get();

        }
        

        return view('orders', ["orders" => $orders]);

    }

    public function getPolicy(Request $request, $id)
    {


        $user = $request->user();

        
        $order = $user->orders()->find($id);


        return response()->json([
            'status' => true,
            'data' => $order,
        ], 200);




    }




    public function policyCertificate(Request $request, $policy_number)
    {


        $user = $request->user();

        $validator = Validator::make(["policy_number" => $policy_number], [
            'policy_number' => 'required|exists:quotes,policy_number',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $quote = Quote::where("policy_number", $policy_number)->first();

        $pdf = new MyPdf();

        return $pdf->certificate($quote);


    }






    // Edit Account
    public function editAccount(Request $request)
    {
        $user = $request->user();

        return view('edit-account');

    }




    public function updateAccount(Request $request)
    {

        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:2',
            'last_name' => 'required|string|min:2',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ",user_id",
            'current_password' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail("Current password is not correct");
                    }
                },
            ],
            'new_password' => [
                function ($attribute, $value, $fail) use ($request) {
                    // Check if `current_password` is provided
                    if ($request->filled('current_password') && empty($value)) {
                        $fail('New password is required when current password is provided.');
                    }
                    // Ensure new password is empty if `current_password` is not provided
                    if (!$request->filled('current_password') && !empty($value)) {
                        $fail('New password must be empty when current password is not provided.');
                    }
                    if ($request->filled('current_password') && !empty($value)) {
                        if (strlen($value) < 6)
                            $fail('Minimum password length is 6');
                    }

                },
            ],
            'confirm_password' => 'nullable|string|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $user->first_name = strip_tags($request->first_name);
        $user->last_name = strip_tags($request->last_name);
        $user->email = $request->email;

        if ($request->has("new_password")) {
            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        $user->save();

        //Return Success
        return response()->json([
            'status' => true,
            'message' => 'Updated successfully',
        ], 200);


    }






    public function logout(Request $request)
    {

        $user = $request->user();

        Auth::logout();
        $request->session()->regenerate();

        return redirect('/');


    }






}
