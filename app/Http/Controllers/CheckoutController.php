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

class CheckoutController extends Controller
{


    public function bankPayment(Request $request)
    {

        // Save imntent seoartedly
        $validatedData = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:quotes,id',
            ]
        );

        $quote = Quote::find($request->id);

        // If payment already completed
        if ($quote->payment_status == 1) {
            return response()->json([
                'message' => "Error !",
            ], 500);
        }


        $amount = $quote->update_price;


        $quote->spayment_id = "bank_" . $quote->policy_number;

        
        $setn = Setting::where("param", "bank_per_off")->first();
        if ($setn == null) {
            $bank_per_off = 0;
        } else {
            $bank_per_off = $setn->value;
        }

        if($bank_per_off > 0){

            $amount =  (1 - ($bank_per_off / 100)) * $amount;
            
            $quote->update_price = $amount;

        }


        $quote->save();


        $setn = Setting::where("param", "bank_name")->first();
        if ($setn == null) {
            $bank_name = "";
        } else {
            $bank_name = $setn->value;
        }
        $setn = Setting::where("param", "bank_sort_code")->first();
        if ($setn == null) {
            $bank_sort_code = "";
        } else {
            $bank_sort_code = $setn->value;
        }
        $setn = Setting::where("param", "bank_account_number")->first();
        if ($setn == null) {
            $bank_account_number = "";
        } else {
            $bank_account_number = $setn->value;
        }
        $setn = Setting::where("param", "bank_ref_number")->first();
        if ($setn == null) {
            $bank_ref_number = "";
        } else {
            $bank_ref_number = $setn->value;
        }

        return response()->json(
            [
                "bank_name" => $bank_name,
                "bank_sort_code" => $bank_sort_code,
                "bank_account_number" => $bank_account_number,
                "bank_ref_number" => $bank_ref_number,
                "policy_number" => $quote->policy_number,
            ],
            200
        );



    }






    public function confirmed(Request $request, $policy_number)
    {


        $html = "";
        
        $quote = Quote::where("policy_number", $policy_number)->first();

        if ($quote == null) {
            abort(500, "Invalid link");
        }


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

            
                $html = '<div class="text-center alert alert-success py-5 my-3 my-md-5">
                    <i class="far fa-check-circle fa-5x"></i>
                    <br>
                    <h3>Payment Pending Confirmation</h3>
                    <p>Our team will look into your order and you will receive a confirmation Email as soon as possible</p>
                    <a href="/my-account" class="btn btn-success px-5">My Account</a>
                </div>';

            
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
