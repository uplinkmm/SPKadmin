<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DrawCreateRequest;
use App\Repositories\Draw\DrawInterface;
use Google\Rpc\Context\AttributeContext\Response;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    //
    private $drawRepo;
    public function __construct(DrawInterface $repo){
        $this->drawRepo=$repo;
    }

    public function index(Request $request){
        $data=$this->drawRepo->list($request);
        ResponseData($data);
    }

    public function create(DrawCreateRequest $request){
        $data=$this->drawRepo->create($request);
        ResponseData($data);
    }

    public function detail($id){
        $data=$this->drawRepo->detail($id);
        ResponseData($data);
    }

    public function lotteryBettingList(Request $request){
        $data=$this->drawRepo->lotteryBettingList($request);
        ResponseData($data);
    }

    public function prizeByGame(Request $request){
        if(!isset($request->game_setting_id) || (isset($request->game_setting_id) && $request->game_setting_id==null)){
            ResponseMessage('Game Setting ID is required',419);
        }
        $data=$this->drawRepo->prizeByGame($request->game_setting_id);
        ResponseData($data);
    }

    public function toggleIsActive(Request $request){
        $data= $this->drawRepo->toggleIsActive($request);
        ResponseData($data);
    }
}
