<?php

namespace App\Repositories\Account;

use stdClass;
use Exception;
use App\Models\Game;
use App\Models\Account;
use App\Models\GameSetting;
use App\Models\CustomerWallet;
use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;

class AccountRepository implements AccountInterface
{
    public function list($request){
        $searchInput=$request->search_input;
        return Account::orderBy('id','asc')
        ->when($searchInput,function($q)use($searchInput){
            $q->where(function ($query) use ($searchInput) {
                $query->where('name','LIKE','%' .$searchInput .'%')
                    ->orWhere('phone_number','LIKE', '%' .$searchInput .'%');
            });
        })
        ->where('account_type','!=','admin')
        ->get();
    }

    public function detail($account){
        return $account;
    }

    public function updateOrCreate($request){
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $data['deposit_id']=1;
            $data['withdrawal_id']=2;
            $account= Account::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            return $account;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
        return $request;
    }
    public function toggleIsActive($request){
        if($request->is_active=='1' | $request->is_active==1){
            $activeAccount=Account::where('is_active',$request->is_active)
            ->where('account_type',$request->account_type)
            ->first();
            if($activeAccount){
                ResponseMessage('An active account for this provider already exists. Please disable it first.', 419);
            }
        }
        $account=Account::find($request->id);
        if($account){
            $account->is_active=(bool)$request->is_active;
            $account->save();
            return $account;
        }
        ResponseMessage('Account not found',400);
    }
}
