<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
use App\Func\MyPdf;
use App\Models\Quote;
use App\Models\Setting;
use App\Models\BlackList;
use App\Models\PromoCode;
use Illuminate\View\View;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\OrderExpiresMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{


    /**
     * Show Ai document page 
     */
    public function aiDocumentShow(): View
    {

        $setn = Setting::where("param", "ai_document_price")->first();
        if ($setn == null) {
            $ai_document_price = "";
        } else {
            $ai_document_price = $setn->value;
        }

        return view('aidocument', compact('ai_document_price'));

    }

    // Show Home Page  page. 
    public function index(Request $request)
    {

        $settn = Setting::where("param", "payment_gateway")->first();
        if ($settn == null) {
            $payment_gateway = "stripe";
        } else {
            $payment_gateway = $settn->value;
        }

        $setn = Setting::where("param", "checkout_notice")->first();
        if ($setn == null) {
            $checkout_notice = "";
        } else {
            $checkout_notice = $setn->value;
        }
        $setn = Setting::where("param", "show_checkout_notice")->first();
        if ($setn == null) {
            $show_checkout_notice = "yes";
        } else {
            $show_checkout_notice = $setn->value;
        }
        $setn = Setting::where("param", "home_notice")->first();
        if ($setn == null) {
            $home_notice = "";
        } else {
            $home_notice = $setn->value;
        }
        $setn = Setting::where("param", "show_home_notice")->first();
        if ($setn == null) {
            $show_home_notice = "yes";
        } else {
            $show_home_notice = $setn->value;
        }
        $setn = Setting::where("param", "choosen_page_notice")->first();
        if ($setn == null) {
            $choosen_page_notice = "yes";
        } else {
            $choosen_page_notice = $setn->value;
        }

        return view('index', ["checkout_notice" => $checkout_notice, "show_checkout_notice" => $show_checkout_notice, "home_notice" => $home_notice, "show_home_notice" => $show_home_notice, "choosen_page_notice" => $choosen_page_notice,]);
    }




    // Show About Page  page. 
    // public function aboutUs(Request $request)
    // {

    //     return view('about-us');
    // }

    // Show About Page  page. 
    public function contact(Request $request)
    {

        return view('contact');
    }


    // Show Privacy Policy  page. 
    public function privacyPolicy(Request $request)
    {

        $settingP = Setting::where("param", 'page[privacy_policy]')->first();
        if ($settingP != null) {
            $pageContent = $settingP->value;
        } else {
            $pageContent = "No content ";
        }

        return view('privacy-policy', ["pageContent" => $pageContent]);
    }

    // Show Terms of Buisness  page. 
    public function customerTermsOfBusiness(Request $request)
    {
        $settingP = Setting::where("param", 'page[term_business]')->first();
        if ($settingP != null) {
            $pageContent = $settingP->value;
        } else {
            $pageContent = "No content ";
        }

        return view('terms-of-business', ["pageContent" => $pageContent]);
    }

    // Show Cookies   page. 
    public function cookies(Request $request)
    {
        $settingP = Setting::where("param", 'page[cookies_page]')->first();
        if ($settingP != null) {
            $pageContent = $settingP->value;
        } else {
            $pageContent = "No content ";
        }

        return view('cookies', ["pageContent" => $pageContent]);
    }


    // Show Terms of Use  page. 
    public function websiteTermsOfUse(Request $request)
    {

        $settingP = Setting::where("param", 'page[term_use]')->first();
        if ($settingP != null) {
            $pageContent = $settingP->value;
        } else {
            $pageContent = "No content ";
        }


        return view('terms-of-use', ["pageContent" => $pageContent]);
    }

    // Order  page. 
    public function viewPolicyPage(Request $request, $policy_number)
    {

        $order = Quote::orderBy("id", "DESC")->first();

        $pdf = new MyPdf();

        // return $pdf->statementFact($order);


        return view('view-order', ["policy_number" => $policy_number]);
    }

    public function viewPolicy(Request $request)
    {


        // $order = Quote::orderBy("id", "DESC")->first();
        // $order->load('user');
        // return response()->json([
        //     'status' => true,
        //     'data' => $order,
        // ], 200);


        $user = $request->user();

        $validator = Validator::make($request->input(), [
            'policy_number' => 'required|exists:quotes,policy_number',
            'last_name' => 'required|string',
            'postcode' => 'required|string',
            'date_of_birth' => 'required|date_format:d-m-Y',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        // Parse start and end dates and times
        $bthDate = $request->date_of_birth; // Format: DD/MM/YYYY
        list($bDay, $bMonth, $bYear) = explode('-', $bthDate);


        $date_of_birth = date("Y-m-d", strtotime("$bYear-$bMonth-$bDay"));



        $postcode = str_replace(' ', '', $request->postcode);

        $order = Quote::where("policy_number", $request->policy_number)
            ->where("last_name", $request->last_name)
            ->where("date_of_birth", $date_of_birth)
            ->where(function ($query) use ($postcode) {
                $query->where(DB::raw("REPLACE(postcode, ' ', '')"), $postcode)
                    ->orWhereNull("postcode");
            })
            ->first();


        if ($order != null) {
            $order->load(['user:user_id,email,first_name']);
        }

        return response()->json([
            'status' => true,
            'data' => $order,
        ], 200);
    }


    public function policyCertificate(Request $request, $type)
    {

        // $user = $request->user();


        $validator = Validator::make($request->input(), [
            'policy_number' => 'required|exists:quotes,policy_number',
            'last_name' => 'required|string',
            'postcode' => 'required|string',
            'date_of_birth' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        // Parse start and end dates and times
        $bthDate = $request->date_of_birth; // Format: DD/MM/YYYY
        list($bDay, $bMonth, $bYear) = explode('-', $bthDate);


        $date_of_birth = date("Y-m-d", strtotime("$bYear-$bMonth-$bDay"));



        $postcode = str_replace(' ', '', $request->postcode);

        $quote = Quote::where("policy_number", $request->policy_number)
            ->where("last_name", $request->last_name)
            ->where("date_of_birth", $date_of_birth)
            ->where(function ($query) use ($postcode) {
                $query->where(DB::raw("REPLACE(postcode, ' ', '')"), $postcode)
                    ->orWhereNull("postcode");
            })
            ->first();




        $pdf = new MyPdf();

        $settingPGX = Setting::where("param", "LIKE", 'pags[doc_%')->get();
        $pags = [];

        foreach ($settingPGX as $setting) {
            $param = str_replace(["pags[", "]"], "", $setting->param);
            $pags[$param] = $setting->value;
        }



        if ($type == Str::slug($pags['doc_certificate']) && $pags['doc_certificate_en'] == 1) {
            return $pdf->certificate($quote);
        } elseif ($type == Str::slug($pags['doc_statement']) && $pags['doc_statement_en'] == 1) {
            return $pdf->statementFact($quote);
        } elseif ($type == Str::slug($pags['doc_schedule']) && $pags['doc_schedule_en'] == 1) {
            return $pdf->newPolicySchedule($quote);
        } elseif ($type == Str::slug($pags['doc_information']) && $pags['doc_information_en'] == 1) {

            if (strpos(strtolower(config('app.url')), 'starling') !== false) {

                $filePath = public_path('.pdf/starling.pdf');
            } elseif (strpos(strtolower(config('app.url')), 'monzit') !== false) {

                $filePath = public_path('.pdf/monzit.pdf');
            } else {
                $filePath = public_path('.pdf/monzit.pdf');
            }



            if (!file_exists($filePath)) {
                abort(404, 'File not found');
            }

            return response()->file($filePath);
        }
    }




    // Show Policy Page. 
    public function policyGetQuote(Request $request)
    {

        // $id = Session::get("quotation_id");

        // $quote = null;
        // if (!empty($id)) {
        //     $quote = Quote::find($id);
        // }
        // if ($quote != null) {

        //     return redirect('/checkout');
        // }



        $quote_js_func = Setting::where("param", "quote_js_func")->first();
        $quoteJsFunc = $quote_js_func->value;

        return view('quote', ["quoteJsFunc" => $quoteJsFunc]);
    }




    // Checkout page. 
    public function checkout(Request $request)
    {

        $id = Session::get("quotation_id");

        if (config('app.env') == "local") {
            $id = 1;
        }

        if ($request->has("miiti")) {
            $id = 27;
        }

        $quote = null;
        if (!empty($id)) {
            $quote = Quote::find($id);
        }
        if ($quote == null) {

            return redirect('/order/get-quote');
        }



        if ($request->has("miiti")) {
            $id = 19;

            $secretKey = 'B#-a#se354912_xz';
            $policyId = $quote->id;
            $hash = hash_hmac('sha256', $policyId, $secretKey);

            // Redirect with user_id and hash
            $url = url("/pmt?policy_id={$policyId}&hash={$hash}");

            return redirect($url);
        }


        // If payment already completed
        if ($quote->payment_status == 1) {

            return redirect("/my-account");
        }

        Session::remove('quotation_id');


        $backdated_time = Setting::where("param", "backdated_time")->first();
        if ($backdated_time == null) {
            $backdatedTime = 0;;
        } else {
            $backdatedTime = $backdated_time->value;
        }




        $settn = Setting::where("param", "payment_gateway")->first();
        if ($settn == null) {
            $payment_gateway = "stripe";
        } else {
            $payment_gateway = $settn->value;
        }

        $setn = Setting::where("param", "checkout_notice")->first();
        if ($setn == null) {
            $checkout_notice = "";
        } else {
            $checkout_notice = $setn->value;
        }
        $setn = Setting::where("param", "show_checkout_notice")->first();
        if ($setn == null) {
            $show_checkout_notice = "yes";
        } else {
            $show_checkout_notice = $setn->value;
        }
        $setn = Setting::where("param", "home_notice")->first();
        if ($setn == null) {
            $home_notice = "";
        } else {
            $home_notice = $setn->value;
        }
        $setn = Setting::where("param", "show_home_notice")->first();
        if ($setn == null) {
            $show_home_notice = "yes";
        } else {
            $show_home_notice = $setn->value;
        }
        $setn = Setting::where("param", "choosen_page_notice")->first();
        if ($setn == null) {
            $choosen_page_notice = "yes";
        } else {
            $choosen_page_notice = $setn->value;
        }



        $setn = Setting::where("param", "show_bank")->first();
        if ($setn == null) {
            $show_bank = 0;
        } else {
            $show_bank = intval($setn->value);
        }

        $setn = Setting::where("param", "bank_infor_text")->first();
        if ($setn == null) {
            $bank_infor_text = "Please put the reference exactly as shown, otherwise the payment may be reversed. ";
        } else {
            $bank_infor_text = $setn->value;
        }


        $setn = Setting::where("param", "bank_per_off")->first();
        if ($setn == null) {
            $bank_per_off = 0;
        } else {
            $bank_per_off = $setn->value;
        }

        $setn = Setting::where("param", "checkout_checkbox")->first();
        if ($setn == null) {
            $checkout_checkbox = "";
        } else {
            $checkout_checkbox = $setn->value;
        }


        if (Auth::check() && Auth::user()->email == 'sabo7rr@gmail.com') {


            $stripePublic = Setting::where("param", "stripe_public")->first();
            if ($stripePublic == null) {
                $stripePublicKey = config('services.stripe.public');
            } else {
                $stripePublicKey = $stripePublic->value;
            }

            return view('checkout_airwallex', ["quote" => $quote, "backdatedTime" => $backdatedTime, "stripePublicKey" => $stripePublicKey,  "checkout_notice" => $checkout_notice, "show_checkout_notice" => $show_checkout_notice, "home_notice" => $home_notice, "show_home_notice" => $show_home_notice, "choosen_page_notice" => $choosen_page_notice, "show_bank" => $show_bank, "bank_infor_text" => $bank_infor_text, 'checkout_checkbox' => $checkout_checkbox, 'bank_per_off' => $bank_per_off,]);
            
        }



        if ($payment_gateway == "wordpress") {

            $secretKey = 'B#-a#se354912_xz';
            $policyId = $quote->id;
            $hash = hash_hmac('sha256', $policyId, $secretKey);

            // Redirect with user_id and hash
            $url = url("/pmt?policy_id={$policyId}&hash={$hash}");

            return redirect($url);
        } elseif ($payment_gateway == "squareup") {


            $settn = Setting::where("param", "square_pmethods")->first();
            if ($settn == null) {
                $squarePMethods = [];
            } else {
                try {
                    $squarePMethods = json_decode($settn->value, true);
                } catch (\Exception) {
                    $squarePMethods = [];
                }
            }


            $squareApp = Setting::where("param", "square_app_id")->first();
            if ($squareApp == null) {
                $squareAppID = config('services.squre.app_id');
            } else {
                $squareAppID = $squareApp->value;
            }

            $squareLoc = Setting::where("param", "square_loc_id")->first();
            if ($squareLoc == null) {
                $squareLocID = config('services.squre.loc_id');
            } else {
                $squareLocID = $squareLoc->value;
            }

            return view('checkout_sqr', ["quote" => $quote, "backdatedTime" => $backdatedTime, "squareAppID" => $squareAppID, "squareLocID" => $squareLocID, "squarePMethods" => $squarePMethods, "checkout_notice" => $checkout_notice, "show_checkout_notice" => $show_checkout_notice, "home_notice" => $home_notice, "show_home_notice" => $show_home_notice, "choosen_page_notice" => $choosen_page_notice, "show_bank" => $show_bank, "bank_infor_text" => $bank_infor_text, 'checkout_checkbox' => $checkout_checkbox, 'bank_per_off' => $bank_per_off,]);
        } elseif ($payment_gateway == "paypal") {

            $setn = Setting::where("param", "paypal_client_id")->first();
            if ($setn == null) {
                $paypalPublic = config('services.paypal.client_id');
            } else {
                $paypalPublic = $setn->value;
            }

            return view('checkout_payp', ["quote" => $quote, "backdatedTime" => $backdatedTime, "paypalPublic" => $paypalPublic, "checkout_notice" => $checkout_notice, "show_checkout_notice" => $show_checkout_notice, "home_notice" => $home_notice, "show_home_notice" => $show_home_notice, "choosen_page_notice" => $choosen_page_notice, "show_bank" => $show_bank, "bank_infor_text" => $bank_infor_text, 'checkout_checkbox' => $checkout_checkbox, 'bank_per_off' => $bank_per_off,]);
        } elseif ($payment_gateway == "nowpay") {


            $setn = Setting::where("param", "nowp_per_off")->first();
            if ($setn == null) {
                $nowp_per_off = 0;
            } else {
                $nowp_per_off = floatval($setn->value);
            }


            return view('checkout_nowp', ["quote" => $quote, "backdatedTime" => $backdatedTime,  "checkout_notice" => $checkout_notice, "show_checkout_notice" => $show_checkout_notice, "home_notice" => $home_notice, "show_home_notice" => $show_home_notice, "choosen_page_notice" => $choosen_page_notice, "nowp_per_off" => $nowp_per_off, "show_bank" => $show_bank, "bank_infor_text" => $bank_infor_text, 'checkout_checkbox' => $checkout_checkbox, 'bank_per_off' => $bank_per_off,]);
        } elseif ($payment_gateway == "airwallex") {


            $stripePublic = Setting::where("param", "stripe_public")->first();
            if ($stripePublic == null) {
                $stripePublicKey = config('services.stripe.public');
            } else {
                $stripePublicKey = $stripePublic->value;
            }

            return view('checkout_airwallex', ["quote" => $quote, "backdatedTime" => $backdatedTime, "stripePublicKey" => $stripePublicKey,  "checkout_notice" => $checkout_notice, "show_checkout_notice" => $show_checkout_notice, "home_notice" => $home_notice, "show_home_notice" => $show_home_notice, "choosen_page_notice" => $choosen_page_notice, "show_bank" => $show_bank, "bank_infor_text" => $bank_infor_text, 'checkout_checkbox' => $checkout_checkbox, 'bank_per_off' => $bank_per_off,]);
        } else {

            $stripePublic = Setting::where("param", "stripe_public")->first();
            if ($stripePublic == null) {
                $stripePublicKey = config('services.stripe.public');
            } else {
                $stripePublicKey = $stripePublic->value;
            }

            return view('checkout', ["quote" => $quote, "backdatedTime" => $backdatedTime, "stripePublicKey" => $stripePublicKey,  "checkout_notice" => $checkout_notice, "show_checkout_notice" => $show_checkout_notice, "home_notice" => $home_notice, "show_home_notice" => $show_home_notice, "choosen_page_notice" => $choosen_page_notice, "show_bank" => $show_bank, "bank_infor_text" => $bank_infor_text, 'checkout_checkbox' => $checkout_checkbox, 'bank_per_off' => $bank_per_off,]);
        }
    }


    // GET PROMO CODE. 
    public function getPromoCode(Request $request)
    {

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

        $code_error = "";
        $new_amount = $quote->cpw;

        $promo = PromoCode::where("promo_code", $request->promo_code)->first();
        if ($promo == null) {
            $code_error = "Invalid promo code";
        } elseif ($promo->used >= $promo->available) {
            $code_error = "Promo code quota used";
        } elseif ($promo->min_spent != 0 && $promo->min_spent > $quote->cpw) {
            $code_error = "You do not qualify for this promo code";
        } else if (time() > strtotime($promo->expires)) {
            $code_error = "Promo code expired";
        } else {
            // Do the code matching
            $matches = json_decode($promo->matches, true);
            if (isset($matches["birth_date"])) {
                $bdata = explode("-", $matches["birth_date"]);

                $qdata = explode("-", $quote->date_of_birth);

                if ($bdata[0] != $qdata[0]) { // Match year
                    $code_error = "You do not qualify for this promo code";
                } else if (isset($bdata[1]) && intval($bdata[1]) != intval($qdata[1])) { // Match month
                    $code_error = "You do not qualify for this promo code";
                } else if (isset($bdata[2]) && intval($bdata[2]) != intval($qdata[2])) { // Match month
                    $code_error = "You do not qualify for this promo code";
                }
            }
            if (isset($matches["last_name"])) {
                if (strtolower($matches["last_name"]) != strtolower($quote->last_name)) { // Match month
                    $code_error = "You do not qualify for this promo code";
                }
            }
            if (isset($matches["registrations"])) {

                $registrations = explode(",", $matches["registrations"]);
                $regData = [];
                foreach ($registrations as $reg) {
                    $reg = trim(strtolower($reg));
                    if (!empty($reg)) {
                        $regData[] = $reg;
                    }
                }
                if (count($regData) > 0 && !in_array(strtolower($quote->reg_number), $regData)) {
                    $code_error = "You do not qualify for this promo code";
                }
            }
        }


        $amount_before = $quote->cpw;

        if ($code_error != "") {

            $quote->update_price = $quote->cpw;
            $quote->promo_code = "";
            $quote->save();

            if ($amount_before != $quote->update_price) {
                $this->updatePaymentIntent($quote->intent_id, $quote->update_price);
            }

            return response()->json([
                'status' => false,
                'change' => ($amount_before != $quote->update_price) ? 1 : 0,
                'message' => "Promo code not valid",
                'errors' => ["promo_code" => [$code_error]],
            ], 400);
        }


        $discount_value = $promo->amount;

        if ($promo->discount_type == "%") {
            $discount_amount = ($discount_value / 100) * $quote->cpw;
        } else {
            $discount_amount = $discount_value;
        }

        $new_amount = $quote->cpw - $discount_amount;

        // Remanining amount must be significant
        if ($new_amount > 1.5) {
            $quote->update_price = $new_amount;
            $quote->promo_code = $request->promo_code;
            $quote->save();

            if ($amount_before != $quote->update_price) {
                $this->updatePaymentIntent($quote->intent_id, $quote->update_price);
            }
        } else {

            $quote->update_price = $quote->cpw;
            $quote->promo_code = "";
            $new_amount = $quote->cpw;
            $quote->save();

            if ($amount_before != $quote->update_price) {
                $this->updatePaymentIntent($quote->intent_id, $quote->update_price);
            }

            return response()->json([
                'status' => false,
                'change' => ($amount_before != $quote->update_price) ? 1 : 0,
                'message' => "You do not qualify for this promo coded",
                'errors' => ["promo_code" => ["You do not qualify for this promo code"]],
            ], 400);
        }


        return response()->json([
            'status' => true,
            'change' => ($amount_before != $quote->update_price) ? 1 : 0,
            'amount' => $new_amount,
        ], 200);
    }

    private function updatePaymentIntent($paymentIntentId, $amount)
    {

        $method = Setting::where("param", "payment_gateway")->first();
        if ($method == null) {
            $payment_method = 'stripe';
        } else {
            $payment_method = $method->value;
        }

        if ($payment_method != "stripe") {
            return;
        }
    }


    private function isBlackListed($quote)
    {

        $blacklists = BlackList::get();

        $bStatus = false;

        foreach ($blacklists as $blacklist) {

            $hasFail = 0;
            $hasSuccess = 0;

            // Do the code matching
            $matches = json_decode($blacklist->matches, true);


            if (isset($matches["birth_date"]) && isset($matches["last_name"]) && isset($matches["first_name"])) {


                // Parse start and end dates and times
                $bthDate = $quote->date_of_birth; // Format: DD/MM/YYYY
                list($bDay, $bMonth, $bYear) = explode('-', $bthDate);
                $date_of_birth = date("Y-m-d", strtotime("$bYear-$bMonth-$bDay"));


                $bdata = explode("-", $matches["birth_date"]);

                $qdata = explode("-", $date_of_birth);

                if ($bdata[0] != $qdata[0]) { // Match year
                    $hasFail++;
                } else if (isset($bdata[1]) && intval($bdata[1]) != intval($qdata[1])) { // Match month
                    $hasFail++;
                } else if (isset($bdata[2]) && intval($bdata[2]) != intval($qdata[2])) { // Match month
                    $hasFail++;
                } else {
                    $hasSuccess++;
                }

                if (strtolower($matches["last_name"]) == strtolower($quote->last_name)) { // Match month
                    $hasSuccess++;
                }
                if (strtolower($matches["first_name"]) == strtolower($quote->first_name)) { // Match month
                    $hasSuccess++;
                }
                if ($hasSuccess >= 3) {
                    $bStatus =  true;
                    break;
                }
            }


            if (isset($matches["birth_date"]) && isset($matches["last_name"])) {


                // Parse start and end dates and times
                $bthDate = $quote->date_of_birth; // Format: DD/MM/YYYY
                list($bDay, $bMonth, $bYear) = explode('-', $bthDate);
                $date_of_birth = date("Y-m-d", strtotime("$bYear-$bMonth-$bDay"));


                $bdata = explode("-", $matches["birth_date"]);

                $qdata = explode("-", $date_of_birth);

                if ($bdata[0] != $qdata[0]) { // Match year
                    $hasFail++;
                } else if (isset($bdata[1]) && intval($bdata[1]) != intval($qdata[1])) { // Match month
                    $hasFail++;
                } else if (isset($bdata[2]) && intval($bdata[2]) != intval($qdata[2])) { // Match month
                    $hasFail++;
                } else {
                    $hasSuccess++;
                }

                if (strtolower($matches["last_name"]) == strtolower($quote->last_name)) { // Match month
                    $hasSuccess++;
                }

                if ($hasSuccess >= 2) {
                    $bStatus =  true;
                    break;
                }
            }


            if (isset($matches["email"])) {
                if (strtolower($matches["email"]) == strtolower($quote->email)) { // Match month
                    $bStatus =  true;
                    break;
                }
            }

            if (isset($matches["registrations"])) {

                $registrations = explode(",", $matches["registrations"]);
                $regData = [];
                foreach ($registrations as $reg) {
                    $reg = trim(strtolower($reg));
                    if (!empty($reg)) {
                        $regData[] = $reg;
                    }
                }
                if (count($regData) > 0 && in_array(strtolower($quote->reg_number), $regData)) {
                    $bStatus =  true;
                    break;
                }
            }
        }



        return $bStatus;
    }








    // Show My account  Use  page. 
    public function myAccount(Request $request)
    {


        if (auth('web')->check()) {

            return view('my-account');
        } else {

            // Login page
            return view('login-reg');
        }
    }






    // SProcess Quote. 
    public function processQuote(Request $request)
    {


        $validatedData = Validator::make(
            $request->all(),
            [
                // 'cpw' => 'required|numeric',
                'update-price' => 'nullable|string',
                'reg_number' => 'required|string|max:50',
                'vehicle_make' => 'required|string|max:100',
                'vehicle_model' => 'required|string|max:100',
                'engine_cc' => 'nullable',
                'start_date' => 'required|date_format:Y-m-d',
                'start_time' => 'required|date_format:H:i',
                'end_date' => 'required|date_format:Y-m-d',
                'end_time' => 'required|date_format:H:i',
                'date_of_birth' => 'required|date_format:d-m-Y',
                'title' => 'required|string',
                'first_name' => 'required|string|max:100',
                'middle_name' => 'nullable|string|max:100',
                'last_name' => 'required|string|max:100',
                'postcode' => 'required|string',
                'address' => 'required|string',
                'town' => 'nullable|string',
                'occupation' => 'required|string',
                'contact_number' => 'required|string',
                'cover_reason' => 'required|string',
                'licence_type' => 'required|string|max:100',
                'licence_held_duration' => 'required|string|max:50',
                'vehicle_type' => 'required|string|max:50',
            ]
        );


        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error",
                'errors' => $validatedData->errors()
            ], 400);
        }


        if ($this->isBlackListed($request)) {
            return response()->json([
                'status' => false,
                'message' => "We ran into an unexpected error, please try again later",
            ], 400);
        }


        $cpw = $this->getQuote($request);
        if ($cpw == null) {
            return response()->json([
                'status' => false,
                'message' => "There seems to be error in your data (Also note maximum policy period should not be more than 28 days (4 weeks))",
            ], 400);
        }


        // Parse start and end dates and times
        $bthDate = $request->date_of_birth; // Format: DD/MM/YYYY
        list($bDay, $bMonth, $bYear) = explode('-', $bthDate);


        $start_date = date("Y-m-d", strtotime($request->start_date));
        $end_date = date("Y-m-d", strtotime($request->end_date));
        $date_of_birth = date("Y-m-d", strtotime("$bYear-$bMonth-$bDay"));


        $engine_cc = (!isset($request['engine_cc']) || !is_numeric($request['engine_cc']))? null : intval($request['engine_cc']);


        // Prepare the data for saving
        $quote = new Quote();
        $quote->cpw = $cpw;
        $quote->update_price = $cpw;
        $quote->reg_number = strtoupper(strip_tags($request['reg_number']));
        $quote->vehicle_make = strip_tags($request['vehicle_make']);
        $quote->vehicle_model = strip_tags($request['vehicle_model']);
        $quote->engine_cc = $engine_cc;
        $quote->start_date = $start_date;
        $quote->start_time = $request['start_time'];
        $quote->end_date = $end_date;
        $quote->end_time = $request['end_time'];
        $quote->date_of_birth = $date_of_birth;
        $quote->first_name = strip_tags($request['first_name']);
        $quote->middle_name = strip_tags($request['middle_name']);
        $quote->last_name = strip_tags($request['last_name']);
        $quote->licence_type = strip_tags($request['licence_type']);
        $quote->licence_held_duration = strip_tags($request['licence_held_duration']);
        $quote->vehicle_type = strip_tags($request['vehicle_type']);
        $quote->user_id = Auth::check() ? Auth::id() : null; // Add user_id if authenticated, null otherwise


        $quote->contact_number = strip_tags($request['contact_number']);
        $quote->title = strip_tags($request['title']);
        $quote->postcode = strtoupper(strip_tags($request['postcode']));
        $quote->address = strip_tags($request['address']);
        $quote->town = strip_tags($request['town']);
        $quote->occupation = strip_tags($request['occupation']);
        $quote->cover_reason = strip_tags($request['cover_reason']);


        // Save the quote
        $quote->save();

        $quote->policy_number = $this->generatePolicyNumber($quote->id);
        $quote->save();


        // Store the quotation ID in the session
        Session::put('quotation_id', $quote->id);

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Quote saved successfully.',
            'quotation_id' => $quote->id,
        ]);
    }





    public function checkCarDetails(Request $request, $reg_no)
    {


        $reg_no = str_replace(" ", "", trim($reg_no));


        $carsearch_api_nx = Setting::where("param", "carsearch_api")->first();
        if ($carsearch_api_nx == null) {
            $carsearch_api = "others";
        } else {
            $carsearch_api = $carsearch_api_nx->value;
        }


        if ($carsearch_api == "others") {

            $url = "https://web-api.dayinsure.com/api/v1/vehicle/" . $reg_no;

            // Get valid access token
            $accessToken = $this->getAccessToken();

            // Make the request with the token
            $response = Http::withHeaders([
                'accept' => 'application/json',
            ])->get($url);



            // Handle API errors
            if ($response->failed()) {

                $apiResp = $response->json() ?: $response->body();
                $errorMessage = $apiResp["errorMessage"] ?? "";


                return response()->json([
                    'error' => true,
                    'message' => 'Failed to retrieve vehicle details (' . $errorMessage . ')',
                    'status_code' => $response->status(),
                    'response' => $apiResp, // Return raw if not JSON
                ], $response->status());
            }

            // Extract required fields
            $data = $response->json();
            $data = $data["detail"] ?? [];

            $carDetails = [
                'registration' => $data['registration'] ?? null,
                'make' => $data['make'] ?? null,
                'model' => $data['model'] ?? null,
                'engineCapacity' => $data['engineSize'] ?? null, // Renamed
            ];

            return response()->json($carDetails, 200);
        }




        $apiKey = config('services.mot.api_key');

        $url = "https://history.mot.api.gov.uk/v1/trade/vehicles/registration/" . urlencode($reg_no);

        // Get valid access token
        $accessToken = $this->getAccessToken();

        // Make the request with the token
        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'X-API-Key' => $apiKey,
            'accept' => 'application/json',
        ])->get($url);



        // Handle API errors
        if ($response->failed()) {

            $apiResp = $response->json() ?: $response->body();
            $errorMessage = $apiResp["errorMessage"] ?? "";


            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve vehicle details (' . $errorMessage . ')',
                'status_code' => $response->status(),
                'response' => $apiResp, // Return raw if not JSON
            ], $response->status());
        }

        // Extract required fields
        $data = $response->json();
        $carDetails = [
            'registration' => $data['registration'] ?? null,
            'make' => $data['make'] ?? null,
            'model' => $data['model'] ?? null,
            'engineCapacity' => $data['engineSize'] ?? null, // Renamed
        ];

        return response()->json($carDetails, 200);
    }


    private function getAccessToken()
    {
        // Check if an existing token is valid
        $tokenData = Setting::where('param', 'mot_token')->first();

        if ($tokenData) {
            $storedToken = json_decode($tokenData->value, true);
            $storedTime = Carbon::parse($storedToken['datetime']);

            // If token is still valid (less than 58 minutes old), return it
            if ($storedTime->diffInMinutes(now()) < 58) {
                return $storedToken['access_token'];
            }
        }


        $client_id = config('services.mot.client_id');
        $client_secret = config('services.mot.client_secret');
        $scope_url = config('services.mot.scope_url');
        $token_url = config('services.mot.token_url');

        // If no valid token, request a new one
        $response = Http::asForm()->post($token_url, [
            'grant_type' => 'client_credentials',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'scope' => $scope_url,
        ]);

        if ($response->failed()) {
            abort(500, 'Failed to retrieve access token');
        }

        $accessToken = $response->json()['access_token'];

        // Store the new token with timestamp
        Setting::updateOrCreate(
            ['param' => 'mot_token'],
            ['value' => json_encode(['access_token' => $accessToken, 'datetime' => now()])]
        );

        return $accessToken;
    }


    public function getAvailableHours(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json([
                'error' => 'Date is required'
            ], 400);
        }

        $hours = [];

        // Get the current date and hour
        $now = new \DateTime();
        $currentDate = $now->format('Y-m-d');
        $currentHour = (int) $now->format('H');

        if ($date === $currentDate) {
            // Return future hours only for the current date
            for ($hour = $currentHour; $hour <= 23; $hour++) {
                $hours[] = sprintf('%02d', $hour);
            }
        } else {
            // Return all hours for any other date
            for ($hour = 0; $hour <= 23; $hour++) {
                $hours[] = sprintf('%02d', $hour);
            }
        }

        return response()->json([
            'hours' => $hours
        ]);
    }


    public function getAvailableMinutes(Request $request)
    {
        $date = $request->input('date');
        $hour = $request->input('hour');

        if (!$date || $hour === null) {
            return response()->json([
                'error' => 'Date and hour are required'
            ], 400);
        }

        $minutes = [];
        $now = new \DateTime();
        $currentDate = $now->format('Y-m-d');
        $currentHour = (int) $now->format('H');
        $currentMinute = (int) $now->format('i');

        // Handle same date condition
        if ($date === $currentDate) {
            if ((int) $hour === $currentHour) {
                // Current hour: future minutes only with a 10-minute buffer
                // $startMinute = ceil(($currentMinute + 10) / 10) * 10;
                $startMinute = floor(($currentMinute + 10) / 10) * 10;

                for ($minute = $startMinute; $minute < 60; $minute += 10) {
                    $minutes[] = sprintf('%02d', $minute);
                }
            } elseif ((int) $hour === $currentHour + 1) {
                // Immediate next hour: Ensure the 10-minute interval continues
                // $startMinute = ($currentMinute < 50) ? 0 : 10;

                // for ($minute = $startMinute; $minute < 60; $minute += 10) {
                //     $minutes[] = sprintf('%02d', $minute);
                // }

                for ($minute = 0; $minute < 60; $minute += 10) {
                    $minutes[] = sprintf('%02d', $minute);
                }
            } else {
                // Regular case for other future hours
                for ($minute = 0; $minute < 60; $minute += 10) {
                    $minutes[] = sprintf('%02d', $minute);
                }
            }
        } else {
            // Different date: Regular 10-minute interval
            for ($minute = 0; $minute < 60; $minute += 10) {
                $minutes[] = sprintf('%02d', $minute);
            }
        }

        return response()->json([
            'minutes' => $minutes
        ]);
    }



    public function searchAddress(Request $request)
    {
        $postcode = $request->input('postcode');

        $postcode_en = rawurldecode($postcode);

        // URL for the API request
        $url = "https://proxy.v1a.goshorty.co.uk/postcode/" . $postcode_en . "/2a-925891-194addebafc-3887ee9e";

        // die($url);

        // Perform the GET request
        $response = Http::get($url);

        // Check if the request was successful
        if ($response->successful()) {
            $data = $response->json();

            return response()->json([
                'addresses' => $data['addresses'] ?? [],
            ], 200);
        }

        // Handle errors or failed requests
        return response()->json([
            'error' => 'Failed to fetch address data',
            'bui' => $rawBody = $response->body(),
        ], 500);
    }














    private function getQuote($request)
    {
        $dob = $request->date_of_birth; // Format: DD/MM/YYYY
        $registration_no = $request->reg_number;

        if (!empty($dob) && !empty($registration_no)) {
            // Parse date of birth in DD/MM/YYYY format
            list($day, $month, $year) = explode('-', $dob);
            $dobTimestamp = mktime(0, 0, 0, $month, $day, $year);

            // Calculate age
            $currentTimestamp = time();
            $ageInMilliseconds = $currentTimestamp - $dobTimestamp;
            $ageInYears = $ageInMilliseconds / (365.25 * 24 * 60 * 60);
            $age = floor($ageInYears);

            // Base prices
            $basePrice = 23.58;
            $basePriceHour = 13.72;
            $basePricePerHour = 0.38; // 1.89;


            // Parse start and end dates and times
            $startDate = $request->start_date; // Format: DD/MM/YYYY
            $startTime = $request->start_time; // Format: HH:mm:ss
            $endDate = $request->end_date; // Format: DD/MM/YYYY
            $endTime = $request->end_time; // Format: HH:mm:ss

            list($sYear, $sMonth, $sDay) = explode('-', $startDate);
            list($eYear, $eMonth, $eDay) = explode('-', $endDate);

            list($sHour, $sMinute) = explode(':', $startTime);
            list($eHour, $eMinute) = explode(':', $endTime);

            $startTimestamp = mktime($sHour, $sMinute, 0, $sMonth, $sDay, $sYear);
            $endTimestamp = mktime($eHour, $eMinute, 0, $eMonth, $eDay, $eYear);

            $timeDifference = $endTimestamp - $startTimestamp;
            $minutesDifference = $timeDifference / 60;
            $hoursDifference = $minutesDifference / 60;

            $dayAvailable = floor($hoursDifference / 24);
            $hourAvailable = ceil($hoursDifference - ($dayAvailable * 24));
            $minuteAvailable = ceil($minutesDifference - ($dayAvailable * 24 * 60) - ($hourAvailable * 60));



            $quote_php_func = Setting::where("param", "quote_php_func")->first();
            $quotePhpFunc = $quote_php_func->value;

            // If no malicious code is detected, you can safely execute the code
            eval($quotePhpFunc);
            // eval('function getQuote($minuteAvailable, $hourAvailable, $dayAvailable, $age) { return 67; }');

            $finalPrice = getQuote($minuteAvailable, $hourAvailable, $dayAvailable, $age);


            // Return the final price formatted to 2 decimal places
            if (is_numeric($finalPrice)) {
                return number_format($finalPrice, 2, '.', '');
            }
        }

        return null; // Return null if data is invalid or incomplete
    }


    private function generatePolicyNumber($quote_id, $n = 8)
    {
        // Ensure the quote_id is a string for concatenation
        $quote_id = (string) $quote_id;

        // Generate a random number with $n digits
        $randomDigits = str_pad(mt_rand(0, pow(10, $n) - 1), $n, '0', STR_PAD_LEFT);

        // Combine the quote_id and random digits to form the 
        $policy_number = (string) $quote_id . $randomDigits;

        return substr($policy_number, 0, 8);
    }
}
