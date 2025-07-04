<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;
use App\Models\Position;

use Illuminate\Support\Facades\Mail;
use App\Models\Quote;
use App\Mail\OrderExpiresMail;


use DataTables;
use CHelper;

class AppAdminController extends Controller
{


    public function __construct(Request $request)
    {
    }


    public function index(Request $request)
    {

        $user = $request->user();
        if(! $user->isAllowed(["SUPER_ADMIN"])){
            return "Access Restricted";
        }
        return view('admin.admins');

    }



    public function data(Request $request)
    {

        $user = $request->user();
        if(! $user->isAllowed(["SUPER_ADMIN"])){
            return "Access Restricted";
        }

        $model = Admin::select('admin_id', 'fname', 'lname', 'email', 'phone', 'role');

        return DataTables::of($model)
            ->make(false);
    }



    public function addAdmin(Request $request)
    {

        $user = $request->user();
        if(! $user->isAllowed(["SUPER_ADMIN"])){
            return "Access Restricted";
        }


        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:admins,email',
            'phone' => 'nullable|string|max:12',
            'fname' => 'required|string|min:3|max:50',
            'lname' => 'required|string|min:3|max:50',
            'password' => 'required|string|min:6|max:20',
            'role' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $admin = new Admin();
        $admin->role = $request->role;
        $admin->phone = $request->phone;
        $admin->fname = $request->fname;
        $admin->lname = $request->lname;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();

        return response()->json([
            'status' => true,
            'message' => 'successful',
            'data' => $admin,
        ], 200);
    }



    public function updateAdmin(Request $request)
    {

        $user = $request->user();
        if(! $user->isAllowed(["SUPER_ADMIN"])){
            return "Access Restricted";
        }

        $validator = Validator::make($request->all(), [
            'admin_id' => 'required|exists:admins,admin_id',
            'email' => 'required|email|unique:users,email|unique:admins,email,' . $request->admin_id.',admin_id',
            'phone' => 'nullable|string|max:12',
            'fname' => 'required|string|min:3|max:50',
            'lname' => 'required|string|min:3|max:50',
            'password' => 'nullable|string|min:6|max:20',
            'role' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $admin = Admin::where("admin_id", $request->admin_id)->first();

        if ($request->admin_id == 1) {
            $admin->role = 1;
        } else {
            $admin->role = $request->role;
        }
        $admin->phone = $request->phone;
        $admin->fname = $request->fname;
        $admin->lname = $request->lname;
        $admin->email = $request->email;

        if (trim($request->password) != "") {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return response()->json([
            'status' => true,
            'message' => 'successful',
            'data' => $admin,
        ], 200);
    }


    public function deleteAdmin(Request $request, $admin_id)
    {

        $user = $request->user();
        if(! $user->isAllowed(["SUPER_ADMIN"])){
            return "Access Restricted";
        }

        $validator = Validator::make(["admin_id" => $admin_id], [
            'admin_id' => 'required|exists:admins,admin_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        if ($admin_id == 1) {
            return response()->json([
                'status' => false,
                'message' => "Vous ne pouvez pas supprimer cet utilisateur. Utilisateur de base",
            ], 400);

        }

        $admin = Admin::where("admin_id", $admin_id)->first();

        $admin->delete();

        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }





    /**
     * Execute the console command.
     */
    public function SendPolicyExpirationReminders(Request $request)
    {
        $dateTimeNow = date("Y-m-d H:i:s");
        $dateTimeNow10MinLater = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        $dateTimeNow20MinLater = date("Y-m-d H:i:s", strtotime("+20 minutes"));

        
        DB::table('quotes')
            ->whereRaw("CONCAT(end_date, ' ', end_time) < ?", [$dateTimeNow])
            ->update(['expired_state' => 'expired']);

        // Fetch quotes expiring in the next 10 minutes and where email hasn't been sent yet
        $expireQuotes = Quote::where(function ($query) use ($dateTimeNow) {
            $query->whereNull('mail_sent')
                  ->orWhere('mail_sent', '<', $dateTimeNow);
        })
        ->where("payment_status", "1")
        ->whereNot("expired_state", "expired")
        ->whereNot("expired_state", "cancelled")
        ->whereRaw("CONCAT(end_date, ' ', end_time) <= ?", [$dateTimeNow10MinLater])
        ->get();

        foreach ($expireQuotes as $quote) {
            try {
                Mail::to($quote->user->email)->send(new OrderExpiresMail($quote));
                
                // Mark the quote as email sent
                $quote->mail_sent = $dateTimeNow20MinLater;
                $quote->save();

                echo "Reminder email sent to: {$quote->user->email} for quote ID: {$quote->id} <br>";
            } catch (\Exception $e) {
                echo "Failed to send email for quote ID: {$quote->id}. Error: " . $e->getMessage()."<br>";
            }
        }

        echo "Policy expiration reminders process completed.";
    }

    


}
