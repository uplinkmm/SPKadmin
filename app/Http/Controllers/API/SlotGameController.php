<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\GameList;
use App\Models\Admin\GameType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

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

    public function toggleHotGameStatus(Request $request){
        $gameId=$request->id;
        if (toggleColumn(GameList::class, $gameId, 'hot_status')) {
            ResponseMessage('Hot game status toggled successfully.',200);
        } else {
            ResponseMessage('Game not found.',404);
        }
    }

    public function toggleGame(Request $request){
        $gameId=$request->id;
        if (toggleColumn(GameList::class, $gameId, 'status')) {
            ResponseMessage('Game status toggled successfully.',200);
        } else {
            ResponseMessage('Game not found.',404);
        }
    }

    public function checkGameList(){
        $productIds=[
            '1002',
        '1004',
        '1006',
        '1009',
        '1013',
        '1022',
        '1041',
        '1050',
        '1077',
        '1084',
        '1085',
        '1091'];
        $json = File::get(base_path('app/Console/Commands/data/GameList.json'));
        $data = json_decode($json);

        // $filteredGames = array_filter($data->ProviderGames, function ($game) use ($productIds) {
        //     return !in_array($game->ProductID, $productIds);
        // });
        
        // // Extract only GameType and ProductID
        // $result = [];
        // foreach ($filteredGames as $game) {
        //     $key = $game->GameType . '-' . $game->ProductID; // Create unique key
        //     $result[$key] = [
        //         'GameType' => $game->GameType,
        //         'ProductID' => $game->ProductID
        //     ];
        // }
        
        // // Convert associative array back to indexed array
        // $uniqueGames = array_values($result);
        foreach ($data->ProviderGames as $obj) {
            $product=Product::where('code',$obj->ProductId)->first();

            $gameType=GameType::where('code',$obj->GameType)->first();

            if($gameType && $product){
                GameList::create([
                    'code' => $obj->GameCode,
                    'name' => $obj->GameName,
                    'game_type_id' => $gameType->id,
                    'product_id' => $product->id,
                    'image_url' => $obj->ImageUrl,
                ]);
            }
        }
    }
}
