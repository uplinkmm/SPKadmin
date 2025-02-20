<?php

namespace App\Repositories\AgentWithdrawalTransaction;

use App\Models\AgentWithdrawalTransaction;
use App\Traits\AgentWalletBalance;
use Illuminate\Support\Facades\DB;

class AgentWithdrawalTransactionRepository implements AgentWithdrawalTransactionInterface
{

    use AgentWalletBalance;
    public function list($request)
    {
        $agent_id = $request->agent_id;
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        return AgentWithdrawalTransaction::with(['agent'])->orderBy('id', 'desc')
            ->when($agent_id, function ($query) use ($agent_id) {
                $query->where('agent_id', $agent_id);
            })
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
                $q->whereBetween(DB::raw('DATE(agent_withdrawal_transactions.date_time)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
                $q->whereDate('agent_withdrawal_transactions.date_time', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
                $q->whereBetween('agent_withdrawal_transactions.date_time', [now(), $to_date]);
            })
            ->when(($request->from_date == null && $request->to_date == null), function ($q) {
                $q->whereDate('agent_withdrawal_transactions.date_time', '>=', now()->format('Y-m-d'));
            })
            ->paginate(20);
    }

    public function create($request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $agentId = $request->agent_id;
            $data['agent_id'] = $agentId;
            $data['date_time'] = now();
            $agentAmount = $this->retrieveAgentBalance($agentId);
            // dd($request->amount);
            if ((double)$agentAmount >= (double)$request->amount) {
                $agentWithdrawal = AgentWithdrawalTransaction::create($data);
                DB::commit();
                return $agentWithdrawal;
            }
            ResponseMessage('Withdrawal amount is not enough',419);

        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    public function updateAgentWalletTransactionStatus($request)
    {
        $agentWithdrawalTransaction = AgentWithdrawalTransaction::find($request->id);
        if ($agentWithdrawalTransaction) {
            $agentWithdrawalTransaction->status = $request->status;
            $agentWithdrawalTransaction->save();
        }
        return $agentWithdrawalTransaction;
    }
}
