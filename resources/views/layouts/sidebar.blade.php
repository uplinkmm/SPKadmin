<nav class="side-bar w-fit pt-0 min-h-[100vh] h-full z-40">
    <div class="relative">
        <button type="button" id="toggleBtn" class="py-3 px-2 absolute left-full top-5 bg-[#202020] text-white rounded-tr-md rounded-br-md ">
            <i class="fas fa-chevron-double-left ease-linear" style="transition:transform 0.5s ease;"></i>
        </button>
        <div id="sidebar" class="relative pb-12 overflow-y-hidden hidden-scrollbar h-[100vh] w-60" onmouseover="this.style.overflowY='scroll'"
            onmouseout="this.style.overflowY='hidden'" style="transition: width 0.3s;">
            
            @php
                // $isWebAuthenticated = Auth::guard('web')->check();
                // $isAgentAuthenticated = Auth::guard('agent')->check();
                // dd([$isWebAuthenticated,$isAgentAuthenticated]);
            @endphp
            <div class="relative w-[15rem] pt-12">
                
                <ul class=" mb-4 ">
                    @if (Auth::guard('web')->check())
                    
                        @if(checkUserPermission('2d'))

                            <li>
                                <a class="flex gap-x-4 items-center @yield('accounting')" data-twe-collapse-init
                                    data-twe-ripple-init data-twe-ripple-color="light" href="#collapseExample" role="button"
                                    aria-expanded="false" aria-controls="collapseExample">
                                    <!-- <span class="text-xs border border-white px-2 py-0.5 mr-3">2D</span> -->
                                    <!-- <img class="w-4 h-4" src="{{ asset('img/2d.png') }}" alt=""> -->
                                    <i class="fal fa-sack-dollar !w-fit pl-1"></i>
                                    2D
                                    <i class="fas fa-caret-down absolute right-2"></i>
                                </a>
                            </li>

                            <li>
                                <div class="!visible @yield('2d-block')hidden text-center bg-neutral-800 pb-4" id="collapseExample"
                                    @yield('2d-collapse') data-twe-collapse-item>
                                    <ul class=" mb-4">
                                        <li>
                                            <a href="{{ route('twod_reports.bettings_overview.index') }}"
                                                class="flex !pl-10 items-center @yield('twod_betting_overview')">
                                                <i class="fal fa-th pr-2"></i>
                                                2D Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('twod_reports.betting_amounts.index') }}"
                                                class="flex !pl-10 items-center @yield('twod_reports.betting_amounts.index')">
                                                <i class="fal fa-hand-holding-usd pr-2"></i>
                                                2D Table

                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('twod_reports.customer_bets.index') }}"
                                                class="flex !pl-10 items-center @yield('twod_Report')">
                                                <i class="fal fa-user-chart pr-2"></i>
                                                2D Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('twod_reports.bet_list.index') }}"
                                                class="flex !pl-10 items-center @yield('twod_bet_list')">
                                                <i class="fal fa-clipboard-list pr-2"></i>
                                                2D Bet List
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('twod_reports.winner_list.index') }}"
                                                class="flex !pl-10 items-center @yield('twod_winner')">
                                                <i class="fal fa-user-check pr-2"></i>
                                                2D Winners
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('twod_reports.winning_numbers.index') }}"
                                                class="flex !pl-10 items-center @yield('twod_winning_number')">
                                                <i class="fal fa-trophy pr-2"></i>
                                                2D Results
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('twod_reports.closing_numbers.index') }}"
                                                class="flex !pl-10 items-center @yield('twod_close_list')">
                                                <i class="fal fa-toggle-off pr-2"></i>
                                                2D Settings
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        @if(checkUserPermission('3d'))

                            <li>
                                <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                                    data-twe-ripple-color="light" href="#collapseExample2" role="button" aria-expanded="false"
                                    aria-controls="collapseExample2">
                                    <i class="fal fa-sack-dollar !w-fit pl-1"></i>
                                    3D
                                    <i class="fas fa-caret-down absolute right-2"></i>
                                </a>
                            </li>

                            <li>
                                <div class="!visible @yield('3d-block')hidden text-center bg-neutral-800 pb-4" id="collapseExample2"
                                    @yield('3d-collapse') data-twe-collapse-item>
                                    <ul class=" mb-4">
                                        <li>
                                            <a href="{{ route('threed_reports.betting_overview.index') }}"
                                                class="!pl-10 flex items-center @yield('threed_overview')">
                                                <i class="fal fa-th pr-2"></i>
                                                3D Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('threed_reports.betting_amounts.index') }}"
                                                class="!pl-10 flex items-center @yield('threed_betting_amount')">
                                                <i class="fal fa-hand-holding-usd pr-2"></i>
                                                3D Table

                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('threed_reports.customer_bets.index') }}"
                                                class="!pl-10 flex items-center @yield('threed_customer_bets')">
                                                <i class="fal fa-user-chart pr-2"></i>
                                                3D Report
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('threed_reports.bet_list.index') }}"
                                                class="!pl-10 flex items-center @yield('threed_bet_list')">
                                                <i class="fal fa-clipboard-list pr-2"></i>
                                                3D Bet List
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('threed_reports.winner_list.index') }}"
                                                class="!pl-10 flex items-center @yield('threed_winning_number')">
                                                <i class="fal fa-user-check pr-2"></i>
                                                3D Winners
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('threed_reports.winning_numbers.index') }}"
                                                class="!pl-10 flex items-center @yield('threed_winning_numbers')">
                                                <i class="fal fa-trophy pr-2"></i>
                                                3D Results
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('threed_reports.game_setting.index') }}"
                                                class="!pl-10 flex items-center @yield('threed_reports.game_setting.index')">
                                                <i class="fal fa-cogs pr-3"></i>
                                                3D Game Setting
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/threeclosing" class="!pl-10 flex items-center @yield('threed_close_list')">
                                                <i class="fal fa-cogs pr-3"></i>
                                                3D Setting
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                        <li>
                                <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                                    data-twe-ripple-color="light" href="#slotCollapse" role="button" aria-expanded="false"
                                    aria-controls="slotCollapse">
                                    <i class="fal fa-user !w-fit pl-1"></i>
                                    Slots
                                    <i class="fas fa-caret-down absolute right-2"></i>
                                </a>
                            </li>

                            <li>
                                <div class="!visible @yield('slot-block')hidden text-center bg-neutral-800 pb-4" id="slotCollapse"
                                    @yield('slot-collapse') data-twe-collapse-item>
                                    <ul class=" mb-4">
                                        <li>
                                            <a href="{{ route('slot_transcation') }}"
                                                class="flex !pl-10 items-center @yield('slot_list')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                Slot Transcations
                                            </a>
                                        </li>
                                        <li>
                                        <a href="{{ route('provider_report') }}"
                                                class="flex !pl-10 items-center @yield('provider_report')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                Provider Report
                                            </a>
                                        </li>
                                        <li>
                                        <a href="{{ route('slot_user_lists') }}"
                                                class="flex !pl-10 items-center @yield('slot_user_lists')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                Slot Users
                                            </a>
                                        </li>
                                        <li>
                                        <a href="{{ route('user_report') }}"
                                                class="flex !pl-10 items-center @yield('user_report')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                User Report
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @if(checkUserPermission('setting'))
                            <li>
                                <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                                    data-twe-ripple-color="light" href="#userCollapse" role="button" aria-expanded="false"
                                    aria-controls="userCollapse">
                                    <i class="fal fa-user !w-fit pl-1"></i>
                                    User
                                    <i class="fas fa-caret-down absolute right-2"></i>
                                </a>
                            </li>

                            <li>
                                <div class="!visible @yield('user-block')hidden text-center bg-neutral-800 pb-4" id="userCollapse"
                                    @yield('user-collapse') data-twe-collapse-item>
                                    <ul class=" mb-4">
                                        <li>
                                            <a href="{{ route('users.index') }}"
                                                class="flex !pl-10 items-center @yield('user_list')">
                                                <i class="fal fa-book-user pr-2"></i>
                                                User
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('users.limit_user') }}"
                                                class="flex !pl-10 items-center @yield('limit_user')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                Limit User

                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="{{ route('settings') }}" class="flex items-center @yield('settings')">
                                    <i class="fal fa-tasks pr-3"></i>
                                    Settings
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('payment_providers.index') }}"
                                    class="flex items-center @yield('payment_providers')">
                                    <i class="fal fa-tasks pr-3"></i>
                                    Payment Providers
                                </a>
                            </li>
                            @endif
                            @if(checkUserPermission('transaction'))

                            <li>
                                <a href="{{ route('topup_transactions.index') }}"
                                    class="flex items-center @yield('topup_transactions')">
                                    <i class="fal fa-tasks pr-3"></i>
                                    Deposit Transactions
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('cash_withdrawal_transactions.index') }}"
                                    class="flex items-center @yield('withdrawal_transactions')">
                                    <i class="fal fa-tasks pr-3"></i>
                                    Withdrawal Transactions
                                </a>
                            </li>
                            @endif
                            @if(checkUserPermission('setting'))
                            <li>
                                <a href="{{ route('balance_transactions.index') }}"
                                    class="flex items-center @yield('balance_transactions.index')">
                                    <i class="fal fa-tasks pr-3"></i>
                                    Balance Transactions
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('history.deposit') }}" class="flex items-center @yield('history.deposit')">
                                    <i class="fal fa-tasks pr-3"></i>
                                    Deposit Histories
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('history.withdrawal') }}" class="flex items-center @yield('history.withdrawal')">
                                    <i class="fal fa-tasks pr-3"></i>
                                    Withdrawal Histories
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('games') }}" class="flex items-center @yield('games')">
                                    <i class="fal fa-money-check-edit-alt pr-2"></i>
                                    Games
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ads_lists') }}" class="flex items-center @yield('ads_lists')">
                                    <i class="fal fa-money-check-edit-alt pr-2"></i>
                                    Ads & Promotion
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin_users') }}" class="flex items-center @yield('admin_lists')">
                                    <i class="fal fa-book-user pr-2"></i>
                                    Admins
                                </a>
                            </li>
                        @endif
                            <li>
                                <a href="{{ route('TermsAndConditions') }}" class="flex items-center @yield('TermsAndConditions')">
                                    <i class="fal fa-money-check-edit-alt pr-2"></i>
                                    Terms And Conditions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contacts') }}" class="flex items-center @yield('contacts')">
                                    <i class="fal fa-money-check-edit-alt pr-2"></i>
                                    Contacts
                                </a>
                            </li>
                        @if(checkUserPermission('transaction'))

                            <li>
                                <a href="{{ route('topup_transactions.index') }}"
                                    class="flex items-center @yield('topup_transactions')">
                                    <i class="fal fa-tasks pr-3"></i>
                                    Deposit Transactions
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('cash_withdrawal_transactions.index') }}"
                                    class="flex items-center @yield('withdrawal_transactions')">
                                    <i class="fal fa-tasks pr-3"></i>
                                    Withdrawal Transactions
                                </a>
                            </li>
                        @endif
                    @endif
                    @if (Auth::guard('agent')->check() || (Auth::guard('web')->check() && checkUserPermission('setting')))
                        <li>
                            <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                                data-twe-ripple-color="light" href="#agentCollapse" role="button" aria-expanded="false"
                                aria-controls="agentCollapse">
                                <i class="fal fa-user !w-fit pl-1"></i>
                                Agents
                                <i class="fas fa-caret-down absolute right-2"></i>
                            </a>
                        </li>
                        <li>
                            <div class="!visible @yield('agent-block')hidden text-center bg-neutral-800 pb-4" id="agentCollapse"
                                @yield('agent-collapse') data-twe-collapse-item>
                                <ul class=" mb-4">
                                    @if (Auth::guard('web')->check() && checkUserPermission('setting'))
                                        <li>
                                            <a href="{{ route('agent_lists') }}"
                                                class="flex !pl-10 items-center @yield('agent_lists')">
                                                <i class="fal fa-book-user pr-2"></i>
                                                Agents
                                            </a>
                                        </li>
                                    @endif
                                    @if(Auth::guard('agent')->check() || (Auth::guard('web')->check() && checkUserPermission('setting')))
                                        <li>
                                            <a href="{{ route('agents_users') }}"
                                                class="flex !pl-10 items-center @yield('agents_users')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                Agent's Users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/game_transitions/2D"
                                                class="flex !pl-10 items-center @yield('game_transitions2D')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                2D Transcations
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/game_transitions/3D"
                                                class="flex !pl-10 items-center @yield('game_transitions3D')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                3D Transcations
                                            </a>
                                        </li>


                                        <li>
                                            <a href="/agents_commission"
                                                class="flex !pl-10 text-left items-center @yield('agents_commission')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                <span>
                                                    Agents Commission
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/agent_wallets" class="flex !pl-10 items-center @yield('agent_wallets')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                Agent Wallets
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/agent_transcations_status"
                                                class="flex !pl-10 items-center @yield('agents_transcations_status')">
                                                <i class="fal fa-money-check-edit-alt pr-2"></i>
                                                Agent Withdrawals
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    <!-- <li>
                        <a href="/logout" class="flex items-center"
                            onclick="event.preventDefault(); localStorage.clear(); window.location.href = '/logout';">
                            <i class="fal fa-sign-out-alt pr-3"></i>
                            Log out
                        </a>
                    </li> -->
                </ul>
            </div>
        </div>
    </div>
</nav>