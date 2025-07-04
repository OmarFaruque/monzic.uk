<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\PromoCode;



use DataTables;
use CHelper;

class CouponController extends Controller
{


    public function __construct(Request $request)
    {
    }


    public function index(Request $request)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }
        return view('admin.coupon');

    }



    public function data(Request $request)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $model = PromoCode::select('id',
        'promo_code',
        'discount_type',
        'amount',
        'min_spent',
        'matches',
        'expires',
        'available',
        'used',
        'created_at');

        return DataTables::of($model)
            ->escapeColumns([])
            ->make(false);
    }



    public function addCoupon(Request $request)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }


        $validator = Validator::make($request->all(), [
            'promo_code' => 'required|unique:promo_codes,promo_code',
            'discount_type' => 'required|string',
            'amount' => 'required|numeric',
            'min_spent' => 'nullable|numeric',
            'matches' => 'nullable|string',
            'expires' => 'required|date',
            'available' => 'required|integer',
            'last_name' => 'nullable|string',
            'registrations' => 'nullable|string',
            'birth_date' => [
                'nullable',
                'regex:/^\d{4}(-\d{2})?(-\d{2})?$/',
            ],
        ], [
            'birth_date.regex' => 'The birth date must be in the format YYYY, YYYY-MM, or YYYY-MM-DD.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        
        $coupon = new PromoCode();

        $matches = [];
        if($request->has('last_name') && $request->last_name != ""){
            $matches["last_name"] = $request->last_name;
        }
        if($request->has('birth_date') && $request->birth_date != ""){
            $matches["birth_date"] = $request->birth_date;
        }
        if($request->has('registrations') && $request->registrations != ""){
            $matches["registrations"] = $request->registrations;
        }

        $matches = json_encode($matches);

        
        $coupon->promo_code = strip_tags($request->promo_code);
        $coupon->discount_type = strip_tags($request->discount_type);
        $coupon->amount = $request->amount;
        $coupon->min_spent = $request->has('min_spent')? $request->min_spent : 0;
        $coupon->available = $request->available;

        $coupon->min_spent = $request->min_spent;

        $coupon->matches = $matches;

        $coupon->expires = date("Y-m-d H:i:s", strtotime($request->expires));

        $coupon->save();

        return response()->json([
            'status' => true,
            'message' => 'successful',
            'data' => $coupon,
        ], 200);
    }



 
    public function updateCoupon(Request $request)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }


        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:promo_codes,id',
            'promo_code' => 'required|unique:promo_codes,promo_code,'.$request->id.',id',
            'discount_type' => 'required|string',
            'amount' => 'required|numeric',
            'min_spent' => 'nullable|numeric',
            'matches' => 'nullable|string',
            'expires' => 'required|date',
            'available' => 'required|integer',
            'last_name' => 'nullable|string',
            'registrations' => 'nullable|string',
            'birth_date' => [
                'nullable',
                'regex:/^\d{4}(-\d{2})?(-\d{2})?$/',
            ],
        ], [
            'birth_date.regex' => 'The birth date must be in the format YYYY, YYYY-MM, or YYYY-MM-DD.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        
        $coupon = PromoCode::find($request->id);

        $matches = [];
        if($request->has('last_name') && $request->last_name != ""){
            $matches["last_name"] = $request->last_name;
        }
        if($request->has('birth_date') && $request->birth_date != ""){
            $matches["birth_date"] = $request->birth_date;
        }
        if($request->has('registrations') && $request->registrations != ""){
            $matches["registrations"] = $request->registrations;
        }

        $matches = json_encode($matches);

        
        $coupon->promo_code = strip_tags($request->promo_code);
        $coupon->discount_type = strip_tags($request->discount_type);
        $coupon->amount = $request->amount;
        $coupon->min_spent = $request->has('min_spent')? $request->min_spent : 0;
        $coupon->available = $request->available;


        $coupon->matches = $matches;

        $coupon->expires = date("Y-m-d H:i:s", strtotime($request->expires));

        $coupon->save();

        return response()->json([
            'status' => true,
            'message' => 'successful',
            'data' => $coupon,
        ], 200);
    }



    public function deleteCoupon(Request $request, $id)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $validator = Validator::make(["id" => $id], [
            'id' => 'required|exists:promo_codes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $coupon = PromoCode::where("id", $id)->first();

        
        $coupon->delete();

        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }




}
