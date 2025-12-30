<?php
namespace App\Http\Action;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\Relation;

class Common{

    public function getWalletId($customerId){
        $prefix = null;
        if($customerId < 10){
            $prefix = sprintf('WL000%d', $customerId);
        }
        else if($customerId > 10 && $customerId < 100){
            $prefix = sprintf('WL00%d', $customerId);
        }
        else if($customerId > 100 && $customerId < 1000){
            $prefix = sprintf('WL0%d', $customerId);
        }
        else{
            $prefix = 'WL' . $customerId;
        }
        $walletId = $prefix . now()->format('Ymd');
        return $walletId;
    }

    public function toggleisActive($data)
    {
        DB::beginTransaction();
        try {
            $type = strtolower($data->type); // e.g. "agent"


            $modelClass = Relation::getMorphedModel($type);

            if (!$modelClass) {
                ResponseMessage('Invalid type provided', 400);
            }

            $record = $modelClass::find($data->id);

            if (!$record) {
                ResponseMessage('Data not found', 404);
            }
            if ($type === 'game_promotion' || $type === 'user_promotion') {

                if (!$record->is_active) {
                    // Check overlapping active promotions
                    $exists = $modelClass::where('id', '!=', $record->id) // exclude current record
                        ->where(function ($q) use ($record) {
                            $q->whereBetween('start_date', [$record->start_date, $record->end_date])
                                ->orWhereBetween('end_date', [$record->start_date, $record->end_date])
                                ->orWhere(function ($q2) use ($record) {
                                    $q2->where('start_date', '<=', $record->start_date)
                                        ->where('end_date', '>=', $record->end_date);
                                });
                        })
                        ->where('is_active', 1)
                        ->exists();

                    if ($exists) {
                        return ResponseMessage('Another active promotion already exists in this date range.', 422);
                    }
                }
            }

            // if ($data->has('is_active')) {
            //     $record->is_active = (int) $data->is_active;
            // } else {
            //     // or toggle automatically if not provided
            //     $record->is_active = $record->is_active ? 0 : 1;
            // }
            $record->is_active = $record->is_active ? 0 : 1;
            $record->save();
            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}