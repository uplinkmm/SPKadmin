<?php
namespace App\Repositories\SlotTransaction;

use DateInterval;

use App\Models\Wager;

use App\Models\GameSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SeamlessTransaction;

class SlotTransactionRepository implements SlotTransactionInterface
{
    public function index($request){
        $perPage = $request->per_page ?? 20;
        return SeamlessTransaction::orderByDesc('seamless_transactions.id')
        ->whereNotNull('wager_id')
        ->join('customers','seamless_transactions.customer_id','customers.id')
        ->join('wagers','seamless_transactions.wager_id','wagers.id')
        ->join('products','seamless_transactions.product_id','products.id')
        ->join('game_types','seamless_transactions.game_type_id','game_types.id')
        ->join('seamless_events','seamless_transactions.seamless_event_id','seamless_events.id')
        ->select('customers.name','wagers.status as win_or_lose','customers.phone_number',
        'seamless_transactions.bet_amount',
        'seamless_transactions.transaction_amount',
        'products.name as game_name',
        'game_types.name as site_name',
        'seamless_transactions.created_at',
        'seamless_events.message_id as ref_no',
        DB::raw('seamless_transactions.bet_amount - seamless_transactions.transaction_amount AS profit')
        )
        ->when(isset($request->game_type_id) && $request->game_type_id ,function($q)use($request){
            $q->where('game_types.id',$request->game_type_id);
        })
        ->when(isset($request->product_id) && $request->product_id,function($q)use($request){
            $q->where('products.id',$request->product_id);
        })
        ->paginate($perPage); 


    }
}