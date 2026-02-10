<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Position;


use DataTables;
use CHelper;

class AppUserController extends Controller
{


    public function __construct(Request $request)
    {
    }


    public function index(Request $request)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return "Access Restricted";
        }

        $qUser = null;
        if ($request->query->has('user')) {
            $qUser = User::where("username", $request->query('user'))->first();
        }

        $userCount = User::count();

        return view('admin.users', ["qUser" => $qUser, "userCount" => $userCount]);

    }



    public function data(Request $request)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return "Access Restricted";
        }



        $model = User::select(
            'users.user_id',
            'users.email',
            'users.first_name',
            'users.last_name',
            'users.created_at',
        );


        return DataTables::of($model)
            ->make(false);
    }



    public function deleteUser(Request $request, $user_id)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $validator = Validator::make(["user_id" => $user_id], [
            'user_id' => 'required|exists:users,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $user = User::where("user_id", $user_id)->first();

        $user->orders()->delete();

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }



    public function exportUsers(Request $request)
    {
        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN", "ADMIN"])) {
            return response('Access Restricted', 403);
        }

        $fileName = 'users_export.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];


        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'First Name', 'Last Name', 'Phone Number', 'Sign Up Date', 'Address']);

            User::with('orders')->orderBy('user_id', 'desc')->chunk(100, function($users) use($file) {
                 foreach ($users as $user) {
                    $latestOrder = $user->orders->sortByDesc('id')->first();

                    $row['email']        = $user->email;
                    $row['first_name']   = $user->first_name;
                    $row['last_name']    = $user->last_name;
                    $row['phone_number'] = $latestOrder ? $latestOrder->contact_number : '';
                    $row['sign_up_date'] = $user->created_at->format('Y-m-d');
                    $address = "";
                    if($latestOrder){
                        $address = ($latestOrder->address??'') . ', ' . ($latestOrder->postcode??'');
                    }
                    $row['address']      = $address;

                    fputcsv($file, [
                        $row['email'],
                        $row['first_name'],
                        $row['last_name'],
                        $row['phone_number'],
                        $row['sign_up_date'],
                        $row['address']
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
