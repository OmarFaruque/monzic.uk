<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\BlackList;



use DataTables;
use CHelper;

class BlackListController extends Controller
{


    private function normalizeText(?string $value): string
    {
        return mb_strtolower(trim((string) $value));
    }

    private function normalizeEmail(?string $value): string
    {
        return preg_replace('/\s+/', '', $this->normalizeText($value));
    }

    private function normalizeRegistration(?string $value): string
    {
        return preg_replace('/\s+/', '', $this->normalizeText($value));
    }

    private function normalizeAddress(?string $value): string
    {
        return preg_replace('/\s+/', ' ', $this->normalizeText($value));
    }

    private function normalizeRegistrations(string $registrations): string
    {
        $normalized = [];
        foreach (explode(',', $registrations) as $registration) {
            $registration = $this->normalizeRegistration($registration);
            if ($registration !== '') {
                $normalized[] = $registration;
            }
        }

        return implode(', ', array_unique($normalized));
    }

    public function __construct(Request $request)
    {
    }


    public function index(Request $request)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }
        return view('admin.blacklist');

    }



    public function data(Request $request)
    {

        $admin = $request->user();
        if (!$admin->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $model = BlackList::select('id',
        'matches',
        'hits',
        'created_at');

        return DataTables::of($model)
            ->escapeColumns([])
            ->make(false);
    }



    public function addBlackList(Request $request)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }


        $validator = Validator::make($request->all(), [
            'last_name' => 'nullable|string',
            'first_name' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
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

        
        $blacklist = new BlackList();

        $matches = [];
        if($request->has('last_name') && $request->last_name != ""){
            $matches["last_name"] = $this->normalizeText($request->last_name);
        }
        if($request->has('first_name') && $request->first_name != ""){
            $matches["first_name"] = $this->normalizeText($request->first_name);
        }
        if($request->has('email') && $request->email != ""){
            $matches["email"] = $this->normalizeEmail($request->email);
        }
        if($request->has('address') && $request->address != ""){
            $matches["address"] = $this->normalizeAddress($request->address);
        }
        if($request->has('birth_date') && $request->birth_date != ""){
            $matches["birth_date"] = $request->birth_date;
        }
        if($request->has('registrations') && $request->registrations != ""){
            $matches["registrations"] = $this->normalizeRegistrations($request->registrations);
        }   

        $matches = json_encode($matches);

 
        $blacklist->matches = $matches;

        $blacklist->save();

        return response()->json([
            'status' => true,
            'message' => 'successful',
            'data' => $blacklist,
        ], 200);
    }



 
    public function updateBlackList(Request $request)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }


        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:black_lists,id',
            'last_name' => 'nullable|string',
            'first_name' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
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

        
        $blacklist = BlackList::find($request->id);

        $matches = [];
        if($request->has('last_name') && $request->last_name != ""){
            $matches["last_name"] = $this->normalizeText($request->last_name);
        }
        if($request->has('first_name') && $request->first_name != ""){
            $matches["first_name"] = $this->normalizeText($request->first_name);
        }
        if($request->has('email') && $request->email != ""){
            $matches["email"] = $this->normalizeEmail($request->email);
        }
        if($request->has('address') && $request->address != ""){
            $matches["address"] = $this->normalizeAddress($request->address);
        }
        if($request->has('birth_date') && $request->birth_date != ""){
            $matches["birth_date"] = $request->birth_date;
        }
        if($request->has('registrations') && $request->registrations != ""){
            $matches["registrations"] = $this->normalizeRegistrations($request->registrations);
        }

        $matches = json_encode($matches);

        
        $blacklist->matches = $matches;


        $blacklist->save();

        return response()->json([
            'status' => true,
            'message' => 'successful',
            'data' => $blacklist,
        ], 200);
    }



    public function deleteBlackList(Request $request, $id)
    {

        $user = $request->user();
        if (!$user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $validator = Validator::make(["id" => $id], [
            'id' => 'required|exists:black_lists,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $blacklist = BlackList::where("id", $id)->first();

        
        $blacklist->delete();

        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }




}
