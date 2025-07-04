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





}
