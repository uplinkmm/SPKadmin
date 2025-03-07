<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use App\Traits\BuildWallet;
use App\Models\CustomerWallet;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements CustomerInterface
{
    use BuildWallet;
    public function getCustomerList($request){
        // dd('abc');
        $perPage = $request->per_page ?? 20;
            $customers = DB::table('customers')
            ->orderBy('customers.id','desc')
            ->leftJoin('customer_wallets', 'customers.id', '=', 'customer_wallets.customer_id')
            ->leftJoin('agents', 'customers.agent_id', '=', 'agents.id')
            ->leftJoin('topup_transactions', function ($join) {
                $join->on('customers.id', '=', 'topup_transactions.customer_id')
                    ->where('topup_transactions.status', '=', 'confirmed');
            })
            ->leftJoin('cash_withdrawl_transactions', function ($join) {
                $join->on('customers.id', '=', 'cash_withdrawl_transactions.customer_id')
                    ->where('cash_withdrawl_transactions.status', '=', 'confirmed');
            })
            ->select(
                'customers.id',
                'customers.name',
                'customers.phone_number',
                'customers.verified_at',
                'agents.id as agent_id',
                'agents.name as agent_name',
                DB::raw('COALESCE(customer_wallets.balance, 0) as wallet_balance'),
                DB::raw('CASE WHEN COALESCE(customer_wallets.balance, 0) > 0 THEN 1 ELSE 0 END as is_active'),
                DB::raw('COALESCE(SUM(DISTINCT topup_transactions.amount), 0) as total_topup_amount'),
                DB::raw('COALESCE(COUNT(DISTINCT topup_transactions.id), 0) as total_topup_count'),
                DB::raw('COALESCE(SUM(DISTINCT cash_withdrawl_transactions.amount), 0) as total_withdrawal_amount'),
                DB::raw('COALESCE(COUNT(DISTINCT cash_withdrawl_transactions.id), 0) as total_withdrawal_count')
            )
            ->when($request->search_input,function($query)use($request){
                $query->where('customers.name','LIKE','%'.$request->search_input.'%')
                ->orWhere('customers.phone_number','LIKE','%'.$request->search_input.'%');
            })
            ->groupBy('customers.id', 'customers.name', 'customers.phone_number', 'customers.verified_at','customer_wallets.balance','agents.id','agents.name');
            // ->paginate(20);
        // if(isset($request->per_page)){
        //     $customers=$customers->paginate($perPage);
        //     return $customers;
        // }else{
        //     return Customer::all();
        // }
        $customers = isset($request->per_page) ? $customers->paginate($perPage) : Customer::all();
        return $customers;
    }

    public function getCustomerLimitationList($request){
        $customers=Customer::select('id','name','phone_number','two_d_limit','three_d_limit')
        ->when($request->search_input,function($query)use($request){
            $query->where('name','LIKE','%'.$request->search_input.'%')
            ->orWhere('phone_number','LIKE','%'.$request->search_input.'%');
        })
        ->paginate(20);
        return $customers;
    }

    public function updateCustomerBetLimit($request){
        $customer=Customer::find($request->id);
        if($customer){
            $column=$request->column;
            $customer->$column=$request->value;
            $customer->save();
            return $customer;
        }
        ResponseMessage('Customer Not Found',404);
    }

    public function store($request){
        $data = $request->all();
        // $data['otp'] = 000000;
        $data['is_verified']=1;
        $data['verified_at']=now();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $customer = Customer::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            $this->createWallet($customer->id);
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

}
