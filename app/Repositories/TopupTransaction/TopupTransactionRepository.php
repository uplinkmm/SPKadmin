<?php

namespace App\Repositories\TopupTransaction;

use App\Http\Action\WalletTransactionCommon;
use Exception;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\CustomerWallet;
use App\Models\Account;
use App\Models\TopupTransaction;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\DB;

class TopupTransactionRepository implements TopupTransactionRepositoryInterface
{
    use WalletTransactionCommon, SendNotification;
    public function listTransactions(Request $request)
    {
        $pageNumber = $request->page ?? 1;
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        // $totalCount = TopupTransaction::count();
        // $skip = ($pageNumber - 1) * $perPage;
        // $transactions = TopupTransaction::with(['customer','confirmedBy', 'rejectedBy'])
        // ->orderBy('id', 'desc')->skip($skip)->take($perPage)->get();
        // $transactions = MakePaginationData($request, $totalCount, $transactions);
        $perPage = $request->per_page ?? config('common.per_page');
        $transactions = TopupTransaction::with(['customer', 'confirmedBy', 'rejectedBy'])
            ->orderBy('topup_transactions.id', 'desc')
            ->join('customers', 'topup_transactions.customer_id', 'customers.id')
            ->join('accounts', 'topup_transactions.account_id', 'accounts.id')
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('customers.name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(topup_transactions.created_at)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('topup_transactions.created_at', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('topup_transactions.created_at', [now(), $to_date]);
            })
            ->when(($from_date == null && $to_date == null), function ($q) {
                $q->whereDate('topup_transactions.created_at', '>=', now()->format('Y-m-d'));
            })
            ->select(
                'topup_transactions.id',
                'customer_id',
                'transactionId',
                'amount',
                'payment_provider',
                'payment_transaction_id',
                'status',
                'confirmed_by',
                'confirmed_at',
                'rejected_by',
                'rejected_at',
                'remark',
                'account_id',
                'accounts.color_code',
                'topup_transactions.updated_at',
                'topup_transactions.created_at',
            );
        $totalAmountOfDeposit = TopupTransaction::selectRaw('
        SUM(CASE WHEN status = "pending" THEN amount ELSE 0 END) AS total_pending_deposit,
        SUM(CASE WHEN status = "confirmed" THEN amount ELSE 0 END) AS total_complete_deposit
    ')
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(topup_transactions.created_at)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('topup_transactions.created_at', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('topup_transactions.created_at', [now(), $to_date]);
            })
            ->when(($from_date == null && $to_date == null), function ($q) {
                $q->whereDate('topup_transactions.created_at', '>=', now()->format('Y-m-d'));
            })
            // ->groupBy('account_id')
            ->first();
        $depositByAccountList = TopupTransaction::join('accounts', 'topup_transactions.account_id', '=', 'accounts.id') // Join with accounts table
            ->selectRaw('
            accounts.name,
            accounts.phone_number,
            accounts.color_code,
            topup_transactions.account_id,
            SUM(topup_transactions.amount) AS total_amount
        ')
        ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
            $q->whereBetween(DB::raw('DATE(topup_transactions.created_at)'), [$from_date, $to_date]);
        })
        ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
            $q->whereDate('topup_transactions.created_at', '>=', $from_date);
        })
        ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
            $q->whereBetween('topup_transactions.created_at', [now(), $to_date]);
        })
        ->when(($from_date == null && $to_date == null), function ($q) {
            $q->whereDate('topup_transactions.created_at', '>=', now()->format('Y-m-d'));
        })
            ->where('status', 'confirmed')
            ->groupBy('topup_transactions.account_id', 'accounts.name', 'accounts.phone_number', 'accounts.color_code') // Group by account fields and account_id
            ->get();
        $transactions = isset($request->per_page) ? $transactions->paginate($perPage) : $transactions->get();
        $data['transactions'] = $transactions;
        $data['total_pending_deposit'] = $totalAmountOfDeposit->total_pending_deposit;
        $data['total_completed_deposit'] = $totalAmountOfDeposit->total_complete_deposit;
        $data['account_list'] = $depositByAccountList;
        return $data;
        // return $transactions;
    }

    public function store($request)
    {
        $data = $request->all();
        $account = Account::where('account_type', 'admin')->first();
        $transactionId = 'TP' . now()->format('YmdHis');
        $data['transactionId'] = $transactionId;
        if (!$account) {
            ResponseMessage('Topup fail', 419);
        }
        $data['createdable_id'] = UserData()->id;
        $data['createdable_type'] = 'user';
        $data['payment_provider'] = 'admin';
        $data['payment_transaction_id'] = 'admin-' . UserData()->id . '-' . now()->format('YmdHis');
        $data['status'] = 'confirmed';
        $data['confirmed_at'] = now();
        $data['confirmed_by'] = UserData()->id;
        $data['account_id'] = $account->id;
        DB::beginTransaction();
        try {
            $transaction = TopupTransaction::create($data);
            if ($transaction) {
                $data['title'] = 'Topup Successfully!!';
                $data['body'] = 'Successfully added ' . $transaction->amount . ' MMK !! by admin';
                $data['date_time'] = now();
                $this->actionOfWalletTransaction($transaction, $transaction->amount, action: 'in');
                $this->send($transaction, $transaction->customer, $data);
            }
            DB::commit();
            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
            return false;
        }

    }


    public function confirmTransaction(TopupTransaction $transaction, $userId)
    {
        if ($transaction->status != 'pending') {
            return false;
        }
        DB::beginTransaction();
        try {
            $transaction->status = 'confirmed';
            $transaction->confirmed_at = CurrentTime();
            $transaction->confirmed_by = $userId;
            #notification
            if ($transaction) {
                $data['title'] = 'Topup Successfully!!';
                $data['body'] = 'You successfully added ' . $transaction->amount . ' MMK !! ';
                $data['date_time'] = now();
                $data['type'] = "topup_transaction";
                $data['name'] = "ငွေသွင်း";
                $data['type'] = "topup_transaction";
                $data['status'] = $transaction->status;
                $data['transaction_date'] = $transaction->confirmed_at;
                $data['amount'] = $transaction->amount;
                $data['provider_name'] = $transaction->account->name;
                $data['payment_transaction_id'] = $transaction->payment_transaction_id;
                $this->actionOfWalletTransaction($transaction, $transaction->amount,  'in');
                (new PromotionService())->claimPromotion($transaction->customer, $data['amount']);
                (new PromotionService())->claimNewUserPromotion($transaction->customer);
                $this->send($transaction, $transaction->customer, $data);
                $transaction->save();
            }
            #end
            // $customer = $transaction->customer;
            // if($customer->wallet){
            //     $customer->wallet->balance += $transaction->amount;
            //     $customer->wallet->save();
            // }else{
            //     CustomerWallet::create([
            //         'walletId'=>Str::random(10),
            //         'customer_id'=>$userId,
            //         'balance'=>$transaction->amount,
            //     ]);
            // }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
            return false;
        }
    }

    public function rejectTransaction(TopupTransaction $transaction, $userId)
    {
        if ($transaction->status != 'pending') {
            return false;
        }
        try {
            DB::beginTransaction();
            $transaction->status = 'rejected';
            $transaction->rejected_at = CurrentTime();
            $transaction->rejected_by = $userId;
            $transaction->save();
            if ($transaction) {
                $data['title'] = 'Topup Rejected';
                $data['body'] = 'Your cash withdrawal was rejected by admin';
                $data['status'] = $transaction->stautus;
                $data['date_time'] = now();
                $data['status'] = $transaction->status;
                $data['type'] = "topup_transaction";
                $data['transaction_date'] = $transaction->rejected_at;
                $data['amount'] = $transaction->amount;
                $data['provider_name'] = $transaction->account->name;
                $data['payment_transaction_id'] = $transaction->payment_transaction_id;
                $this->send($transaction, $transaction->customer, $data);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getTopupTransactionHistory($request)
    {
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $perPage = $request->per_page ?? config('common.per_page');
        $topups = TopupTransaction::with(['customer:id,name,phone_number'])
            ->select(
                'topup_transactions.id',
                'customer_id',
                'payment_transaction_id',
                'amount',
                'payment_provider',
                'status',
                'topup_transactions.created_at',
                DB::raw("CASE 
        WHEN status = 'pending' THEN topup_transactions.created_at
        WHEN status = 'confirmed' THEN topup_transactions.confirmed_at
        WHEN status = 'rejected' THEN topup_transactions.rejected_at
        END AS transaction_updated_date"),
            )
            ->join('customers', 'topup_transactions.customer_id', '=', 'customers.id')
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('customers.name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
                $q->whereBetween(DB::raw('DATE(topup_transactions.created_at)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
                $q->whereDate('topup_transactions.created_at', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
                $q->whereBetween('topup_transactions.created_at', [now(), $to_date]);
            })
            ->when(($request->from_date == null && $request->to_date == null), function ($q) {
                // $q->whereDate('topup_transactions.created_at', '>=', now()->format('Y-m-d'));
                $q->whereDate('topup_transactions.created_at', '>=', now()->subDays(30)->format('Y-m-d')); //default is previous 30day
    
            });
        // ->where('status','confirmed')
        $topups = isset($request->per_page) ? $topups->paginate($perPage) : $topups->get();
        return $topups;
    }
}
