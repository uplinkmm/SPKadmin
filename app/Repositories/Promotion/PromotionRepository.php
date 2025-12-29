<?php
namespace App\Repositories\Promotion;

use App\Models\GamePromotion;
use App\Models\ReferralPromotion;
use App\Models\UserPromotion;
use App\Repositories\Promotion\PromotionInterface;
use Illuminate\Support\Facades\DB;


class PromotionRepository implements PromotionInterface
{
    public function index($request)
    {
        $perPage = $request->per_page ?? config('common.per_page');
        $promotion = GamePromotion::orderBy('id', 'desc')
            ->paginate($perPage);
        return $promotion;
    }

    public function storeGamePromotion($request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $data['start_date'] = convertDateTimeFormat($request->start_date);
            $data['end_date'] = convertDateTimeFormat($request->end_date);
            $exists = GamePromotion::where(function ($q) use ($data) {
                $q->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($q2) use ($data) {
                        $q2->where('start_date', '<=', $data['start_date'])
                            ->where('end_date', '>=', $data['end_date']);
                    });
            })
                ->where('is_active', 1)
                ->exists();
            if ($exists) {
                ResponseMessage('Promotion exists within the selected duration.', 422);
            }
            $promotion = GamePromotion::create($data);
            DB::commit();
            return $promotion;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function userBonusList($request)
    {
        $perPage = $request->per_page ?? config('common.per_page');
        $promotion = UserPromotion::orderBy('id', 'desc')
            ->paginate($perPage);
        return $promotion;
    }

    public function storeUserBonus($request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $data['start_date'] = convertDateTimeFormat($request->start_date);
            $data['end_date'] = convertDateTimeFormat($request->end_date);
            $exists = UserPromotion::where(function ($q) use ($data) {
                $q->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($q2) use ($data) {
                        $q2->where('start_date', '<=', $data['start_date'])
                            ->where('end_date', '>=', $data['end_date']);
                    });
            })
                ->where('is_active', 1)
                ->exists();
            if ($exists) {
                ResponseMessage('Promotion exists within the selected duration.', 422);
            }
            $promotion = UserPromotion::create($data);
            DB::commit();
            return $promotion;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function referralPromotionList($request)
    {
        $perPage = $request->per_page ?? config('common.per_page');
        $promotion = ReferralPromotion::orderBy('id', 'desc')
            ->paginate($perPage);
        return $promotion;
    }

    public function storeReferralPromotion($request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $data['start_date'] = convertDateTimeFormat($request->start_date);
            $data['end_date'] = convertDateTimeFormat($request->end_date);
            $exists = ReferralPromotion::where(function ($q) use ($data) {
                $q->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($q2) use ($data) {
                        $q2->where('start_date', '<=', $data['start_date'])
                            ->where('end_date', '>=', $data['end_date']);
                    });
            })
                ->where('is_active', 1)
                ->exists();
            if ($exists) {
                ResponseMessage('Referral Promotion exists within the selected duration.', 422);
            }
            $promotion = ReferralPromotion::create($data);
            DB::commit();
            return $promotion;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}