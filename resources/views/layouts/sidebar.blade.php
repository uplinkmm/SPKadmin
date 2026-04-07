<nav class="side-bar w-fit pt-0 min-h-[100vh] h-full z-40">
    <div class="relative">
        <button type="button" id="toggleBtn"
            class="py-3 px-2 absolute left-full top-5 bg-[#202020] text-white rounded-tr-md rounded-br-md ">
            <i class="fas fa-chevron-double-left ease-linear" style="transition:transform 0.5s ease;"></i>
        </button>
        <div id="sidebar" class="relative pb-12 overflow-y-hidden hidden-scrollbar h-[100vh] w-60"
            onmouseover="this.style.overflowY='scroll'" onmouseout="this.style.overflowY='hidden'"
            style="transition: width 0.3s;">

            @php
            // $isWebAuthenticated = Auth::guard('web');
            // $isAgentAuthenticated = Auth::guard('agent')->check();
            // dd([$isWebAuthenticated,$isAgentAuthenticated]);
            @endphp
            <div class="relative w-[15rem] pt-12">

                <ul class=" mb-4 ">
                    @if (Auth::guard('web')->check())

                    @if(Auth::guard('web')->user()->isSuperAdmin() || (Auth::guard('web')->user()->isAdmin() && checkUserPermission('2d')))

                    <li>
                        <a class="flex gap-x-4 items-center @yield('accounting')" data-twe-collapse-init
                            data-twe-ripple-init data-twe-ripple-color="light" href="#collapseExample" role="button"
                            aria-expanded="false" aria-controls="collapseExample">
                            <!-- <span class="text-xs border border-white px-2 py-0.5 mr-3">2D</span> -->
                            <!-- <img class="w-4 h-4" src="{{ asset('img/2d.png') }}" alt=""> -->
                            <i class="fas fa-sack-dollar !w-fit pl-1"></i>
                            2D
                            <i class="fas fa-caret-down absolute right-2"></i>
                        </a>
                    </li>

                    <li>
                        <div class="!visible @yield('2d-block')hidden text-center bg-neutral-800 pb-4"
                            id="collapseExample" @yield('2d-collapse') data-twe-collapse-item>
                            <ul class=" mb-4">
                                <li>
                                    <a href="{{ route('twod_reports.bettings_overview.index') }}"
                                        class="flex !pl-10 items-center @yield('twod_betting_overview')">
                                        <i class="fas fa-th pr-2"></i>
                                        2D Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('twod_reports.betting_amounts.index') }}"
                                        class="flex !pl-10 items-center @yield('twod_reports.betting_amounts.index')">
                                        <i class="fas fa-hand-holding-usd pr-2"></i>
                                        2D Table

                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('twod_reports.customer_bets.index') }}"
                                        class="flex !pl-10 items-center @yield('twod_Report')">
                                        <i class="fas fa-user-chart pr-2"></i>
                                        2D Report
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('twod_reports.bet_list.index') }}"
                                        class="flex !pl-10 items-center @yield('twod_bet_list')">
                                        <i class="fas fa-clipboard-list pr-2"></i>
                                        2D Bet List
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('twod_reports.winner_list.index') }}"
                                        class="flex !pl-10 items-center @yield('twod_winner')">
                                        <i class="fas fa-user-check pr-2"></i>
                                        2D Winners
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('twod_reports.winning_numbers.index') }}"
                                        class="flex !pl-10 items-center @yield('twod_winning_number')">
                                        <i class="fas fa-trophy pr-2"></i>
                                        2D Results
                                    </a>
                                </li>
                                @if(Auth::guard('web')->user()->isSuperAdmin())
                                <li>
                                    <a href="{{ route('twod_reports.closing_numbers.index') }}"
                                        class="flex !pl-10 items-center @yield('twod_close_list')">
                                        <i class="fas fa-toggle-off pr-2"></i>
                                        2D Settings
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(Auth::guard('web')->user()->isSuperAdmin() || (Auth::guard('web')->user()->isAdmin() && checkUserPermission('3d')))

                    <li>
                        <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                            data-twe-ripple-color="light" href="#collapseExample2" role="button" aria-expanded="false"
                            aria-controls="collapseExample2">
                            <i class="fas fa-sack-dollar !w-fit pl-1"></i>
                            3D
                            <i class="fas fa-caret-down absolute right-2"></i>
                        </a>
                    </li>

                    <li>
                        <div class="!visible @yield('3d-block')hidden text-center bg-neutral-800 pb-4"
                            id="collapseExample2" @yield('3d-collapse') data-twe-collapse-item>
                            <ul class=" mb-4">
                                <li>
                                    <a href="{{ route('threed_reports.betting_overview.index') }}"
                                        class="!pl-10 flex items-center @yield('threed_overview')">
                                        <i class="fas fa-th pr-2"></i>
                                        3D Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('threed_reports.betting_amounts.index') }}"
                                        class="!pl-10 flex items-center @yield('threed_betting_amount')">
                                        <i class="fas fa-hand-holding-usd pr-2"></i>
                                        3D Table

                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('threed_reports.customer_bets.index') }}"
                                        class="!pl-10 flex items-center @yield('threed_customer_bets')">
                                        <i class="fas fa-user-chart pr-2"></i>
                                        3D Report
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('threed_reports.bet_list.index') }}"
                                        class="!pl-10 flex items-center @yield('threed_bet_list')">
                                        <i class="fas fa-clipboard-list pr-2"></i>
                                        3D Bet List
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('threed_reports.winner_list.index') }}"
                                        class="!pl-10 flex items-center @yield('threed_winning_number')">
                                        <i class="fas fa-user-check pr-2"></i>
                                        3D Winners
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('threed_reports.winning_numbers.index') }}"
                                        class="!pl-10 flex items-center @yield('threed_winning_numbers')">
                                        <i class="fas fa-trophy pr-2"></i>
                                        3D Results
                                    </a>
                                </li>
                                <!-- <li>
                                                                                                            <a href="{{ route('threed_reports.game_setting.index') }}"
                                                                                                                class="!pl-10 flex items-center @yield('threed_reports.game_setting.index')">
                                                                                                                <i class="fas fa-cogs pr-3"></i>
                                                                                                                3D Game Setting
                                                                                                            </a>
                                                                                                        </li> -->
                                @if(Auth::guard('web')->user()->isSuperAdmin())
                                <li>
                                    <a href="/threeclosing"
                                        class="!pl-10 flex items-center @yield('threed_close_list')">
                                        <i class="fas fa-cogs pr-3"></i>
                                        3D Setting
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @if(Auth::guard('web')->user()->isSuperAdmin() || (Auth::guard('web')->user()->isAdmin() && checkUserPermission('slot')))
                    <li>
                        <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                            data-twe-ripple-color="light" href="#slotCollapse" role="button" aria-expanded="false"
                            aria-controls="slotCollapse">
                            <i class="fas fa-dice !w-fit pl-1"></i>
                            Slots
                            <i class="fas fa-caret-down absolute right-2"></i>
                        </a>
                    </li>

                    <li>
                        <div class="!visible @yield('slot-block')hidden text-left bg-neutral-800 pb-2"
                            id="slotCollapse" @yield('slot-collapse') data-twe-collapse-item>
                            <ul class="mb-2 space-y-1">
                                <li>
                                    <a href="{{ route('slot_game_lists') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('slot_game_lists')">
                                        <i class="fas fa-th-large pr-2"></i>
                                        Slot Games Lists
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('slot_transcation') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('slot_list')">
                                        <i class="fas fa-receipt pr-2"></i>
                                        Slot Transcations
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('buffalo_transcation') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('buffalo-transcation')">
                                        <i class="fas fa-cow pr-2"></i>
                                        Buffalo Transcations
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('provider_report') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('provider_report')">
                                        <i class="fas fa-file-alt pr-2"></i>
                                        Provider Report
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('slot_user_lists') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('slot_user_lists')">
                                        <i class="fas fa-users pr-2"></i>
                                        Slot Users</a>
                                </li>
                                <li>
                                    <a href="{{ route('user_report') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('user_report')">
                                        <i class="fas fa-clipboard-list pr-2"></i>
                                        User Report
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                            data-twe-ripple-color="light" href="#drawCollapse" role="button" aria-expanded="false"
                            aria-controls="drawCollapse">
                            <i class="fas fa-ticket-alt !w-fit pl-1"></i>
                            Lottery Game Lists
                            <i class="fas fa-caret-down absolute right-2"></i>
                        </a>
                    </li>

                    <li>
                        <div class="!visible @yield('draw-block')hidden text-left bg-neutral-800 pb-2" id="drawCollapse"
                            @yield('draw-collapse') data-twe-collapse-item>
                            <ul class="mb-2 space-y-1">
                                <li>
                                    <a href="{{ route('draw_game_lists') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('draw_game_lists')">
                                        <i class="fas fa-list-ul pr-2"></i>
                                        Lottery Games Lists
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('draw_promotion_lists') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('draw_promotion_lists')">
                                        <i class="fas fa-tags pr-2"></i>
                                        Lottery Games Promotions
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('draw_game_results') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('draw_result_lists')">
                                        <i class="fas fa-chart-line pr-2"></i>Lottery Games Results
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('draw_user_betting_lists') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('draw_user_betting_lists')">
                                        <i class="fas fa-clipboard-list pr-2"></i>Lottery User Betting Lists
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('draw_winner_lists') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('draw_winner_lists')">
                                        <i class="fas fa-trophy pr-2"></i>Lottery Winners Lists
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(Auth::guard('web')->user()->isSuperAdmin() || (Auth::guard('web')->user()->isAdmin() && checkUserPermission('setting')))
                    <li>
                        <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                            data-twe-ripple-color="light" href="#userCollapse" role="button" aria-expanded="false"
                            aria-controls="userCollapse">
                            <i class="fas fa-users !w-fit pl-1"></i>
                            User
                            <i class="fas fa-caret-down absolute right-2"></i>
                        </a>
                    </li>

                    <li>
                        <div class="!visible @yield('user-block')hidden text-left bg-neutral-800 pb-2"
                            id="userCollapse" @yield('user-collapse') data-twe-collapse-item>
                            <ul class="mb-2 space-y-1">
                                <li>
                                    <a href="{{ route('users.index') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('user_list')">
                                        <i class="fas fa-user pr-2"></i>
                                        User
                                    </a>
                                </li>
                                <!-- <li>
                                    <a href="{{ route('users.limit_user') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('limit_user')">
                                        <i class="fas fa-ban pr-2"></i>
                                        Limit User

                                    </a>
                                </li> -->
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                            data-twe-ripple-color="light" href="#promotionsCollapse" role="button" aria-expanded="false"
                            aria-controls="promotionsCollapse">
                            <i class="fas fa-bullhorn !w-fit pl-1"></i>
                            Promotions
                            <i class="fas fa-caret-down absolute right-2"></i>
                        </a>
                    </li>

                    <li>
                        <div class="!visible @yield('promotions-block')hidden text-left bg-neutral-800 pb-2"
                            id="promotionsCollapse" @yield('promotions-collapse') data-twe-collapse-item>
                            <ul class="mb-2 space-y-1">
                                <li>
                                    <a href="{{ route('promotions') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('promotions')">
                                        <i class="fas fa-bullhorn pr-2"></i>
                                        Promotions
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('promotions.new_user_bonus') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('new_user_bonus')">
                                        <i class="fas fa-gift pr-2"></i>
                                        New User Bonus

                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('referral_promotions') }}"
                                        class="flex items-center gap-2 !pl-8 py-2 text-[15px] leading-5 text-gray-100 hover:text-white hover:bg-neutral-700 rounded @yield('referral_promotions')">
                                        <i class="fas fa-user-friends pr-2"></i>
                                        Referral Promotions

                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('settings') }}" class="flex items-center @yield('settings')">
                            <i class="fas fa-cog pr-3"></i>
                            Settings
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('payment_providers.index') }}"
                            class="flex items-center @yield('payment_providers')">
                            <i class="fas fa-credit-card pr-3"></i>
                            Payment Providers
                        </a>
                    </li>
                    @endif
                    @if(Auth::guard('web')->user()->isSuperAdmin() || (Auth::guard('web')->user()->isAdmin() && checkUserPermission('transaction')))

                    <li>
                        <a href="{{ route('topup_transactions.index') }}"
                            class="flex items-center @yield('topup_transactions')">
                            <i class="fas fa-arrow-down pr-3"></i>
                            Deposit Transactions
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('cash_withdrawal_transactions.index') }}"
                            class="flex items-center @yield('withdrawal_transactions')">
                            <i class="fas fa-arrow-up pr-3"></i>
                            Withdrawal Transactions
                        </a>
                    </li>
                    @endif
                    @if(Auth::guard('web')->user()->isSuperAdmin() || (Auth::guard('web')->user()->isAdmin() && checkUserPermission('setting')))
                    <li>
                        <a href="{{ route('balance_transactions.index') }}"
                            class="flex items-center @yield('balance_transactions.index')">
                            <i class="fas fa-balance-scale pr-3"></i>
                            Balance Transactions
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('history.deposit') }}" class="flex items-center @yield('history.deposit')">
                            <i class="fas fa-history pr-3"></i>
                            Deposit Histories
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('history.withdrawal') }}"
                            class="flex items-center @yield('history.withdrawal')">
                            <i class="fas fa-history pr-3"></i>
                            Withdrawal Histories
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('games') }}" class="flex items-center @yield('games')">
                            <i class="fas fa-gamepad pr-2"></i>
                            Games
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ads_lists', ['type' => 'ads']) }}"
                            class="flex items-center {{ request()->routeIs('ads_lists') && request('type', 'ads') !== 'promotion' ? 'active-link' : '' }}">
                            <i class="fas fa-ad pr-2"></i>
                            Ads Lists
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ads_lists', ['type' => 'promotion']) }}"
                            class="flex items-center {{ request()->routeIs('ads_lists') && request('type') === 'promotion' ? 'active-link' : '' }}">
                            <i class="fas fa-bullhorn pr-2"></i>
                            Promotion Lists
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin_users') }}" class="flex items-center @yield('admin_lists')">
                            <i class="fas fa-user-shield pr-2"></i>
                            Admins
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('TermsAndConditions') }}"
                            class="flex items-center @yield('TermsAndConditions')">
                            <i class="fas fa-file-contract pr-2"></i>
                            Terms And Conditions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('deposit_withdraw_tutorials') }}"
                            class="flex items-center @yield('deposit_withdraw_tutorials')">
                            <i class="fas fa-book-open pr-2"></i>
                            Deposit & Withdraw Tutorials Links
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact_us_links') }}"
                            class="flex items-center @yield('contact_us_links')">
                            <i class="fas fa-envelope-open-text pr-2"></i>
                            Contact Us Links
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contacts') }}" class="flex items-center @yield('contacts')">
                            <i class="fas fa-address-book pr-2"></i>
                            Contacts
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('feedbacks') }}" class="flex items-center @yield('feedbacks')">
                            <i class="fas fa-comment-dots pr-2"></i>
                            Feedbacks
                        </a>
                    </li>
                    @endif

                    <!-- @if(Auth::guard('web')->user()->isSuperAdmin() || (Auth::guard('web')->user()->isAdmin() && checkUserPermission('transaction') && !checkUserPermission('setting')))

                            <li>
                                <a href="{{ route('topup_transactions.index') }}"
                                    class="flex items-center @yield('topup_transactions')">
                                    <i class="fas fa-tasks pr-3"></i>
                                    Deposit Transactions
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('cash_withdrawal_transactions.index') }}"
                                    class="flex items-center @yield('withdrawal_transactions')">
                                    <i class="fas fa-tasks pr-3"></i>
                                    Withdrawal Transactions
                                </a>
                            </li>
                        @endif -->
                    @endif
                    <!-- @if (Auth::guard('agent')->check() || (Auth::guard('web')->check() && checkUserPermission('setting')))
                        <li>
                            <a class="flex gap-x-4 items-center" data-twe-collapse-init data-twe-ripple-init
                                data-twe-ripple-color="light" href="#agentCollapse" role="button" aria-expanded="false"
                                aria-controls="agentCollapse">
                                <i class="fas fa-user !w-fit pl-1"></i>
                                Agents
                                <i class="fas fa-caret-down absolute right-2"></i>
                            </a>
                        </li>
                        <li>
                            <div class="!visible @yield('agent-block')hidden text-center bg-neutral-800 pb-4"
                                id="agentCollapse" @yield('agent-collapse') data-twe-collapse-item>
                                <ul class=" mb-4">
                                    @if (Auth::guard('web')->check() && checkUserPermission('setting'))
                                        <li>
                                            <a href="{{ route('agent_lists') }}"
                                                class="flex !pl-10 items-center @yield('agent_lists')">
                                                <i class="fas fa-book-user pr-2"></i>
                                                Agents
                                            </a>
                                        </li>
                                    @endif
                                    @if(Auth::guard('agent')->check() || (Auth::guard('web')->check() && checkUserPermission('setting')))
                                        <li>
                                            <a href="{{ route('agents_users') }}"
                                                class="flex !pl-10 items-center @yield('agents_users')">
                                                <i class="fas fa-money-check-edit-alt pr-2"></i>
                                                Agent's Users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/game_transitions/2D"
                                                class="flex !pl-10 items-center @yield('game_transitions2D')">
                                                <i class="fas fa-money-check-edit-alt pr-2"></i>
                                                2D Transcations
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/game_transitions/3D"
                                                class="flex !pl-10 items-center @yield('game_transitions3D')">
                                                <i class="fas fa-money-check-edit-alt pr-2"></i>
                                                3D Transcations
                                            </a>
                                        </li>


                                        <li>
                                            <a href="/agents_commission"
                                                class="flex !pl-10 text-left items-center @yield('agents_commission')">
                                                <i class="fas fa-money-check-edit-alt pr-2"></i>
                                                <span>
                                                    Agents Commission
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/agent_wallets" class="flex !pl-10 items-center @yield('agent_wallets')">
                                                <i class="fas fa-money-check-edit-alt pr-2"></i>
                                                Agent Wallets
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/agent_transcations_status"
                                                class="flex !pl-10 items-center @yield('agents_transcations_status')">
                                                <i class="fas fa-money-check-edit-alt pr-2"></i>
                                                Agent Withdrawals
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif -->
                </ul>
            </div>
        </div>
    </div>
</nav>