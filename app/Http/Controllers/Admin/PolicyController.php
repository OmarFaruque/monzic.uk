<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderCancelledMail;
use App\Mail\OrderConfirmationMail;
use App\Models\BlackList;
use App\Models\PromoCode;
use App\Models\Quote;
use App\Models\Setting;
use App\Models\User;
use App\Services\BlacklistService;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\Refund;
use Stripe\Stripe;

class PolicyController extends Controller
{

    public function __construct(Request $request, private BlacklistService $blacklistService)
    {
    }



    public function index(Request $request)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return "Access Restricted";
        }

        $date_time_now = date("Y-m-d H:i:s");

        DB::table('quotes')
            ->whereRaw("CONCAT(end_date, ' ', end_time) < ?", [$date_time_now])
            ->update(['expired_state' => 'expired']);


        return view('admin.policies');

    }



    public function indexUn(Request $request)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return "Access Restricted";
        }

        $date_time_now = date("Y-m-d H:i:s");

        DB::table('quotes')
            ->whereRaw("CONCAT(end_date, ' ', end_time) < ?", [$date_time_now])
            ->update(['expired_state' => 'expired']);


        return view('admin.policies-un');

    }






    public function data(Request $request)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return "Access Restricted";
        }

        $date_time_now = date("Y-m-d H:i:s");

        DB::table('quotes')
            ->whereRaw("CONCAT(end_date, ' ', end_time) < ?", [$date_time_now])
            ->update(['expired_state' => 'expired']);


        $model = Quote::select(
            'id',
            'policy_number',
            'cpw',
            'start_date',
            'start_time',
            'end_date',
            'end_time',
            'reg_number',
            'vehicle_make',
            'vehicle_model',
            'engine_cc',
            'date_of_birth',
            'quotes.first_name',
            'quotes.middle_name',
            'quotes.last_name',
            'licence_type',
            'licence_held_duration',
            'vehicle_type',
            'update_price',
            'expired_state',
            'refund_state',
            'users.email',
        )->leftJoin('users', 'quotes.user_id', 'users.user_id')
        ->where("payment_status", 1);


        return DataTables::of($model)
            ->make(false);
    }




    public function dataUn(Request $request)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return "Access Restricted";
        }

        $date_time_now = date("Y-m-d H:i:s");

        DB::table('quotes')
            ->whereRaw("CONCAT(end_date, ' ', end_time) < ?", [$date_time_now])
            ->update(['expired_state' => 'expired']);


        $model = Quote::select(
            'id',
            'policy_number',
            'cpw',
            'update_price',
            'start_date',
            'start_time',
            'end_date',
            'end_time',
            'reg_number',
            'quotes.first_name',
            'quotes.middle_name',
            'quotes.last_name',
            'users.email',
        )->leftJoin('users', 'quotes.user_id', 'users.user_id')
            ->where('payment_status', 0)
            ->where('spayment_id', 'like', 'bank_%');

        return DataTables::of($model)
            ->make(false);
    }



    public function refundPolicy(Request $request, $id)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $validator = Validator::make(["id" => $id], [
            'id' => 'required|exists:quotes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $quote = Quote::where("id", $id)->first();

        if ($quote->refund_state != "" && $quote->refund_state != null) {
            return response()->json([
                'status' => false,
                'message' => 'Alread Performed',
            ], 400);
        }

        $intent = $quote->intents()->where("status", 1)->first();

        $stripeSecret = Setting::where("param", "stripe_secret")->first();
        if ($stripeSecret == null) {
            $stripeSecretKey = config('services.stripe.secret');
        } else {
            $stripeSecretKey = $stripeSecret->value;
        }
        Stripe::setApiKey($stripeSecretKey);

        // Create the refund
        $refund = Refund::create([
            'payment_intent' => $intent->intent_id,
        ]);
        // Check the status
        $status = $refund->status;

        $quote->refund_state = $status;

        $quote->save();


        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }



    public function cancelPolicy(Request $request, $id)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $validator = Validator::make(["id" => $id], [
            'id' => 'required|exists:quotes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $quote = Quote::where("id", $id)->first();

        if ($quote->expired_state != "" && $quote->expired_state != null) {
            return response()->json([
                'status' => false,
                'message' => 'Not allowed',
            ], 400);
        }

        Mail::to($quote->user()->first())->send(new OrderCancelledMail($quote));


        $quote->expired_state = "cancelled";

        $quote->save();


        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }


    public function refundEvents(Request $request)
    {
        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return "Access Restricted";
        }

        return view('admin.refund-events');
    }

    public function refundEventsData(Request $request)
    {
        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return "Access Restricted";
        }

        $model = Quote::select(
            'quotes.id',
            'quotes.policy_number',
            'quotes.refund_state',
            'quotes.spayment_id',
            'quotes.cpw',
            'quotes.update_price',
            'quotes.first_name',
            'quotes.last_name',
            'users.email',
            'quotes.address',
            'quotes.updated_at',
            DB::raw("CASE WHEN EXISTS (
                SELECT 1
                FROM black_lists bl
                WHERE JSON_UNQUOTE(JSON_EXTRACT(bl.matches, '$.email')) = LOWER(users.email)
                   OR JSON_UNQUOTE(JSON_EXTRACT(bl.matches, '$.address')) = LOWER(TRIM(quotes.address))
            ) THEN 1 ELSE 0 END as is_blocked")
        )->leftJoin('users', 'quotes.user_id', 'users.user_id')
            ->where('quotes.payment_status', 1)
            ->where('quotes.spayment_id', 'like', 'paddle_%')
            ->where(function ($query) {
                $query->where('quotes.refund_state', 'like', 'paddle_refund_%')
                    ->orWhere('quotes.refund_state', 'like', 'paddle_chargeback_%');
            });    

        return DataTables::of($model)->make(false);
    }

    public function blacklistRefundUser(Request $request, $id)
    {
        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return "Access Restricted";
        }

        $validator = Validator::make(["id" => $id], [
            'id' => 'required|exists:quotes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $quote = Quote::leftJoin('users', 'quotes.user_id', 'users.user_id')
            ->where('quotes.id', $id)
            ->select('quotes.id', 'quotes.first_name', 'quotes.last_name', 'users.email', 'quotes.address')
            ->first();

       if (!$quote) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.',
            ], 422);
        }

        $blockEmail = filter_var($request->input('block_email'), FILTER_VALIDATE_BOOLEAN);
        $blockAddress = filter_var($request->input('block_address'), FILTER_VALIDATE_BOOLEAN);

        if (!$blockEmail && !$blockAddress) {
            return response()->json([
                'status' => false,
                'message' => 'Choose at least one item to block.',
            ], 422);
        }

        if ($blockEmail && !$quote->email) {
            return response()->json([
                'status' => false,
                'message' => 'Customer email is unavailable for this order.',
            ], 422);
        }

       if ($blockAddress && !$quote->address) {
            return response()->json([
                'status' => false,
                'message' => 'Customer address is unavailable for this order.',
            ], 422);
        }

        if ($blockEmail) {
            $matches = json_encode(['email' => strtolower(trim($quote->email))]);
            BlackList::firstOrCreate(['matches' => $matches]);
        }

        if ($blockAddress) {
            $matches = json_encode(['address' => strtolower(trim(preg_replace('/\s+/', ' ', $quote->address)))]);
            BlackList::firstOrCreate(['matches' => $matches]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer added to blacklist successfully.',
        ]);
    }


    public function newPolicy(Request $request)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $quote_js_func = Setting::where("param", "quote_js_func")->first();
        $quoteJsFunc = $quote_js_func->value;
        $quoteJsFunc = $quote_js_func->value;

        $quote = null;

        return view('admin.quote', ["quote" => $quote, "quoteJsFunc" => $quoteJsFunc]);


    }



    public function editPolicy(Request $request, $id)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $validator = Validator::make(["policy_number" => $id], [
            'policy_number' => 'required|exists:quotes,policy_number',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $quote = Quote::where("policy_number", $id)->first();

        $quote_js_func = Setting::where("param", "quote_js_func")->first();
        $quoteJsFunc = $quote_js_func->value;
        $quoteJsFunc = $quote_js_func->value;


        return view('admin.quote', ["quote" => $quote, "quoteJsFunc" => $quoteJsFunc]);


    }



    // Add Quote. 
    public function addPolicy(Request $request)
    {


        $validatedData = Validator::make(
            $request->all(),
            [
                'cpw' => 'required|numeric',
                'user_id' => 'required|exists:users,user_id',
                'update_price' => 'required|numeric',
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
        $quote->update_price = $request->update_price;
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

        $quote->user_id = $request->user_id;
        $quote->payment_status = 1;

        $quote->contact_number = strip_tags($request['contact_number']);
        $quote->title = strip_tags($request['title']);
        $quote->postcode = strtoupper(strip_tags($request['postcode']));
        $quote->address = strip_tags($request['address']);
        $quote->occupation = strip_tags($request['occupation']);
        $quote->cover_reason = strip_tags($request['cover_reason']);


        // Save the quote
        $quote->save();

        $quote->policy_number = $this->generatePolicyNumber($quote->id);
        $quote->save();



        //WE WILL SEND CONFIRMATION MESSAGE HERE                    
        Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));


        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Policy created successfully.',
            'quotation_id' => $quote->id,
        ]);


    }



    // Update Quote. 
    public function updatePolicy(Request $request)
    {


        $validatedData = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:quotes,id',
                'cpw' => 'required|numeric',
                'update_price' => 'required|numeric',
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
        $quote = Quote::find($request->id);
        $quote->cpw = $cpw;
        $quote->update_price = $request->update_price;
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


        $quote->contact_number = strip_tags($request['contact_number']);
        $quote->title = strip_tags($request['title']);
        $quote->postcode = strtoupper(strip_tags($request['postcode']));
        $quote->address = strip_tags($request['address']);
        $quote->occupation = strip_tags($request['occupation']);
        $quote->cover_reason = strip_tags($request['cover_reason']);


        if (strtotime($end_date . ' ' . $request['end_time']) > time()) {
            $quote->expired_state = "";
        }

        // Save the quote
        $quote->save();


        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Policy updated successfully.',
            'quotation_id' => $quote->id,
        ]);


    }




    public function getUsers(Request $request)
    {
        $query = $request->input('q');

        // Split the query by spaces, append '*' to each part, and rejoin
        $wildcardQuery = collect(explode(' ', $query))
            ->map(fn($term) => $term . '*')
            ->join(' ');

        // $wildcardQuery = $query;



        $users = User::selectRaw("
            user_id,
            CONCAT(first_name, ' ', last_name) as name,
            email,
            MATCH(first_name, last_name) AGAINST (?) as score
        ", [$query])
            ->whereRaw("MATCH(first_name, last_name) AGAINST (?)", [$wildcardQuery])
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orderByDesc('score')
            ->limit(100)
            ->get();

        return response()->json($users);
    }




    public function confirmPolicy(Request $request)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $validator = Validator::make(["id" => $request->id], [
            'id' => 'required|exists:quotes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $quote = Quote::where("id", $request->id)->first();

        if ($quote->payment_status == 1) {
            return response()->json([
                'status' => true,
                'message' => 'Policy already confirmed',
            ], 200);
        }

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




        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }



    public function deletePolicy(Request $request, $id)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $validator = Validator::make(["id" => $id], [
            'id' => 'required|exists:quotes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $quote = Quote::where("id", $id)->first();

        $quote->intents()->delete();

        $quote->delete();

        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }






    private function isBlackListed($quote)
    {
        $email = $quote->email ?? null;
        if (!$email && $quote->user_id) {
            $user = User::find($quote->user_id);
            if ($user) {
                $email = $user->email;
            }
        }

        return $this->blacklistService->isBlacklisted([
            'email' => $email,
            'first_name' => $quote->first_name ?? null,
            'last_name' => $quote->last_name ?? null,
            'birth_date' => $quote->date_of_birth ?? null,
            'reg_number' => $quote->reg_number ?? null,
            'address' => $quote->address ?? null,
        ]);
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
            eval ($quotePhpFunc);
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
