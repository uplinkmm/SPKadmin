<?php

namespace App\Http\Controllers\API;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    //
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $feedback=Feedback::orderBy('id','desc')->paginate($perPage);
        if($feedback){
            ResponseData($feedback);
        }
        ResponseMessage('Data not found',404);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data=$request->all();
            $data['customer_id']=UserData()->id;
            $feedback = Feedback::Create(
                $data
            );
            DB::commit();
            ResponseData($feedback);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function destroy(Feedback $feedback){
        $feedback->delete();
        ResponseMessage('Feedback delete successfully',404);
    }
}
