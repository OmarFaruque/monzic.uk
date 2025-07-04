<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class AdminController extends Controller
{


    public function __construct(Request $request)
    {

    }


    public function index(Request $request)
    {

        return view('admin.index');
    }




    public function showChangePassword(Request $request)
    {

        return view('admin.update-password');
    }




    public function updatePassword(Request $request)
    {

        $admin = $request->user();

        $validator = Validator::make($request->all(), [
            'old_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($admin) {
                    if (!Hash::check($value, $admin->password)) {
                        $fail("Old password is incorrect");
                    }
                },
            ],
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $admin->password = Hash::make($request->new_password);
        $admin->save();

        //Return Success
        return response()->json([
            'status' => true,
            'message' => 'Updated successfully',
        ], 200);
    }




    public function logout(Request $request)
    {
        Auth('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/");
    }



}
