<?php

namespace App\Repositories\Draw;

use Exception;

use Carbon\Carbon;
use App\Models\Draw;

use App\Models\Game;

use App\Models\Prize;
use Google\Service\Games;
use App\Models\GameSetting;
use Illuminate\Http\Request;
use App\Models\LotteryNumber;
use App\Models\PrizeItemImage;
use App\Models\LotteryPromotion;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LotteryWinningNumber;
use App\Models\LotteryPromotionTicket;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Draw\DrawInterface;

class DrawRepository implements DrawInterface
{
    use SendNotification;
    private $select = ['id', 'name', 'photo', 'opening_date_time', 'closing_date_time', 'lottery_date_time', 'photo', 'price', 'limitation_quantity', 'description', 'terms_and_condition', 'game_id', 'is_active'];
    public function list($request)
    {
        $game = Game::where('type', 'draw')->first();
        if (!$game) {
            ResponseMessage('Game Not found', 404);
        }
        $perPage = $request->per_page ?? config('common.per_page');
        $searchInput = $request->search_input;
        $now = Carbon::now();
        $drawListQuery = GameSetting::with('prizes.prizes_images')->where('game_id', $game->id)
            ->select($this->select)
            ->latest();
        if (isset($request->page)) {
            return $drawListQuery->paginate($perPage);
        }
        return $drawListQuery->where('is_active', 1)
            ->where(function ($query) use ($now) {
                $query->where('opening_date_time', '<=', $now)
                    ->where('closing_date_time', '>=', $now);
            })
            ->get();
    }

    public function toggleIsActive($request)
    {
        $draw = GameSetting::find($request->id);
        if ($draw) {
            $draw->is_active = (bool) $request->is_active;
            $draw->save();
            return $draw;
        }
        ResponseMessage('Draw not found', 400);
    }


    public function prizeByGame($gameSettingId)
    {
        return Prize::where('game_setting_id', $gameSettingId)->get();
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
                    if (isset($decodedData['id']) && $decodedData['is_delete']) {
                        $prize = Prize::find($decodedData['id']);
                        foreach ($prize->prizes_images as $oldImage) {
                            $oldPhotoPath = str_replace('/storage/', 'public/', $oldImage->name);
                            Storage::delete($oldPhotoPath);
                        }
                        $prize->prizes_images()->delete();
                        $prize->delete();
                    } else {
                        $prize = Prize::updateOrCreate(
                            ['id' => $decodedData['id']],
                            [
                                'name' => $decodedData['name'],
                                'prize' => $decodedData['prize'],
                                'game_setting_id' => $gameSetting->id
                            ]
                        );
                        if (isset($request->id) && isset($decodedData['photo'])) {
                            if (count($decodedData['photo']) > 0) {
                                foreach ($prize->prizes_images as $oldImage) {
                                    $oldPhotoPath = str_replace('/storage/', 'public/', $oldImage->name);
                                    Storage::delete($oldPhotoPath);
                                }
                                $prize->prizes_images()->delete();
                            }
                        }
                        if (isset($decodedData['photo']) && is_array($decodedData['photo']) && count($decodedData['photo']) > 0) {
                            foreach ($decodedData['photo'] as $photoName) {
                                if (isset($uploadedPrizePhotos[$photoName])) {
                                    $prizeItemImage = PrizeItemImage::create(
                                        [
                                            'name' => $uploadedPrizePhotos[$photoName],
                                            'prize_id' => $prize->id,
                                        ]
                                    );
                                }
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


    public function lotteryPromotionList($request)
    {
        $perPage = $request->per_page ?? config('common.per_page');
        $drawListQuery = LotteryPromotionTicket::with(['lottery_promotion.game_setting'])
            ->latest();
        if (isset($request->page)) {
            return $drawListQuery->paginate($perPage);
        }
        return $drawListQuery->get();
    }
    public function lotteryPromotionCreate($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $gameSettingId = $request->game_setting_id;
            $exists = LotteryPromotion::where('game_setting_id', $gameSettingId)->exists();
            if ($exists) {
                ResponseMessage('Promotion already exist', 419);
            }
            $lotteryPromotion = LotteryPromotion::create(
                $data
            );
            $json_decoded = json_decode($data['lottery_promotion_tickets'], true);
            foreach ($json_decoded as $decoded) {
                $lotteryPromotionTicket = LotteryPromotionTicket::create([
                    'qty' => $decoded['qty'],
                    'additional_qty' => $decoded['additional_qty'],
                    'lottery_promotion_id' => $lotteryPromotion->id,

                ]);
            }
            DB::commit();
            return $lotteryPromotion;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }

    }
    public function lotteryPromotiondetail($id)
    {

    }

    public function lotteryWinningNumberList($request)
    {
        $perPage = $request->per_page ?? config('common.per_page');
        return LotteryWinningNumber::with('prize.game_setting')
            ->when($request->game_setting_id, function ($query) use ($request) {
                $query->whereHas('prize', function ($q) use ($request) {
                    $q->where('game_setting_id', $request->game_setting_id);
                });
            })
            ->latest()
            ->paginate($perPage);
    }
    public function lotteryWinningNumberCreate($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }

            $data['game_setting_id'] = $request->game_setting_id;
            $existing = LotteryWinningNumber::join('prizes', 'lottery_winning_numbers.prize_id', '=', 'prizes.id')
                ->where('prizes.game_setting_id', $request->game_setting_id)
                ->exists();


            if ($existing) {
                return ResponseMessage('Winning Number already exists for this draw', 419);
            }
            $data['created_by'] = UserData()->id;
            $prizes = json_decode($data['prizes'], true);
            if (empty($prizes) || !is_array($prizes)) {
                return ResponseMessage('Prize is empty', 419);
            }
            $insertData = [];
            $now = now();

            foreach ($prizes as $prize) {
                $number = trim($prize['number'] ?? '');

                // Validate number (must be 3 digits)
                if (!ctype_digit($number) || strlen($number) !== 3) {
                    return ResponseMessage('Winning Number format is invalid', 419);
                }

                $insertData[] = [
                    'id' => $data['id'],
                    'prize_id' => $prize['prize_id'],
                    'number' => $number,
                    'created_by' => UserData()->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            LotteryWinningNumber::insert($insertData);
            DB::commit();
            return $insertData;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function approveLotteryWinnigNumber($request)
    {
        DB::beginTransaction();
        try {
            $lotteryWinningNumber = LotteryWinningNumber::find($request->id);
            if ($lotteryWinningNumber->is_approve == 1) {
                ResponseMessage('Winning number is already approved', 419);
            }
            // dd($lotteryWinningNumber->prize->game_setting());
            $gameSetting = $lotteryWinningNumber->prize->game_setting()->first();
            $userId = UserData()->id;
            $lotteryWinningNumber->approved_by = $userId;
            $lotteryWinningNumber->approved_at = now();
            $lotteryWinningNumber->is_approve = 1;
            $lotteryWinningNumber->updated_by = $userId;
            $lotteryWinningNumber->save();
            if (!$gameSetting) {
                ResponseMessage('Game Setting is required', 419);
            }
            // $lotteryNumber = LotteryNumber::join('lotteries', 'lottery_numbers.lottery_id', 'lotteries.id')
            //     ->where('lotteries.game_setting_id', $gameSetting->id)
            //     ->where('number', $lotteryWinningNumber->number)
            //     ->first();
            $lotteryNumber = LotteryNumber::whereHas('lottery', function ($q) use ($gameSetting) {
                $q->where('game_setting_id', $gameSetting->id);
            })
                ->where('number', $lotteryWinningNumber->number)
                ->with('lottery.customer') // eager load relationships
                ->first();

            if ($lotteryNumber) {
                $customer = $lotteryNumber->lottery->customer;
                if (!$customer->has_won) {
                    $customer->has_won = true;
                    $customer->save();
                }
                $data['title'] = 'Lottery Win!!';
                // $data['body'] = 'You number ' . $bettingNumber->number . ' is winning !! ';
                $data['body'] = 'သင်သည် ပေါက်မဲနံပတ် ' . $lotteryNumber->number . ' နှင့် ' . $lotteryWinningNumber->prize->name . ' ' . $lotteryWinningNumber->prize->prize . ' ကိုပိုင်ဆိုင်ပါသည်';
                $data['date_time'] = now();
                // $lotteryNumberUpdate = LotteryNumber::find($lotteryNumber->id);
                // $lotteryNumberUpdate->lottery_winning_number_id = $lotteryWinningNumber->id;
                // $lotteryNumberUpdate->save();
                $lotteryNumber->update([
                    'lottery_winning_number_id' => $lotteryWinningNumber->id,
                ]); 
                // $lotteryNumber->update(['lottery_winning_number_id', $lotteryWinningNumber->id]);
                $this->send($lotteryWinningNumber, $customer, $data);
                Log::info('Send Notification Successfuly');

            }
            DB::commit();
            ResponseMessage('Approve Successfully', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function editLotteryWinnigNumber($request)
    {
        DB::beginTransaction();
        try {
            $lotteryWinningNumber = LotteryWinningNumber::find($request->id);
            if ($lotteryWinningNumber->is_approve == 1) {
                ResponseMessage('Winning number cannot edit after approved', 419);
            }
            $userId = UserData()->id;
            $lotteryWinningNumber->number = $request->number;
            $lotteryWinningNumber->updated_by = $userId;
            $lotteryWinningNumber->save();
            DB::commit();
            ResponseMessage('Edit Successfully', 200);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    public function lotteryWinningUserList($request)
    {

        $gameSettingId = $request->game_setting_id;
        $perPage = $request->per_page ?? 20;
        $lotteryWinningNumber = LotteryWinningNumber::where('is_approve', 1)
            ->with(['approved_by', 'lottery_number.lottery.customer', 'prize.game_setting']) // eager load lottery_numbers, even if null
            ->when($gameSettingId, function ($query) use ($gameSettingId) {
                $query->whereHas('prize', function ($q) use ($gameSettingId) {
                    $q->where('game_setting_id', $gameSettingId);
                });
            })
            ->paginate($perPage);
        return $lotteryWinningNumber;

    }
    public function lotteryBettingList($request)
    {
        if (!isset($request->game_setting_id) && $request->game_setting_id == null) {
            ResponseMessage('Game setting is required', 419);
        }
        $perPage = $request->per_page ?? 20;
        $gameSettingId = $request->game_setting_id;
        $lotteryBettingList = LotteryNumber::with(['lottery.game_setting', 'lottery.customer'])->orderByDesc('id')
            ->select(['id', 'amount', 'lottery_id', 'number'])
            ->whereHas('lottery', function ($q) use ($gameSettingId) {
                $q->where('game_setting_id', $gameSettingId);
            })
            ->paginate($perPage);
        return $lotteryBettingList;
    }

    public function lotteryWinningNumberdetail($id)
    {

    }
}