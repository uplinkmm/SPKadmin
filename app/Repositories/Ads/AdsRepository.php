<?php

namespace App\Repositories\Ads;

use App\Models\Ads;
use App\Models\Customer;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdsRepository implements AdsInterface
{
    use SendNotification;
    public function list($request)
    {
        $perPage = $request->per_page ?? config('common.per_page');
        $searchInput=$request->search_input;
        return Ads::orderBy("id", "desc")
        ->when($searchInput,function($q)use($searchInput){
            $q->where(function ($query) use ($searchInput) {
                $query->where('name','LIKE','%' .$searchInput .'%');
            });
        })
        ->paginate($perPage);
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
            $customers=Customer::all();
            if(!isset($request->id)&& $request->type=='promotion'){
                $data['title'] = $ads->name;
                // $data['body'] = 'You number ' . $bettingNumber->number . ' is winning !! ';
                $data['body'] = $ads->body;
                $data['date_time'] = now();
                $this->send($ads, collect($customers), $data);
            }
            DB::commit();
            return $ads;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function delete($ads)
    {
        DB::beginTransaction();
        try {
            $ads = Ads::find($ads);
            if (!$ads) {
                ResponseMessage('Ads not found', 419);
            }
            if ($ads->photo) {
                // Remove the "/storage/" prefix to get the relative path used in storage
                $imagePath = str_replace('/storage/', 'public/', $ads->photo);
                // Check if the file exists before attempting to delete
                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
                }
            }
            $ads->delete();
            DB::commit();
            ResponseMessage('Ads delete successfully');
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}