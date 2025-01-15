<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\GameList;
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

    public function allGameList(Request $request){
        $gameTypeId=$request->game_type_id;
        $productId=$request->product_id;
        $searchInput=$request->search_input;
        $perPage = $request->per_page ?? config('common.per_page');
        $gameLists = GameList::with('product')
        // ->where('status', 1)
        ->when(strlen($searchInput) >= 4,function($q)use($searchInput){
            $q->where('name','LIKE',$searchInput.'%');
        })
        ->when($gameTypeId,function($q)use($gameTypeId){
            $q->where('game_type_id',$gameTypeId);
        })
        ->when($productId,function($q)use($productId){
            $q->where('product_id',$productId);
        })
        ->paginate($perPage);
        ResponseData($gameLists,200);
    }

    public function toggleGame(Request $request){
        $gameId=$request->id;
        if (toggleColumn(GameList::class, $gameId, 'status')) {
            ResponseMessage('Game status toggled successfully.',200);
        } else {
            ResponseMessage('Game not found.',404);
        }
    }
}
