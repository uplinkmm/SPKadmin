<?php

namespace App\Repositories\Draw;

use Exception;

use App\Models\Draw;
use App\Models\Game;

use App\Models\Prize;

use App\Models\GameSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Draw\DrawInterface;
use Google\Service\Games;

class DrawRepository implements DrawInterface
{
    private $select=['id','name','photo', 'opening_date_time', 'closing_date_time', 'lottery_date_time','photo','price','limitation_quantity','description','terms_and_condition','game_id','is_active'];
    public function list($request){
        $game=Game::where('type','draw')->first();
        if(!$game){
            ResponseMessage('Game Not found',404);
        }
        $perPage = $request->per_page ?? config('common.per_page');
        $searchInput=$request->search_input;
        $drawList=GameSetting::where('game_id',$game->id)
        ->select($this->select)
        ->paginate($perPage);
        return $drawList;
    }

    public function create($request){
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $game=Game::where('type','draw')->first();
            if(!$game){
                ResponseMessage('Game Not found',404);
            }
            $gameId= $game->id;
            if ($request->has('photo')) {
                $path = $request->file('photo')->store('public/img');
                $imageUrl = Storage::url($path);
                $data['photo'] = $imageUrl;
            }

            $data['game_id']=$gameId;
            $gameSetting = GameSetting::create(
                // ['id' => $data['id']],
                $data
            );
            $json_decoded=json_decode($data['prizes'],true);
            foreach($json_decoded as $decodedData){
                $prize=Prize::create([
                    'name'=>$decodedData['name'],
                    'prize'=>$decodedData['prize'],
                ]);
            }
            DB::commit();
            return $gameSetting;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}