<?php

namespace App\Repositories\Agent;

use App\Models\Game;
use App\Models\Agent;
use App\Models\Betting;
use App\Models\Customer;
use App\Models\AgentWallet;
use App\Models\AgentCommission;
use App\Traits\AgentWalletBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AgentRepository implements AgentInterface
{
    use AgentWalletBalance;
    public function list($request)
    {
        $searchInput = $request->search_input;
        $agentQuery = Agent::with(['agent_commissions'])
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $searchInput . '%');
                });
            });
        if ($request->page) {
            return $agentQuery->paginate(20);
        }
        return $agentQuery->where('is_active', 1)->get();
    }

    public function updateOrCreate($request)
    {
        $commissions = JsonDecode($request->commission);
        $data = $request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            } else {
                if (isset($data['new_password']) && $data['new_password'] != null) {
                    $data['password'] = $data['new_password'];
                }
            }
            $agent = Agent::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            foreach ($commissions as $commission) {
                if (!isset($request->id)) {
                    $commission_data['id'] = null;
                } else {
                    $commission_data['id'] = $commission->id;
                }
                $commission_data['agent_id'] = $agent->id;
                $commission_data['game_id'] = $commission->game_id;
                $commission_data['commission_amount'] = $commission->commission_amount;
                $commissionObj = AgentCommission::updateOrCreate(
                    ['id' => $commission_data['id']],
                    $commission_data
                );
            }
            DB::commit();
            return $agent;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detail($agent)
    {
        $agent->load('agent_commissions');
        return $agent;
    }

    public function toggleIsActive($request)
    {
        $agent = Agent::where('id', $request->id)->first();
        if ($agent) {
            $agent->is_active = (int) $request->is_active;
            $agent->save();
            return $agent;
        }
        ResponseMessage('Data not found', 404);
    }

    public function customerListByAgent($request)
    {
        $perPage = 20;
        $agent_id = $request->agent_id;
        $searchInput = $request->search_input;
        // $route_name = Route::currentRouteName();
        // $agent_id = $route_name == 'agent_customer' ? $request->agent_id : (isset($request->agent_id) ? $request->agent_id : null);
        $totalCustomer = Customer::count();

        $allGames = Game::all()->keyBy('id');

        // Fetch customers with bettings and commissions data, including pagination
        $customersQuery = Customer::leftJoin('bettings', 'customers.id', '=', 'bettings.customer_id')
            ->leftJoin('games', 'bettings.game_id', '=', 'games.id')
            ->leftJoin('agent_commissions', function ($join) use ($agent_id) {
                $join->on('agent_commissions.agent_id', '=', 'customers.agent_id')
                    ->on('agent_commissions.game_id', '=', 'bettings.game_id');
            })
            ->select(
                'customers.id',
                'customers.name',
                'customers.phone_number',
                'games.id as game_id',
                'games.name as game_name',
                DB::raw('COALESCE(SUM(bettings.total_amount), 0) as total_bet_amount'),
                DB::raw('COALESCE(agent_commissions.commission_amount, 0) as commission_amount'),
                DB::raw('(COALESCE(SUM(bettings.total_amount), 0) * COALESCE(agent_commissions.commission_amount, 0) / 100) as commission_percentage')
            )
            ->when($agent_id, function ($query) use ($agent_id) {
                $query->where('customers.agent_id', $agent_id);
            })
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('customers.name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->groupBy('customers.id', 'customers.name', 'customers.phone_number', 'games.id', 'games.name', 'agent_commissions.commission_amount');

        // Paginate the query
        $customersPaginated = $customersQuery->paginate($perPage);

        // Format the customers data
        $formattedCustomers = $customersPaginated->getCollection()->groupBy('id')->map(function ($customerGames, $customerId) use ($allGames) {
            $first = $customerGames->first();
            $gamesData = $allGames->map(function ($game) use ($customerGames) {
                $customerGame = $customerGames->firstWhere('game_id', $game->id);
                return [
                    'id' => $game->id,
                    'name' => $game->name,
                    'total_bet_amount' => $customerGame ? $customerGame->total_bet_amount : 0,
                    'commission_percentage' => $customerGame ? $customerGame->commission_percentage : 0,
                    'commission_amount' => $customerGame ? $customerGame->commission_amount : 0,
                ];
            });

            return [
                'id' => $first->id,
                'name' => $first->name,
                'phone_number' => $first->phone_number,
                'games' => $gamesData->values()->all(),
            ];
        })->values()->all();

        $totalCustomer = Customer::count();

        // Create a new paginator instance with the formatted data
        $paginatedCustomers = new LengthAwarePaginator(
            $formattedCustomers,
            $totalCustomer, // Total number of items
            $customersPaginated->perPage(), // Items per page
            $customersPaginated->currentPage(), // Current page
            ['path' => LengthAwarePaginator::resolveCurrentPath()]// Path for pagination links
        );

        return $paginatedCustomers;
    }

    public function transactionListByAgent($request)
    {
        $agent_id = $request->agent_id;
        $searchInput = $request->search_input;
        $date = convertDateFormat($request->date);
        return Betting::with([
            'bettingNumbers:id,number,amount,betting_id',
        ])
            ->join('customers', 'bettings.customer_id', 'customers.id')
            ->join('agents', 'customers.agent_id', 'agents.id')
            ->join('agent_commissions', function ($join) use ($agent_id, $request) {
                $join->on('agents.id', '=', 'agent_commissions.agent_id')
                    ->where('agent_commissions.game_id', $request->game_id);
            })
            ->orderBy('id', 'DESC')
            ->where('bettings.game_id', $request->game_id)
            ->when($agent_id, function ($query) use ($agent_id) {
                $query->where('customers.agent_id', $agent_id);
            })
            ->whereDate('bettings.date_time', $date)
            ->when($searchInput, function ($q) use ($searchInput) {
                $q->where(function ($query) use ($searchInput) {
                    $query->where('customers.name', 'LIKE', '%' . $searchInput . '%')
                        ->orWhere('customers.phone_number', 'LIKE', '%' . $searchInput . '%');
                });
            })
            ->select(
                'bettings.id',
                'total_amount',
                'agent_commissions.commission_amount',
                'customers.name',
                'customers.phone_number',
                'agents.name as agent_name',
                DB::raw('(agent_commissions.commission_amount / 100 * bettings.total_amount) as commission_percentage')
            )
            ->paginate(20);
    }

    public function commissionAmountOfDayByAgent($request)
    {
        $agent_id = $request->agent_id;
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        $allGames = Game::orderBy('id', 'desc')->get()->keyBy('id');
        $bettingsQuery = Betting::join('customers', 'bettings.customer_id', 'customers.id')
            ->join('agents', 'customers.agent_id', 'agents.id')
            ->leftJoin('agent_commissions', function ($join) use ($agent_id) {
                $join->on('agents.id', '=', 'agent_commissions.agent_id')
                    ->on('bettings.game_id', '=', 'agent_commissions.game_id');
            })
            ->join('games', 'bettings.game_id', '=', 'games.id')
            ->when($agent_id, function ($query) use ($agent_id) {
                $query->where('customers.agent_id', $agent_id);
            })
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
                $q->whereBetween(DB::raw('DATE(bettings.date_time)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
                $q->whereDate('bettings.date_time', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
                $q->whereBetween('bettings.date_time', [now(), $to_date]);
            })
            ->when(($request->from_date == null && $request->to_date == null), function ($q) {
                $q->whereDate('bettings.date_time', '>=', now()->format('Y-m-d'));
            })
            ->select(
                DB::raw('DATE_FORMAT(bettings.date_time, "%Y-%m-%d") as date'),
                'bettings.game_id',
                'games.name as game_name',
                DB::raw('SUM(bettings.total_amount) as total_amount'),
                'agent_commissions.commission_amount',
            )

            ->groupBy(DB::raw('DATE_FORMAT(bettings.date_time, "%Y-%m-%d")'), 'bettings.game_id', 'games.name', 'agent_commissions.commission_amount')
            ->get();

        $response = [];
        $totalSum = 0;
        $totalCommissionSum = 0;

        foreach ($bettingsQuery as $row) {
            $date = $row->date;
            $game_id = $row->game_id;
            $total_amount = $row->total_amount;
            $game_name = $row->game_name;
            $commission_amount = $row->commission_amount;
            $commission_percentage = ($commission_amount / 100) * $total_amount;

            if (!isset($response[$date])) {
                $response[$date] = [
                    'date' => $date,
                    'games' => [],
                    'total' => 0,
                    'total_commission_percentage' => 0,
                ];
            }

            $response[$date]['games'][$game_id] = [
                'id' => $game_id,
                'total_amount' => $total_amount,
                'name' => $game_name,
                'commission_amount' => $commission_amount,
                'commission_percentage' => $commission_percentage,
            ];

            $response[$date]['total'] += $total_amount;
            $response[$date]['total_commission_percentage'] += $commission_percentage;
        }

        // Ensure all games are included for each date
        foreach ($response as &$dateEntry) {
            foreach ($allGames as $game) {
                if (!isset($dateEntry['games'][$game->id])) {
                    $dateEntry['games'][$game->id] = [
                        'id' => $game->id,
                        'total_amount' => 0,
                        'name' => $game->name,
                        'commission_amount' => 0,
                        'commission_percentage' => 0,
                    ];
                }
            }
            usort($dateEntry['games'], function ($a, $b) {
                return $a['id'] <=> $b['id'];
            });
            // $dateEntry['games'] = array_values($dateEntry['games']); // Convert to indexed array
        }
        // Convert response to indexed array
        $response = array_values($response);
        // Pagination
        $page = $request->page; // Get current page number
        $perPage = 20; // Number of items per page
        $offset = ($page - 1) * $perPage;
        $pagedData = array_slice($response, $offset, $perPage);
        $paginatedResponse = new LengthAwarePaginator($pagedData, count($response), $perPage, $page, [
            'path' => Request::url(),
            'query' => Request::query(),
        ]);
        return $paginatedResponse;

    }

    public function getAgentWallet($request)
    {
        $agent_id = $request->agent_id;
        // $date = convertDateFormat($request->date);
        $from_date = convertDateFormat($request->from_date);
        $to_date = convertDateFormat($request->to_date);
        if($agent_id){
           $agentBalance= $this->retrieveAgentBalance($agent_id) ?? 0;
        }
        $wallets = AgentWallet::select(
            DB::raw('DATE(agent_wallets.date_time) as date'),
            DB::raw('SUM(CASE WHEN action = "in" THEN agent_wallets.amount ELSE agent_wallets.amount END) as amount'),
            'action',
            'agents.name as agent_name',
            'agents.id as agent_id'
        )
            ->join('agents', 'agent_wallets.agent_id', 'agents.id')
            ->when($agent_id, function ($query) use ($agent_id) {
                $query->where('agent_wallets.agent_id', $agent_id);
            })
            // ->when(($request->date != null || $request->date != ""), function ($q) use ($date, $request) {
            //     $q->whereDate('agent_wallets.date_time', '<=', $date);
            // })
            ->when(($request->from_date && $request->to_date), function ($q) use ($from_date, $to_date, $request) {
                $q->whereBetween(DB::raw('DATE(agent_wallets.date_time)'), [$from_date, $to_date]);
            })
            ->when(($request->from_date && $request->to_date == null), function ($q) use ($from_date, $request) {
                $q->whereDate('agent_wallets.date_time', '>=', $from_date);
            })
            ->when(($request->from_date == null && $request->to_date), function ($q) use ($to_date, $request) {
                $q->whereBetween('agent_wallets.date_time', [now(), $to_date]);
            })
            ->when(($request->from_date == null && $request->to_date == null), function ($q) {
                $q->whereDate('agent_wallets.date_time', '>=', now()->format('Y-m-d'));
            })
            ->groupBy(DB::raw('DATE(agent_wallets.date_time)'), 'action', 'agent_id', 'agent_name')
            ->orderBy(DB::raw('DATE(agent_wallets.date_time)'))
            ->get();
        $balance = 0;
        $result = [];
        foreach ($wallets as $wallet) {
            $balance += ($wallet->action == 'in') ? $wallet->amount : -$wallet->amount;
            $result[] = [
                'date' => $wallet->date,
                'amount' => $wallet->amount,
                'balance' => $balance,
                'action' => $wallet->action,
                'id' => $wallet->agent_id,
                'name' => $wallet->agent_name,
            ];
        }
        // $result = array_reverse($result);
        $page = $request->page; // Get current page number
        $perPage = 20; // Number of items per page
        $offset = ($page - 1) * $perPage;
        $pagedData = array_slice($result, $offset, $perPage);
        $paginatedResponse = new LengthAwarePaginator($pagedData, count($result), $perPage, $page, [
            'path' => Request::url(),
            'query' => Request::query(),
        ]);
        $data['agent_wallet_balance']=$agentBalance;
        $data['wallets']=$paginatedResponse;
        return $data;
        // return $paginatedResponse;
    }

}
