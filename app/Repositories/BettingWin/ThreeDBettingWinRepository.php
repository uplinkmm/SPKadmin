<?php

namespace App\Repositories\BettingWin;

use Exception;

use App\Models\Game;
use App\Models\BettingWin;

use App\Models\GameSetting;

use Illuminate\Http\Request;
use App\Models\BettingNumber;
use App\Models\TwistWinNumber;
use App\Traits\CheckBettingWin;
use App\Traits\SendNotification;

use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Action\WalletTransactionCommon;

class ThreeDBettingWinRepository implements ThreeDBettingWinRepositoryInterface
{
    use WalletTransactionCommon, SendNotification, CheckBettingWin;

    public function listBettingWins(Request $request)
    {
        $gameId = 2;
        $gameSettingId = $request->game_setting_id;
        $perPage = $request->per_page ?? config('common.per_page');
        $searchInput = $request->search_input;
        $from_date = isset($request->from_date) ? convertDateFormat($request->from_date) : null;
        $to_date = isset($request->to_date) ? convertDateFormat($request->to_date) : null;
        $bettingWins = BettingWin::with(['twistWinNumbers', 'game_setting'])
            // join('game_settings','betting_wins.game_setting_id','game_settings.id')
            ->orderBy('betting_wins.id', 'desc')
            ->where('game_setting_id', '>=', 3)
            ->when($gameSettingId, function ($q) use ($gameSettingId) {
                $q->where('game_setting_id', $gameSettingId);
            })
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('betting_wins.number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(betting_wins.date_time)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('betting_wins.date_time', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('betting_wins.date_time', [now(), $to_date]);
            });
        // ->where('game_settings.game_id',$gameId);
        // dd($bettingWins->get());
        $bettingWins = isset($request->per_page) ? $bettingWins->paginate($perPage) : $bettingWins->get();
        ResponseData($bettingWins);
    }

    public function createBettingWin(array $data, array $twistNumbers)
    {
        $game = Game::with('threedSetting')->find(2);
        if (!$game->threedSetting) {
            ResponseMessage('Game Setting is invalid', 419);
        }
        $data['game_setting_id'] = $game->threedSetting->id;
        $this->checkExistThreeDBettingWin($data);
        try {
            DB::beginTransaction();
            $bettingWin = BettingWin::create($data);
            foreach ($twistNumbers as $twistNumber) {
                TwistWinNumber::create([
                    'number' => $twistNumber,
                    'betting_win_id' => $bettingWin->id,
                    'created_by' => $bettingWin->created_by
                ]);
            }
            DB::commit();

            return $bettingWin;
        } catch (Exception $e) {
            DB::rollBack();

            ResponseMessage($e->getMessage(), 500);
        }
    }

    public function updateBettingWin(array $data, array $twistNumbers, BettingWin $bettingWin)
    {
        $game = Game::with('threedSetting')->find(2);
        $data['game_setting_id'] = $game->threedSetting->id;

        try {
            DB::beginTransaction();
            $bettingWin->update($data);
            $bettingWin->save();
            if (count($twistNumbers) > 0) {
                $oldTwistWinNumbers = TwistWinNumber::where('betting_win_id', $bettingWin->id)->get();
                foreach ($oldTwistWinNumbers as $oldTwistWinNumber) {
                    $oldTwistWinNumber->delete();
                }

                foreach ($twistNumbers as $twistNumber) {
                    TwistWinNumber::create([
                        'number' => $twistNumber,
                        'betting_win_id' => $bettingWin->id,
                        'created_by' => $bettingWin->created_by
                    ]);
                }
            }
            DB::commit();

            return $bettingWin->with('twistWinNumbers');

        } catch (Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
        }


        return $bettingWin;
    }

    old
    public function approveBettingWin($bettingWin)
    {
        if ($bettingWin->is_approved == 1) {
            ResponseMessage('The number is already approved for winning', 400);
        }

        try {
            DB::beginTransaction();

            $bettingWin->is_approved = 1;
            $bettingWin->approved_by = ApiUser()->id;
            $bettingWin->save();

            //original
            $bettingNumbers = BettingNumber::
                whereIn('betting_id', function ($query) use ($bettingWin) {
                    $query->select('id')->from('bettings')
                        ->where('game_id', 2)
                        ->where('game_setting_id', $bettingWin->game_setting_id);
                })
                ->where('betting_numbers.number', $bettingWin->number)
                ->with('betting')
                ->get();
            // dd($bettingNumbers);
            //end
            $customers = [];
            $walletTransactions = []; // Array to hold bulk data
            foreach ($bettingNumbers as $bettingNumber) {
                $customers[] = $bettingNumber->betting->customer;
                $bettingNumber->is_win = 1;
                $bettingNumber->save();
                $bettingNumber->customer_id = $bettingNumber->betting->customer_id;
                $amount = $bettingNumber->amount * $bettingNumber->betting_multiplier;

                //
                // Prepare data for bulk insert
                $walletTransactions[] = [
                    'date_time' => now(),
                    'amount' => (int) $amount,
                    'walletable_id' => $bettingNumber->id,
                    'walletable_type' => RelationMorphName($bettingNumber),
                    'action' => 'in',
                    'customer_id' => $bettingNumber->customer_id,
                ];
                // $this->actionOfWalletTransaction($bettingNumber, $amount, 'in');
            }
            if (!empty($walletTransactions)) {
                WalletTransaction::insert($walletTransactions);
            }

            #notification
            if ($bettingNumbers->isNotEmpty()) {
                $data['title'] = 'Betting Win!!';
                // $data['body'] = 'Your number ' . $bettingNumber->number . ' is winning !! ';
                $data['body'] = '3D ပေါက်ဂဏန်း ' . $bettingNumber->number . ' တွက်လျော်ကြေးငွေရှိပါသည် ';
                $data['date_time'] = now();
                $this->send($bettingWin, collect($customers), $data);
            }
            #end

            // $twistBettingNumbers = BettingNumber::whereIn('betting_id', function ($query) use ($bettingWin) {
            //     $query->select('id')->from('bettings')
            //         ->where('game_id', 2)
            //         ->where('game_setting_id', $bettingWin->game_setting_id);
            // })
            //     ->whereIn('number', function ($query) use ($bettingWin) {
            //         $query->select('number')->from('twist_win_numbers')
            //             ->where('betting_win_id', $bettingWin->id);
            //     })
            //     ->with('betting')->get();

            $twistBettingNumbers = BettingNumber::whereIn('betting_id', function ($query) use ($bettingWin) {
                $query->select('id')->from('bettings')
                    ->where('game_id', 2)
                    ->where('game_setting_id', $bettingWin->game_setting_id);
            })
                ->join('twist_win_numbers', 'betting_numbers.number', '=', 'twist_win_numbers.number') // Joining the twist_win_numbers table
                ->where('twist_win_numbers.betting_win_id', $bettingWin->id)
                ->with('betting')
                ->select('betting_numbers.*', 'twist_win_numbers.id as twist_win_id') // Selecting the twist_win_numbers.id
                ->get();
            $gameSetting = GameSetting::find($bettingWin->game_setting_id);
            $twistWalletTransactions=[];
            foreach ($twistBettingNumbers as $bettingNumber) {
                $bettingNumber->is_win = 1;
                $bettingNumber->is_twist = 1;
                $bettingNumber->betting_multiplier = $gameSetting->twist_multiplier;
                $bettingNumber->save();
                $twistCustomer = $bettingNumber->betting->customer;
                $bettingNumber->customer_id = $bettingNumber->betting->customer_id;
                $amount = $bettingNumber->amount * $bettingNumber->betting_multiplier;


                // $this->actionOfWalletTransaction($bettingNumber, $amount, 'in');
                $twistWalletTransactions[] = [
                    'date_time' => now(),
                    'amount' => (int) $amount,
                    'walletable_id' => $bettingNumber->id,
                    'walletable_type' => RelationMorphName($bettingNumber),
                    'action' => 'in',
                    'customer_id' => $bettingNumber->customer_id,
                ];


                $data['title'] = 'Betting Win!!';
                // $data['body'] = 'Your twist number ' . $bettingNumber->number . ' is winning !! ';
                $data['body'] = 'Your number ' . $bettingNumber->number . ' (Twist) is winning   !! ';
                $data['date_time'] = now();
                $twistWinNumber = TwistWinNumber::find($bettingNumber->twist_win_id);
                $this->send($twistWinNumber, collect([$twistCustomer]), $data);
            }
            if (!empty($twistWalletTransactions)) {
                WalletTransaction::insert($twistWalletTransactions);
            }
            DB::commit();
            ResponseMessage('The number and twist numbers have been approved for winning');
        } catch (Exception $e) {
            DB::rollBack();

            ResponseMessage($e->getMessage(), 500);
        }
    }


}
