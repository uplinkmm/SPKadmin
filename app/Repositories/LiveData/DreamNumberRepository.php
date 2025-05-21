<?php

namespace App\Repositories\LiveData;

use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\DreamNumber;

use App\Repositories\LiveData\DreamNumberRepositoryInterface;

class DreamNumberRepository implements DreamNumberRepositoryInterface
{
    public function bulkCreate(array $dreamNumbers)
    {
        $success = false;
        DB::beginTransaction();
        try{
            foreach($dreamNumbers as $dreamNumber){

                DreamNumber::create([
                    'name' => $dreamNumber['name'],
                    'number' => $dreamNumber['number'],
                    'image_url' => $dreamNumber['image_url']
                ]);
            }
            DB::commit();
            $success = true;
        }
        catch(Exception $e){
            DB::rollBack();
        }

        return $success;
    }

    public function getDreamNumbers(Request $request)
    {
        $dreamNumbers = null;
        if($request->search){
            $dreamNumbers = DreamNumber::where('name', 'like', "%{$request->search}%")
            ->orWhere('number', 'like', "%{$request->search}%")
            ->orderByRaw("
                CASE
                    WHEN name = ? THEN 1
                    WHEN name LIKE ? THEN 2
                    ELSE 3
                END",
                [$request->search, "%{$request->search}%"]
            )
            ->select(["id", "name", "number", "image_url"])
            ->get();
        }
        else{
            $dreamNumbers = DreamNumber::select(["id", "name", "number", "image_url"])
            ->get();
        }

        return $dreamNumbers;
    }
}
