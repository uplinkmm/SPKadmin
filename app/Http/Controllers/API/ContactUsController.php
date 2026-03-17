<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = ContactUs::latest()->get();
        ResponseData($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'facebook_link' => 'required',
            'viber_number'  => 'required|string|max:20',
            'phone_number'  => 'required|string|max:20|unique:contact_us,phone_number,' . $request->id,
            'telegram_link' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $data = $request->all();

            $contactUs = ContactUs::updateOrCreate([
                'id' => $data['id'] ?? null
            ], $data);
            DB::commit();
            \ResponseData($contactUs);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $contactUs = ContactUs::findOrFail($id);
        \ResponseData($contactUs);
    }
}
