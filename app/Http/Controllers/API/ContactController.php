<?php

namespace App\Http\Controllers\API;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactCreateRequest;

class ContactController extends Controller
{
    //

    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $contact=Contact::orderBy('id','desc')->paginate($perPage);
        if($contact){
            ResponseData($contact);
        }
        ResponseMessage('Data not found',404);
    }

    public function store(ContactCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $data=$request->all();
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $contact = Contact::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            // return $customer;
            DB::commit();
            ResponseData($contact);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function show(Contact $contact)
    {
        ResponseData($contact);
    }
}
