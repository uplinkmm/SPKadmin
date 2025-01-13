<?php

namespace App\Repositories\BettingWin;

use Exception;

use App\Models\BettingWin;
use App\Models\GameSetting;

use Illuminate\Http\Request;

use App\Models\BettingNumber;
use App\Traits\CheckBettingWin;
use App\Traits\SendNotification;
use App\Models\WalletTransaction;

use Illuminate\Support\Facades\DB;
use App\Http\Action\WalletTransactionCommon;

class TwoDBettingWinRepository implements TwoDBettingWinRepositoryInterface
{
    use WalletTransactionCommon, SendNotification, CheckBettingWin;

    public function listBettingWins(Request $request)
    {
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $perPage = $request->per_page ?? 20;
        $searchInput = $request->search_input;
        $bettingWins = BettingWin::with(['game_setting:id,lottery_time'])->where('game_setting_id', '<', 3)
            // ->select('id','number','date_time','time_status','game_setting_id','')
            ->orderBy('id', 'desc')
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
            })
            ->when(($from_date == null && $to_date == null), function ($q) {
                $q->whereDate('betting_wins.date_time', '=', now()->format('Y-m-d'));
            });
        $bettingWins = isset($request->per_page) ? $bettingWins->paginate($perPage) : $bettingWins->get();
        ResponseData($bettingWins);
    }

    public function createBettingWin(array $data)
    {
        $this->checkExistBettingWin($data);
        $bettingWin = BettingWin::create($data);
        return $bettingWin;
    }

    public function upateBettingWin(array $data, BettingWin $bettingWin)
    {
        $bettingWin->update($data);
        $bettingWin->save();

        return $bettingWin;
    }


    public function approveBettingWin(BettingWin $bettingWin)
    {
        if ($bettingWin->is_approved == 1) {
            ResponseMessage('The number is already approved for winning', 400);
        }
        try {
             // $startTime = $date . ' 00:00:00';
            // $endTime = $date . ' 23:59:59';
            // $bettingNumbers = BettingNumber::whereBetween('created_at', [$startTime, $endTime])
            // ->where('number', $bettingWin->number)
            // ->where('game_setting_id', $bettingWin->game_setting_id)
            // ->get();

            DB::beginTransaction();
            $bettingWin->is_approved = 1;
            $bettingWin->approved_by = ApiUser()->id;
            $bettingWin->save();
            $date = date_create($bettingWin->date_time)->format('Y-m-d');
            $gameSetting = GameSetting::find($bettingWin->game_setting_id);
            $startTime = $date . ' ' . $gameSetting->opening_time;
            $endTime = $date . ' ' . $gameSetting->closing_time;


            $bettingNumbers = BettingNumber::whereIn('betting_id', function ($query) use ($startTime, $endTime, $bettingWin) {
                $query->select('id')->from('bettings')
                    ->whereBetween('date_time', [$startTime, $endTime])
                    ->where('game_setting_id', $bettingWin->game_setting_id)
                    ->where('game_id', 1);
            })
                ->where('number', $bettingWin->number)
                ->with('betting')
                ->get();
            $customers = [];
            $walletTransactions = []; // Array to hold bulk data
            foreach ($bettingNumbers as $bettingNumber) {
                $customers[] = $bettingNumber->betting->customer;
                $bettingNumber->is_win = 1;
                $bettingNumber->save();
                $bettingNumber->customer_id = $bettingNumber->betting->customer_id;
                $amount = $bettingNumber->amount * $bettingNumber->betting_multiplier;
                // $this->actionOfWalletTransaction($bettingNumber, $amount, 'in');
                $walletTransactions[] = [
                    'date_time' => now(),
                    'amount' => (int) $amount,
                    'walletable_id' => $bettingNumber->id,
                    'walletable_type' => RelationMorphName($bettingNumber),
                    'action' => 'in',
                    'customer_id' => $bettingNumber->customer_id,
                ];
            }
            if (!empty($walletTransactions)) {
                WalletTransaction::insert($walletTransactions);
            }
            #notification
            if ($bettingNumbers->isNotEmpty()) {
                $data['title'] = 'Betting Win!!';
                // $data['body'] = 'You number ' . $bettingNumber->number . ' is winning !! ';
                $data['body'] = '2D ပေါက်ဂဏန်း ' . $bettingNumber->number . ' တွက်လျော်ကြေးငွေရှိပါသည် ';
                $data['date_time'] = now();
                $this->send($bettingWin, collect($customers), $data);
            }
            #end
            DB::commit();
            ResponseMessage('The number has been approved for winning');
        } catch (Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
        }
    }
}
