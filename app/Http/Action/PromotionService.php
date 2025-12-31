<?php
namespace App\Http\Action;

use App\Models\Customer;
use App\Models\GamePromotion;
use App\Models\UserPromotion;
use App\Enums\TransactionName;
use Illuminate\Support\Carbon;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PromotionService
{
    use WalletTransactionCommon;

    public function activePromotion()
    {
        $date = Carbon::now();
        $activePromotions = GamePromotion::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('is_active', 1) // if you have is_active column
            ->first();
        return $activePromotions;
    }

    public function activeNewUserPromotion()
    {
        $date = Carbon::now();
        $activePromotions = UserPromotion::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('is_active', 1) // if you have is_active column
            ->first();
        return $activePromotions;
    }

    public function claimNewUserPromotion($customer)
    {
        $activePromotion = $this->activeNewUserPromotion();
        if ($activePromotion && $customer->is_verified) {
            $topupCount = $customer->confirmedTopupTransactions()->count();
            if ($topupCount === 0) {
                $activePromotion->customers()->syncWithoutDetaching([$customer->id]);
                $activePromotion->customer_id = $customer->id;
                $this->actionOfWalletTransaction($activePromotion, $activePromotion->amount, 'in');
            }

        }
    }

    public function claimPromotion(Customer $customer, $amount)
    {
        $activePromotion = $this->activePromotion();
        if ($activePromotion) {
            if ($amount >= $activePromotion->deposit_amount) {
                $activePromotion->customers()->attach($customer->id);
                Log::info('Referral promotion is active.', [
                    'amount' => $activePromotion->promotion_amount,
                ]);
                (new WalletService)->deposit($customer, $activePromotion->promotion_amount, TransactionName::Promotion);

            }
        }
    }


}