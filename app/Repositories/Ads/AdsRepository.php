<?php

namespace App\Repositories\Ads;

use App\Models\Ads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdsRepository implements AdsInterface
{
    public function list($request)
    {
        $perPage = $request->per_page ?? config('common.per_page');
        return Ads::orderBy("id", "desc")->paginate($perPage);
    }

    public function detail($id)
    {
        $ads=Ads::find($id);
        if($ads){
            return $ads;
        }
        responseMessage('data not found',404);
    }

    public function updateOrCreate($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            if ($request->has('photo')) {
                $path = $request->file('photo')->store('public/img');
                $imageUrl = Storage::url($path);
                $data['photo'] = $imageUrl;
            }
            $ads = Ads::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            return $ads;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}