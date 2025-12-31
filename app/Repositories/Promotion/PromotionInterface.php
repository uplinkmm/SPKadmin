<?php

namespace App\Repositories\Promotion;


interface PromotionInterface
{
    public function index($request);
    public function storeGamePromotion($request);

    public function userBonusList($request);
    public function storeUserBonus($request);
    public function referralPromotionList($request);

    public function storeReferralPromotion($request);

}