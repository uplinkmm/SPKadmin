<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Draw\DrawInterface;

class LotteryPromotionController extends Controller
{
    //
    private $drawRepo;
    public function __construct(DrawInterface $repo){
        $this->drawRepo=$repo;
    }

    public function index(Request $request){
        $data=$this->drawRepo->lotteryPromotionList($request);
        ResponseData($data);
    }

    public function create(Request $request){
        $data=$this->drawRepo->lotteryPromotionCreate($request);
        ResponseData($data);
    }

    public function detail($id){
        $data=$this->drawRepo->lotteryPromotiondetail($id);
        ResponseData($data);
    }
}
