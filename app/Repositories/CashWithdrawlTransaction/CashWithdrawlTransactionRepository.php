<?php

namespace App\Repositories\CashWithdrawlTransaction;

use Exception;

use App\Models\User;
use App\Models\Account;
use App\Traits\BuildWallet;
use Illuminate\Http\Request;

use App\Models\CustomerWallet;

use App\Traits\SendNotification;
use Illuminate\Support\Facades\DB;
use App\Models\CashWithdrawlTransaction;
use App\Http\Action\WalletTransactionCommon;
use App\Repositories\CashWithdrawlTransaction\CashWithdrawlTransactionRepositoryInterface;

class CashWithdrawlTransactionRepository implements CashWithdrawlTransactionRepositoryInterface
{
    use WalletTransactionCommon, SendNotification, BuildWallet;

    public function listTransactions(Request $request)
    {
        $pageNumber = $request->page ?? 1;
        $perPage = $request->per_page ?? config('common.per_page');
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        // $totalCount = CashWithdrawlTransaction::count();
        // $skip = ($pageNumber - 1) * $perPage;
        // $transactions = CashWithdrawlTransaction::with(['customer.wallet','confirmedBy', 'rejectedBy'])
        // ->orderBy('id', 'desc')->skip($skip)->take($perPage)->get();
        // $transactions = MakePaginationData($request, $totalCount, $transactions);

        $transactions = CashWithdrawlTransaction::with(['customer.wallet', 'confirmedBy', 'rejectedBy'])
            ->orderBy('cash_withdrawl_transactions.id', 'desc')
            ->join('customers', 'cash_withdrawl_transactions.customer_id', 'customers.id')
            ->join('accounts', 'cash_withdrawl_transactions.account_id', 'accounts.id')
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('customers.name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(cash_withdrawl_transactions.created_at)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('cash_withdrawl_transactions.created_at', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('cash_withdrawl_transactions.created_at', [now(), $to_date]);
            })
            ->when(($from_date == null && $to_date == null), function ($q) {
                $q->whereDate('cash_withdrawl_transactions.created_at', '>=', now()->format('Y-m-d'));
            })
            ->select(
                'cash_withdrawl_transactions.id',
                'customer_id',
                'transactionId',
                'amount',
                'account_name',
                'cash_withdrawl_transactions.phone_number',
                'payment_transaction_id',
                'status',
                'confirmed_by',
                'confirmed_at',
                'rejected_by',
                'rejected_at',
                'remark',
                'payment_provider',
                'accounts.color_code',
                'cash_withdrawl_transactions.created_at',
                'cash_withdrawl_transactions.updated_at'
            );
        // $transactions = isset($request->per_page) ? $transactions->paginate($perPage) : $transactions->get();
        $totalAmountOfwithdrawal = CashWithdrawlTransaction::selectRaw('
        SUM(CASE WHEN status = "pending" THEN amount ELSE 0 END) AS total_pending_withdrawal,
        SUM(CASE WHEN status = "confirmed" THEN amount ELSE 0 END) AS total_complete_withdrawal')
            // ->groupBy('account_id')
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(cash_withdrawl_transactions.created_at)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('cash_withdrawl_transactions.created_at', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('cash_withdrawl_transactions.created_at', [now(), $to_date]);
            })
            ->when(($from_date == null && $to_date == null), function ($q) {
                $q->whereDate('cash_withdrawl_transactions.created_at', '>=', now()->format('Y-m-d'));
            })
            ->first();
        $withdrawalByAccountList = CashWithdrawlTransaction::join('accounts', 'cash_withdrawl_transactions.account_id', '=', 'accounts.id') // Join with accounts table
            ->selectRaw('
            accounts.name,
            accounts.phone_number,
            cash_withdrawl_transactions.account_id,
            SUM(cash_withdrawl_transactions.amount) AS total_amount
        ')
            ->when(($from_date && $to_date), function ($q) use ($from_date, $to_date) {
                $q->whereBetween(DB::raw('DATE(cash_withdrawl_transactions.created_at)'), [$from_date, $to_date]);
            })
            ->when(($from_date && $to_date == null), function ($q) use ($from_date) {
                $q->whereDate('cash_withdrawl_transactions.created_at', '>=', $from_date);
            })
            ->when(($from_date == null && $to_date), function ($q) use ($to_date) {
                $q->whereBetween('cash_withdrawl_transactions.created_at', [now(), $to_date]);
            })
            ->when(($from_date == null && $to_date == null), function ($q) {
                $q->whereDate('cash_withdrawl_transactions.created_at', '>=', now()->format('Y-m-d'));
            })
            ->where('status', 'confirmed')
            ->groupBy('cash_withdrawl_transactions.account_id', 'accounts.name', 'accounts.phone_number') // Group by account fields and account_id
            ->get();

        $transactions = isset($request->per_page) ? $transactions->paginate($perPage) : $transactions->get();
        $data['transactions'] = $transactions;
        $data['total_pending_withdrawal'] = $totalAmountOfwithdrawal->total_pending_withdrawal;
        $data['total_completed_withdrawal'] = $totalAmountOfwithdrawal->total_complete_withdrawal;
        $data['account_list'] = $withdrawalByAccountList;
        return $data;
    }

    public function store($request)
    {
        $data = $request->all();
        $wallet = CustomerWallet::where('customer_id', $request->customer_id)->first();
        if (!$wallet) {
            ResponseMessage('Cash withdrawl is invalid', 419);
        }
        if ($wallet && $wallet->balance < 1) {
            ResponseMessage('Cash withdrawl confirmation failed, the customer has zero balance', 402);
        }
        if ($wallet && ($request->amount > $wallet->balance)) {
            ResponseMessage('Cash withdrawl confirmation failed, the customer has insufficient balance', 402);
        }
        $account = Account::where('account_type', 'admin')->first();
        $transactionId = 'CW' . now()->format('YmdHis');

        $data['transactionId'] = $transactionId;
        if (!$account) {
            ResponseMessage('withdrawl fail', 419);
        }
        $data['createdable_id'] = UserData()->id;
        $data['createdable_type'] = 'user';
        $data['payment_provider'] = 'admin';
        $data['payment_transaction_id'] = 'admin-' . UserData()->id . '-' . now()->format('YmdHis');
        $data['status'] = 'confirmed';
        $data['confirmed_at'] = now();
        $data['confirmed_by'] = UserData()->id;
        $data['account_name'] = $account->name;
        $data['account_type'] = $account->account_type;
        $data['account_id'] = $account->id;
        DB::beginTransaction();
        try {
            $withdrawl = CashWithdrawlTransaction::create($data);
            if ($withdrawl) {
                $data['title'] = $withdrawl->customer->name;
                $data['body'] = 'has just withdrawal by admin';
                $data['date_time'] = now();
                $users = User::all();
                $this->actionOfWalletTransaction($withdrawl, $withdrawl->amount, 'out');
                $this->send($withdrawl, $users, $data);
            }
            DB::commit();
            return $withdrawl;
        } catch (Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
            return false;
        }

    }

    public function confirmTransaction(CashWithdrawlTransaction $transaction, $userId)
    {
        if ($transaction->status != 'pending') {
            return false;
        }
        $customer = $transaction->customer;
        if (!$customer) {
            ResponseMessage('Customer is not found', 419);
        }
        $wallet = $transaction->customer->customerWallet;
        // $wallet = CustomerWallet::where('customer_id', $customer->id)->first();
        if (!$wallet) {
            ResponseMessage('Wallet not found', 419);
        }
        // if ($wallet->balance < 1) {
        //     $this->rejectTransaction($transaction, $userId);
        //     ResponseMessage('Cash withdrawl confirmation failed, the customer has zero balance', 402);
        // }
        // if ($transaction->amount > $wallet->balance) {
        //     $this->rejectTransaction($transaction, $userId);
        //     ResponseMessage('Cash withdrawl confirmation failed, the customer has insufficient balance', 402);
        // }
        try {
            DB::beginTransaction();
            // $transaction->payment_transaction_id = $paymentTrId;
            $transaction->status = 'confirmed';
            $transaction->confirmed_at = CurrentTime();
            $transaction->confirmed_by = $userId;
            $transaction->save();
            $this->actionOfWalletTransaction($transaction, $transaction->amount, 'out');
            #notification
            if ($transaction) {
                $data['title'] = 'Cash Withdrawal Successfully!!';
                $data['body'] = 'Your cash withdrawal request has been successfully processed. Thank you for using our services.' . $transaction->amount . ' MMK !! ';
                $data['date_time'] = now();
                $data['name'] = "ငွေထုပ်";
                $data['status'] = $transaction->status;
                $data['transaction_date'] = $transaction->confirmed_at;
                $data['amount'] = $transaction->amount;
                $data['provider_name'] = $transaction->account->name;
                $data['payment_transaction_id'] = null;
                $this->send($transaction, $transaction->customer, $data);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function rejectTransaction(CashWithdrawlTransaction $transaction, $userId)
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
                $data['title'] = 'Wtihdrawal Rejected';
                $data['body'] = 'Your cash withdrawal was rejected by admin.';
                $data['date_time'] = now();
                $data['name'] = "ငွေထုပ်";
                $data['status'] = $transaction->status;
                $data['transaction_date'] = $transaction->rejected_at;
                $data['amount'] = $transaction->amount;
                $data['provider_name'] = $transaction->account->name;
                $data['payment_transaction_id'] = null;
                $this->send($transaction, $transaction->customer, $data);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getWithdrawalTransactionHistory($request)
    {
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $searchInput = $request->search_input;
        $perPage = $request->per_page ?? config('common.per_page');

        $withdrawal = CashWithdrawlTransaction::with(['customer:id,name,phone_number'])
            ->select(
                'cash_withdrawl_transactions.id',
                'customer_id',
                'cash_withdrawl_transactions.phone_number',
                'payment_transaction_id',
                'amount',
                'payment_provider',
                'status',
                'cash_withdrawl_transactions.created_at',
                DB::raw("CASE 
        WHEN status = 'pending' THEN cash_withdrawl_transactions.created_at
        WHEN status = 'confirmed' THEN cash_withdrawl_transactions.confirmed_at
        WHEN status = 'rejected' THEN cash_withdrawl_transactions.rejected_at
        END AS transaction_updated_date"),
            )
            ->join('customers', 'cash_withdrawl_transactions.customer_id', '=', 'customers.id')
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('customers.name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
                $q->whereBetween(DB::raw('DATE(cash_withdrawl_transactions.created_at)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
                $q->whereDate('cash_withdrawl_transactions.created_at', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
                $q->whereBetween('cash_withdrawl_transactions.created_at', [now(), $to_date]);
            })
            ->when(($request->from_date == null && $request->to_date == null), function ($q) {
                $q->whereDate('cash_withdrawl_transactions.created_at', '>=', now()->subDays(30)->format('Y-m-d'));
            });
        $withdrawal = isset($request->per_page) ? $withdrawal->paginate($perPage) : $withdrawal->get();
        return $withdrawal;
    }
}
