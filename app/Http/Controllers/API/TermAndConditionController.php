<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\TermAndCondition;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TermAndConditionController extends Controller
{
    //
    public function index(Request $request)
    {
        $termAndCondition=TermAndCondition::orderBy('id','desc')->get();
        ResponseData($termAndCondition);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data=$request->all();
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $termAndCondition = TermAndCondition::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            // return $customer;
            DB::commit();
            ResponseData($termAndCondition);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function show(TermAndCondition $termAndCondition)
    {
        ResponseData($termAndCondition);
    }
}
