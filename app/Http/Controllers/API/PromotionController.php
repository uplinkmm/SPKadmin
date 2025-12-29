<?php

namespace App\Http\Controllers\API;

use App\Http\Action\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GamePromotionRequest;
use App\Http\Requests\Admin\NewUserPromotionCreateRequest;
use App\Http\Requests\Admin\ReferralPromotionCreateRequest;
use App\Repositories\Promotion\PromotionInterface;
use Illuminate\Database\Eloquent\Relations\Relation;

class PromotionController extends Controller
{
    //
    private $promotinRepo;
    public function __construct(PromotionInterface $repo)
    {
        $this->promotinRepo = $repo;
    }
    public function index(Request $request)
    {
        $data = $this->promotinRepo->index($request);
        ResponseMessage($data);
    }
    public function storeGamePromotion(GamePromotionRequest $request)
    {
        
        $data = $this->promotinRepo->storeGamePromotion($request);
        ResponseMessage($data);
    }

    public function userBonusList(Request $request)
    {
        $data = $this->promotinRepo->userBonusList($request);
        ResponseMessage($data);
    }
    public function storeUserBonus(NewUserPromotionCreateRequest $request)
    {
        $data = $this->promotinRepo->storeUserBonus($request);
        ResponseMessage($data);
    }

    public function toggleIsActive(Request $request)
    {
        $data = (new Common())->toggleisActive($request);
        ResponseData($data);
    }

    public function referralPromotionList(Request $request)
    {
        $data = $this->promotinRepo->referralPromotionList($request);
        ResponseMessage($data);
    }

    public function storeReferralPromotion(ReferralPromotionCreateRequest $request)
    {
        $data = $this->promotinRepo->storeReferralPromotion($request);
        ResponseMessage($data);
    }

}
