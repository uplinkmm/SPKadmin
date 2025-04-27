<?php

namespace App\Repositories\Draw;

use Exception;

use App\Models\Draw;
use App\Models\Game;

use App\Models\Prize;

use Google\Service\Games;
use App\Models\GameSetting;
use Illuminate\Http\Request;
use App\Models\PrizeItemImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Draw\DrawInterface;

class DrawRepository implements DrawInterface
{
    private $select = ['id', 'name', 'photo', 'opening_date_time', 'closing_date_time', 'lottery_date_time', 'photo', 'price', 'limitation_quantity', 'description', 'terms_and_condition', 'game_id', 'is_active'];
    public function list($request)
    {
        $game = Game::where('type', 'draw')->first();
        if (!$game) {
            ResponseMessage('Game Not found', 404);
        }
        $perPage = $request->per_page ?? config('common.per_page');
        $searchInput = $request->search_input;
        $drawList = GameSetting::with('prizes.prizes_images')->where('game_id', $game->id)
            ->select($this->select)
            ->paginate($perPage);
        return $drawList;
    }

    public function create($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $game = Game::where('type', 'draw')->first();
            if (!$game) {
                ResponseMessage('Game Not found', 404);
            }
            $gameId = $game->id;
            if ($request->has('photo')) {
                $path = $request->file('photo')->store('public/img');
                $imageUrl = Storage::url($path);
                $data['photo'] = $imageUrl;
            }

            $data['game_id'] = $gameId;
            $gameSetting = GameSetting::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            if ($gameSetting) {
                $json_decoded = json_decode($data['prizes'], true);
                $uploadedPrizePhotos = [];
                if ($request->hasFile('prize_photos')) {
                    foreach ($request->file('prize_photos') as $photo) {
                        $originalName = $photo->getClientOriginalName();
                        $path = $photo->store('public/img');
                        $uploadedPrizePhotos[$originalName] = Storage::url($path);
                    }
                }
                foreach ($json_decoded as $decodedData) {
                    if (!isset($decodedData['id'])) {
                        $decodedData['id'] = null;
                    }
                    $prize = Prize::updateOrCreate(
                        ['id' => $decodedData['id']],
                        [
                            'name' => $decodedData['name'],
                            'prize' => $decodedData['prize'],
                            'game_setting_id' => $gameSetting->id
                        ]
                    );
                    // dd($decodedData['photo']);
                    // if (!isset($request->id)&&isset($decodedData['photo']) && is_array($decodedData['photo'])) {
                    if(isset($request->id) && isset($decodedData['photo'])){
                        if(count($decodedData['photo'])>0){
                            foreach($prize->prizes_images as $oldImage){
                                $oldPhotoPath = str_replace('/storage/', 'public/', $oldImage->name);
                                Storage::delete($oldPhotoPath);
                            }
                            $prize->prizes_images()->delete();
                        }
                    }
                    if (isset($decodedData['photo']) && is_array($decodedData['photo']) && count($decodedData['photo'])>0 ) {
                        foreach ($decodedData['photo'] as $photoName) {
                            if (isset($uploadedPrizePhotos[$photoName])) {
                                $prizeItemImage=PrizeItemImage::create(
                                    [
                                    'name' => $uploadedPrizePhotos[$photoName],
                                    'prize_id'=>$prize->id,
                                ]);
                            }
                        }
                    }
                }
            }
            DB::commit();
            return $gameSetting;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detail($id)
    {
        $gameSetting = GameSetting::with('prizes.prizes_images')->select($this->select)->find($id);
        if (!$gameSetting) {
            ResponseMessage('Draw not found', 404);
        }
        return $gameSetting;
    }
}