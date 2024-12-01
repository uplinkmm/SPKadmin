<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Game\GameInterface;

class GameController extends Controller
{
    private $gameRepo;
    public function __construct(GameInterface $repo)
    {
        $this->gameRepo = $repo;
    }
    public function index(Request $request)
    {
        $games = Game::orderBy('id', 'asc')->get();
        ResponseData($games);
        // $game= Game::find($request->game_id);
    }

    public function game_list(Request $request){
        $current_time = Carbon::now()->format('H:i:s');
        if($request->game_id==1){
            $game = Game::with(['twodSettings' => function($q) use ($current_time) {
                $q
                ->where('is_active', 1)
                  ->where('opening_time', '<=', $current_time)
                  ->where('closing_time', '>=', $current_time);
            }])
            ->find($request->game_id);
        }
        if($request->game_id==2){
            $game=Game::with(['threedSetting'])->find($request->game_id);
        }
        
        if($game){
            ResponseData($game);
        }
        ResponseMessage('Game Not Found',404);
    }

    public function store(Request $request)
    {
        $data = $this->gameRepo->updateOrCreate($request);
        ResponseData($data);
    }

    public function storeGameSetting(Request $request)
    {
        $data = $this->gameRepo->updateOrCreateGameSetting($request);
        ResponseData($data);
    }

    public function toggleIsActive(Request $request)
    {
        $data = $this->gameRepo->toggleIsActive($request);
        ResponseData($data);
    }

    public function get3DGameSetting(Request $request){
        $data = $this->gameRepo->gameSetting(2);
        ResponseData($data);
    }
}
