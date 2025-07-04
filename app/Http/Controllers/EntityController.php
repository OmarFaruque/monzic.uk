<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Entity;

use DataTables;

class EntityController extends Controller
{
    // Display the list of entities
    public function index()
    {

        return view('entities.index');
    }


    // Return a single entity details
    public function get(Request $request, $id)
    {

        // Create the entity
        $entity = Entity::find($request->id);

        return response()->json([
            'status' => true,
            'data' => $entity,
        ], 200);

    }



    // Show the form to create a new entity
    public function create()
    {
        return view('entities.create');
    }


    // Datatable Data source using Laravel datatable plugin
    public function data(Request $request)
    {


        // These columns must be in order as it will be
        // in thesame order in the Javascript frontend
        // $model = Entity::select(
        //     'id',
        //     'name',
        //     'version',
        //     'main_contact',
        //     'tel',
        //     'type',
        //     'school_type',
        //     'barcode',
        //     'barcode_type',
        //     'students_enabled',
        //     'barcode_prefix',
        // );


        $model = DB::table('entities')->select(
            'entities.id',
            'entities.name',
            'customer_info.cust_postcode',
            'entities.tel',
        )->leftJoin('customer_info', 'entities.id', 'customer_info.eid');

        return DataTables::of($model)
            ->make(false);
    }





    // Handle the form submission to store a new entity
    public function store(Request $request)
    {

        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:50',
                'version' => 'nullable|in:V1,V2,V3,V4',
                'main_contact' => 'required|string|max:30',
                'tel' => 'required|string|max:40',
                'type' => 'required|string|max:10',
                'school_type' => 'nullable|in:secondary,primary',
                'barcode' => 'required|in:barcode,qrcode',
                'barcode_type' => 'required|in:ean8,ean13,code39,code93,code128',
                'id_card_type' => 'nullable|string',
                'students_enabled' => 'required|boolean',
                'barcode_prefix' => 'required|string|max:3',
                'booking_tags' => 'nullable|string|max:100',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => __('messages.validation_error'),
                'errors' => $validateUser->errors()
            ], 400);
        }

        $validatedData = $request->all();

        // Additional XSS sanitization on certain fields
        $validatedData['name'] = e($validatedData['name']);
        $validatedData['main_contact'] = e($validatedData['main_contact']);
        $validatedData['tel'] = e($validatedData['tel']);
        $validatedData['type'] = e($validatedData['type']);
        $validatedData['barcode_prefix'] = e($validatedData['barcode_prefix']);
        $validatedData['booking_tags'] = isset($validatedData['booking_tags']) ? e($validatedData['booking_tags']) : null;


        // Create the entity
        Entity::create($validatedData);


        return response()->json([
            'status' => true,
            'message' => 'Created sucessfully',
        ], 200);



    }




    // Show the form to edit an existing entity
    public function edit($id)
    {
        $entity = Entity::findOrFail($id);
        return view('entities.edit', compact('entity'));
    }


    // Handle the form submission to update an existing entity
    public function update(Request $request, $id)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:entities',
                'name' => 'required|string|max:50',
                'version' => 'nullable|in:V1,V2,V3,V4',
                'main_contact' => 'required|string|max:30',
                'tel' => 'required|string|max:40',
                'type' => 'required|string|max:10',
                'school_type' => 'nullable|in:secondary,primary',
                'barcode' => 'required|in:barcode,qrcode',
                'barcode_type' => 'required|in:ean8,ean13,code39,code93,code128',
                'id_card_type' => 'nullable|string',
                'students_enabled' => 'required|boolean',
                'barcode_prefix' => 'required|string|max:3',
                'booking_tags' => 'nullable|string|max:100',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => __('messages.validation_error'),
                'errors' => $validateUser->errors()
            ], 400);
        }

        $validatedData = $request->all();


        // Create the entity
        $entity = Entity::find($request->id);

        $entity->version = $request->version;
        $entity->school_type = $request->school_type;
        $entity->barcode = $request->barcode;
        $entity->barcode_type = $request->barcode_type;
        $entity->students_enabled = $request->students_enabled;

        // Additional XSS sanitization on certain fields
        $entity->name = e($validatedData['name']);
        $entity->main_contact = e($validatedData['main_contact']);
        $entity->tel = e($validatedData['tel']);
        $entity->type = e($validatedData['type']);
        $entity->barcode_prefix = e($validatedData['barcode_prefix']);
        // $entity->booking_tags = isset($validatedData['booking_tags']) ? e($validatedData['booking_tags']) : null;


        $entity->save(); // Save the update


        return response()->json([
            'status' => true,
            'message' => 'Updated sucessfully',
        ], 200);


    }

    // Delete an entity
    public function destroy($id)
    {
        $entity = Entity::findOrFail($id);
        $entity->delete();

        return response()->json([
            'status' => true,
            'message' => 'Entity deleted',
        ], 200);


    }
}
