<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\GameType;
use App\Http\Controllers\Controller;

class SlotGameController extends Controller
{
    //
    public function getGameTypeList(){
        $gameType = GameType::where('status', 1)
        ->get();
        ResponseData($gameType);
    }
    public function productListByGameType($gameTypeId){
        $productList=Product::where('status',1)
        ->where('game_list_status',1)
        ->whereHas('gameTypes', function ($query) use ($gameTypeId) {
            $query->where('game_type_id', $gameTypeId);
        })->get();
        ResponseData($productList);
    }
}
