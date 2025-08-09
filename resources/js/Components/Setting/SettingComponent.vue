<template>
    <div class="frame-container min-h-[100vh]">
        <div class="rounded-md bg-white px-4 pt-4 pb-8 mb-6 shadow-md">
            <div>
                <p class="font-semibold font-inter text-black mb-3">
                    Dashboard CRM
                </p>
            </div>
            <div class="overflow-hidden">
                <table
                    class="min-w-full text-left text-sm font-inter text-black border-neutral-200 border-t"
                >
                    <tbody>
                        <tr
                            class="border-b border-r border-l border-neutral-200"
                        >
                            <td
                                class="whitespace-nowrap px-6 py-4 font-medium text-center border-r"
                            >
                                Users
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                {{ dashboard?.total_customer }}
                            </td>
                        </tr>
                        <tr
                            class="border-b border-r border-l border-neutral-200"
                        >
                            <td
                                class="whitespace-nowrap px-6 py-4 font-medium text-center border-r"
                            >
                                Active Users
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                {{ dashboard.active_customer }}
                            </td>
                        </tr>
                        <tr
                            class="border-b border-r border-l border-neutral-200"
                        >
                            <td
                                class="whitespace-nowrap px-6 py-4 font-medium text-center border-r"
                            >
                                Deposits
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                {{
                                    dashboard.total_topup_amount?.toLocaleString()
                                }}
                            </td>
                        </tr>
                        <tr
                            class="border-b border-r border-l border-neutral-200"
                        >
                            <td
                                class="whitespace-nowrap px-6 py-4 font-medium text-center border-r"
                            >
                                Withdrawls
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                {{
                                    dashboard.total_withdrawl_amount?.toLocaleString()
                                }}
                            </td>
                        </tr>
                        <tr
                            class="border-b border-r border-l border-neutral-200"
                        >
                            <td
                                class="whitespace-nowrap px-6 py-4 font-medium text-center border-r"
                            >
                                User Balance
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                {{
                                    dashboard?.total_wallet_balance?.toLocaleString()
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white px-4 pt-4 pb-8 rounded-md mb-6 shadow-md">
            <div>
                <p class="font-semibold font-inter text-black mb-3">
                    Financial Report
                </p>
            </div>
            <div class="flex justify-start gap-x-4 mb-4">
                <div>
                    <label for="" class="text-sm">Start</label>
                    <VueDatePicker
                        v-model="from_date"
                        :enable-time-picker="false"
                        auto-apply
                        class="mr-3"
                        @update:model-value="getFianancialReport"
                        format="dd/MM/yyyy"
                    ></VueDatePicker>
                </div>
                <div>
                    <label for="" class="text-sm">End</label>
                    <VueDatePicker
                        v-model="to_date"
                        :enable-time-picker="false"
                        auto-apply
                        class="mr-3"
                        @update:model-value="getFianancialReport"
                        format="dd/MM/yyyy"
                    ></VueDatePicker>
                </div>
            </div>
            <div class="overflow-x-auto">
                <div class="overflow-hidden">
                    <table
                        class="min-w-full text-left text-sm font-inter text-black"
                    >
                        <thead
                            class="border-b border-t border-l border-neutral-200 font-medium"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Pay Acc
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Deposit
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Withdrawl
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Profit & loss
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(report, index) in fianancial_report"
                                :key="index"
                                class="border-b border-l border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100"
                            >
                                <td
                                    class="whitespace-nowrap px-6 py-4 font-medium border-r"
                                >
                                    {{ report.account_name }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ report.total_topup_count }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{
                                        report.total_topup_amount?.toLocaleString()
                                    }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ report.total_withdrawal_count }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{
                                        report.total_withdrawal_amount?.toLocaleString()
                                    }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{
                                        report.total_topup_amount -
                                        report.total_withdrawal_amount
                                    }}
                                </td>
                            </tr>

                            <tr
                                class="border-b border-neutral-200 transition duration-300 ease-in-out bg-neutral-100"
                            >
                                <td
                                    class="whitespace-nowrap px-6 py-4 font-medium"
                                >
                                    Total :
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ totals?.totalTopupCount }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{
                                        totals?.totalTopupAmount?.toLocaleString()
                                    }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ totals?.totalWithdrawalCount }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{
                                        totals?.totalWithdrawalAmount?.toLocaleString()
                                    }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ totals?.totalProfit }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div
            class="flex flex-col bg-white px-4 pt-4 pb-8 mb-6 rounded-md shadow-md"
        >
            <div>
                <p class="font-semibold font-inter text-black mb-3">
                    Deposit / Withdrawal SMS Ph Control
                </p>
            </div>
            <div class="overflow-x-auto">
                <div class="overflow-hidden">
                    <table
                        class="min-w-full text-left text-sm font-inter text-black"
                    >
                        <thead
                            class="border-b border-t border-l border-neutral-200 font-medium"
                        >
                            <tr>
                                <th
                                    scope="col"
                                    colspan="2"
                                    class="px-6 py-4 border-r text-center"
                                >
                                    Deposit
                                </th>
                                <th
                                    scope="col"
                                    colspan="2"
                                    class="px-6 py-4 border-r text-center"
                                >
                                    Withdrawal
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    SMS Phone
                                </th>
                                <!-- <th scope="col" class="px-6 py-4 border-r">
                                    Current Date
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Current Time
                                </th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-l border-neutral-200">
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    Min
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    Max
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    Min
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    Max
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    Phone Number
                                </td>
                                <!-- <td
                                    rowspan="3"
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    26/6/2024
                                </td>
                                <td
                                    rowspan="3"
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    00:00
                                </td> -->
                            </tr>

                            <tr class="border-b border-l border-neutral-200">
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'transaction_control',
                                                'min',
                                                transaction_control_deposit.id,
                                                transaction_control_deposit?.min,
                                                'Enter Min Deposit Amount'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                transaction_control_deposit?.min?.toLocaleString()
                                            }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'transaction_control',
                                                'max',
                                                transaction_control_deposit.id,
                                                transaction_control_deposit?.max,
                                                'Enter Max Deposit Amount'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                transaction_control_deposit?.max?.toLocaleString()
                                            }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'transaction_control',
                                                'min',
                                                transaction_control_withdrawal.id,
                                                transaction_control_withdrawal?.min,
                                                'Enter Min Withdrawal Amount'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                transaction_control_withdrawal?.min?.toLocaleString()
                                            }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'transaction_control',
                                                'max',
                                                transaction_control_withdrawal.id,
                                                transaction_control_withdrawal?.max,
                                                'Enter Max Withdrawal Amount'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                transaction_control_withdrawal?.max?.toLocaleString()
                                            }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    rowspan="2"
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    Empty
                                </td>
                            </tr>
                            <tr class="border-b border-l border-neutral-200">
                                <td
                                    colspan="2"
                                    class="whitespace-nowrap px-6 py-4 border-r text-center"
                                >
                                    <div
                                        v-if="transaction_control_deposit"
                                        class="relative inline-block w-24 h-10"
                                    >
                                        <input
                                            type="checkbox"
                                            id="toggle_deposit"
                                            class="hidden"
                                            @input="
                                                transactionControlIsactive(
                                                    'deposit',
                                                    !transaction_control_deposit.is_active,
                                                    transaction_control_deposit.id
                                                )
                                            "
                                        />
                                        <label
                                            for="toggle_deposit"
                                            class="block cursor-pointer bg-gray-300 rounded-md p-1 relative flex items-center justify-between"
                                            :class="{
                                                'bg-green-500':
                                                    transaction_control_deposit.is_active,
                                                'bg-gray-300':
                                                    !transaction_control_deposit.is_active,
                                            }"
                                        >
                                            <span
                                                class="absolute left-2/4 top-1/2 transform -translate-y-1/2 text-xs font-bold text-white"
                                                v-if="
                                                    !transaction_control_deposit.is_active
                                                "
                                                >Disable</span
                                            >
                                            <span
                                                class="absolute left-1/4 top-1/2 transform -translate-y-1/2 text-xs font-bold text-white"
                                                v-if="
                                                    transaction_control_deposit.is_active
                                                "
                                                >Enable</span
                                            >
                                            <span
                                                class="block w-5 h-8 bg-white rounded-md shadow transform transition-transform"
                                                :class="{
                                                    'translate-x-16':
                                                        transaction_control_deposit.is_active,
                                                }"
                                            >
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td
                                    colspan="2"
                                    class="whitespace-nowrap px-6 py-4 border-r text-center"
                                >
                                    <div
                                        v-if="
                                            transaction_control_withdrawal != ''
                                        "
                                        class="relative inline-block w-24 h-10"
                                    >
                                        <input
                                            type="checkbox"
                                            id="togglewithdrawal"
                                            class="hidden"
                                            @input="
                                                transactionControlIsactive(
                                                    'withdrawal',
                                                    !transaction_control_withdrawal.is_active,
                                                    transaction_control_withdrawal.id
                                                )
                                            "
                                        />
                                        <label
                                            for="togglewithdrawal"
                                            class="block cursor-pointer bg-gray-300 rounded-md p-1 relative flex items-center justify-between"
                                            :class="{
                                                'bg-green-500':
                                                    transaction_control_withdrawal.is_active,
                                                'bg-gray-300':
                                                    !transaction_control_withdrawal.is_active,
                                            }"
                                        >
                                            <span
                                                class="absolute left-2/4 top-1/2 transform -translate-y-1/2 text-xs font-bold text-white"
                                                v-if="
                                                    !transaction_control_withdrawal.is_active
                                                "
                                                >Disable</span
                                            >
                                            <span
                                                class="absolute left-1/4 top-1/2 transform -translate-y-1/2 text-xs font-bold text-white"
                                                v-if="
                                                    transaction_control_withdrawal.is_active
                                                "
                                                >Enable</span
                                            >
                                            <span
                                                class="block w-5 h-8 bg-white rounded-md shadow transform transition-transform"
                                                :class="{
                                                    'translate-x-16':
                                                        transaction_control_withdrawal.is_active,
                                                }"
                                            >
                                            </span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div
            class="flex flex-col bg-white px-4 pt-4 pb-8 mb-6 rounded-md shadow-md"
        >
            <div class="flex">
                <p class="font-semibold font-inter text-black mb-3">
                    System Control
                </p>
                <button
                    type="button"
                    data-twe-toggle="modal"
                    data-twe-target="#game_setting"
                    data-twe-ripple-init
                    data-twe-ripple-color="light"
                    class="rounded ml-6 mb-4 bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                >
                    Add
                </button>
            </div>
            <div class="overflow-x-auto">
                <div>
                    <table
                        class="min-w-full text-left text-sm font-inter text-black"
                    >
                        <thead
                            class="border-b border-t border-l border-neutral-200 font-medium"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Digital ( 2D / 3D)
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Odds
                                </th>

                                <th scope="col" class="px-6 py-4 border-r">
                                    Twit
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Opening Time
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Closing Time
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Lottery Time
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Min Bet
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Max Bet
                                </th>

                                <th scope="col" class="px-6 py-4 border-r">
                                    Closing Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(control, index) in system_control"
                                :key="index"
                                class="border-b border-l border-neutral-200"
                            >
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <div
                                        class="relative inline-block w-24 h-10"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="`toggle${control.id}`"
                                            class="hidden"
                                            @input="
                                                systemControlToggleBtn(
                                                    control.id,
                                                    !control.is_active
                                                )
                                            "
                                        />
                                        <label
                                            :for="`toggle${control.id}`"
                                            class="block cursor-pointer bg-gray-300 rounded-md p-1 relative flex items-center justify-between"
                                            :class="{
                                                'bg-green-500':
                                                    control.is_active,
                                                'bg-gray-300':
                                                    !control.is_active,
                                            }"
                                        >
                                            <span
                                                class="absolute left-2/4 top-1/2 transform -translate-y-1/2 text-xs font-bold text-white"
                                                v-if="!control.is_active"
                                                >Close</span
                                            >
                                            <span
                                                class="absolute left-1/4 top-1/2 transform -translate-y-1/2 text-xs font-bold text-white"
                                                v-if="control.is_active"
                                                >Open</span
                                            >
                                            <span
                                                class="block w-5 h-8 bg-white rounded-md shadow transform transition-transform"
                                                :class="{
                                                    'translate-x-16':
                                                        control.is_active,
                                                }"
                                            >
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ control.name }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ control.game_name }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ control.type }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'bet_multiplier',
                                                control.id,
                                                control.bet_multiplier,
                                                'Enter Multiplier',
                                                'number'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{ control.bet_multiplier }}
                                        </span>
                                    </button>
                                </td>

                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        v-if="control.type == '3d'"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'twist_multiplier',
                                                control?.id,
                                                control?.twist_multiplier,
                                                'Enter Twist Multiplier',
                                                'number'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                control.type == "3d"
                                                    ? control?.twist_multiplier
                                                    : ""
                                            }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'opening_time',
                                                control.id,
                                                control.opening_time,
                                                'Enter Opening Time',
                                                'time'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                formatTime(control.opening_time)
                                            }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'closing_time',
                                                control.id,
                                                control.closing_time,
                                                'Enter Closing Time',
                                                'time'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                formatTime(control.closing_time)
                                            }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'lottery_time',
                                                control.id,
                                                control.lottery_time,
                                                'Enter Lottery Time',
                                                'time'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                formatTime(control.lottery_time)
                                            }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'min',
                                                control.id,
                                                control.min,
                                                'Enter Min Amount',
                                                'number'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{ control.min }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'max',
                                                control.id,
                                                control.max,
                                                'Enter Max Amount',
                                                'number'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{ control.max }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'closing_amount',
                                                control.id,
                                                control.closing_amount,
                                                'Enter Closing Amount',
                                                'number'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                control.closing_amount?.toLocaleString()
                                            }}
                                        </span>
                                    </button>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                ></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div>
                <p class="font-semibold font-inter text-black mb-3">
                    3D Setting
                </p>
            </div>
            <div class="overflow-x-auto">
                <div class="overflow-hidden">
                    <table
                        class="min-w-full text-left text-sm font-inter text-black"
                    >
                        <thead
                            class="border-b border-t border-l border-neutral-200 font-medium"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Opening Time
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Closing Time
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Lottery Time
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-l border-neutral-200">
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        v-if="threed_setting"
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'opening_date_time',
                                                threed_setting.id,
                                                threed_setting.opening_date_time,
                                                'Enter Date',
                                                'datetime-local'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                formatDate(
                                                    threed_setting.opening_date_time
                                                )
                                            }}
                                        </span>
                                    </button>
                                    <p v-else>-</p>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        v-if="threed_setting"
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'closing_date_time',
                                                threed_setting.id,
                                                threed_setting.closing_date_time,
                                                'Enter Date',
                                                'datetime-local'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                formatDate(
                                                    threed_setting.closing_date_time
                                                )
                                            }}
                                        </span>
                                    </button>
                                    <p v-else>-</p>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        v-if="threed_setting"
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#deposit_min_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            controlUpdateBtn(
                                                'system_control',
                                                'lottery_date_time',
                                                threed_setting.id,
                                                threed_setting.lottery_date_time,
                                                'Enter Date',
                                                'datetime-local'
                                            )
                                        "
                                    >
                                        <span class="underline-dotted">
                                            {{
                                                formatDate(
                                                    threed_setting.lottery_date_time
                                                )
                                            }}
                                        </span>
                                    </button>
                                    <p v-else>-</p>
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#edit_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                    >
                                        Create New Setting
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deposit_min_modal"
        tabindex="-1"
        aria-labelledby="ModalLabel"
        aria-hidden="true"
    >
        <div
            data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
        >
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none"
            >
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4"
                >
                    <h5
                        class="text-xl font-medium leading-normal text-surface"
                        id="ModalLabel"
                    >
                        {{ edit_value.title }}
                    </h5>
                    <button
                        type="button"
                        id="closeModal"
                        class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-twe-modal-dismiss
                        aria-label="Close"
                    >
                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </span>
                    </button>
                </div>
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6" v-if="edit_value.input_type == 'time'">
                        <label for="" class="text-sm mb-3 relative block">
                            Time
                        </label>
                        <VueDatePicker
                            v-model="edit_value.value"
                            time-picker
                            :is-24="false"
                            auto-apply
                        />
                    </div>
                    <div
                        class="mb-6"
                        v-if="edit_value.input_type == 'datetime-local'"
                    >
                        <label for="" class="text-sm mb-3 relative block">
                            Date
                        </label>
                        <!-- <input
                            type="datetime-local"
                            :placeholder="edit_value.title"
                            v-model="edit_value.value"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        /> -->
                        <VueDatePicker
                            v-model="edit_value.value"
                            :is-24="false"
                            auto-apply
                            format="dd/MM/yyyy hh:mm a"
                        />
                    </div>
                    <div class="mb-6" v-if="edit_value.input_type == 'number'">
                        <label for="" class="text-sm mb-3 relative block"
                            >Amount</label
                        >
                        <input
                            type="number"
                            :placeholder="edit_value.title"
                            v-model="edit_value.value"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                </div>
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4"
                >
                    <button
                        type="button"
                        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black focus:outline-none focus:ring-00"
                        data-twe-modal-dismiss
                        data-twe-ripple-init
                        id="deposit_min_modal_close"
                        data-twe-ripple-color="light"
                    >
                        Close
                    </button>
                    <button
                        type="button"
                        :disabled="loading"
                        @click="updateControl"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        {{ loading ? "Loading..." : "Done" }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit game setting modal -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="game_setting"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div
            data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
        >
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none"
            >
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4"
                >
                    <h5
                        class="text-xl font-medium leading-normal text-surface"
                        id="exampleModalLabel"
                    >
                        Add Game Setting
                    </h5>
                    <button
                        type="button"
                        id="closeAddModal"
                        class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-twe-modal-dismiss
                        aria-label="Close"
                    >
                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6">
                        <label
                            for="2d_games"
                            class="text-sm mb-3 relative block"
                            >Game</label
                        >
                        <select
                            id="2d_games"
                            v-model="edit_game_setting.game_id"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                        >
                            <option
                                v-for="game in twod_games"
                                :key="game.id"
                                :value="game.id"
                            >
                                {{ game.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="name" class="text-sm mb-3 relative block"
                            >Name</label
                        >
                        <input
                            type="text"
                            v-model="edit_game_setting.name"
                            id="name"
                            placeholder="Name"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label for="opening" class="text-sm mb-3 relative block"
                            >Opening Time</label
                        >
                        <VueDatePicker
                            id="opening"
                            v-model="edit_game_setting.opening_time"
                            time-picker
                            :is-24="false"
                            auto-apply
                        />
                    </div>
                    <div class="mb-6">
                        <label for="closing" class="text-sm mb-3 relative block"
                            >Closing Time</label
                        >

                        <VueDatePicker
                            id="closing"
                            v-model="edit_game_setting.closing_time"
                            time-picker
                            :is-24="false"
                            auto-apply
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="lottery_time"
                            class="text-sm mb-3 relative block"
                            >Lottery Time</label
                        >

                        <VueDatePicker
                            id="lottery_time"
                            v-model="edit_game_setting.lottery_time"
                            time-picker
                            :is-24="false"
                            auto-apply
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="multiplier"
                            class="text-sm mb-3 relative block"
                            >Bet Multiplier</label
                        >
                        <input
                            type="number"
                            v-model="edit_game_setting.bet_multiplier"
                            id="multiplier"
                            placeholder="Multiplier"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="closing_amount"
                            class="text-sm mb-3 relative block"
                            >Closing Amount</label
                        >
                        <input
                            type="number"
                            v-model="edit_game_setting.closing_amount"
                            id="closing_amount"
                            placeholder="closing amount"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="min_bet_amount"
                            class="text-sm mb-3 relative block"
                            >Min Bet Amount</label
                        >
                        <input
                            type="text"
                            v-model="edit_game_setting.min"
                            id="min_bet_amount"
                            placeholder="min"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="max_bet_amount"
                            class="text-sm mb-3 relative block"
                            >Max Bet Amount</label
                        >
                        <input
                            type="text"
                            v-model="edit_game_setting.max"
                            id="max_bet_amount"
                            placeholder="max"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                </div>
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4"
                >
                    <button
                        type="button"
                        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black focus:outline-none focus:ring-00"
                        data-twe-modal-dismiss
                        data-twe-ripple-init
                        data-twe-ripple-color="light"
                        id="close_game_setting"
                    >
                        Close
                    </button>
                    <button
                        :disabled="loading"
                        type="button"
                        @click="addGameSetting()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        {{ loading ? "Loading..." : "Create" }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- edit 3 setting modal -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="edit_modal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div
            data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
        >
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none"
            >
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4"
                >
                    <h5
                        class="text-xl font-medium leading-normal text-surface"
                        id="exampleModalLabel"
                    >
                        Add Game Setting
                    </h5>
                    <button
                        type="button"
                        id="closeAddModal"
                        class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-twe-modal-dismiss
                        aria-label="Close"
                    >
                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6">
                        <label for="3dname" class="text-sm mb-3 relative block"
                            >Name</label
                        >
                        <input
                            id="3dname"
                            type="text"
                            v-model="threed_game_setting.name"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                        />
                    </div>
                    <div class="mb-6">
                        <label for="opening" class="text-sm mb-3 relative block"
                            >Opening Date Time</label
                        >
                        <!-- <input
                            type="datetime-local"
                            v-model="threed_game_setting.opening_date_time"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                        /> -->
                        <VueDatePicker
                            v-model="threed_game_setting.opening_date_time"
                            :is-24="false"
                            auto-apply
                            format="dd/MM/yyyy hh:mm a"
                        />
                    </div>
                    <div class="mb-6">
                        <label for="closing" class="text-sm mb-3 relative block"
                            >Closing Date Time</label
                        >

                        <VueDatePicker
                            v-model="threed_game_setting.closing_date_time"
                            :is-24="false"
                            auto-apply
                            format="dd/MM/yyyy hh:mm a"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="3dlotterydatetime"
                            class="text-sm mb-3 relative block"
                            >Lottery Date</label
                        >

                        <VueDatePicker
                            v-model="threed_game_setting.lottery_date_time"
                            :is-24="false"
                            auto-apply
                            format="dd/MM/yyyy hh:mm a"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="multiplier"
                            class="text-sm mb-3 relative block"
                            >Bet Multiplier</label
                        >
                        <input
                            type="text"
                            v-model="threed_game_setting.bet_multiplier"
                            id="multiplier"
                            placeholder="Multiplier"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="twist_multiplier"
                            class="text-sm mb-3 relative block"
                            >Twist Multiplier</label
                        >
                        <input
                            type="text"
                            v-model="threed_game_setting.twist_multiplier"
                            id="twist_multiplier"
                            placeholder="Twist Multiplier"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="closing_amount"
                            class="text-sm mb-3 relative block"
                            >Closing Amount</label
                        >
                        <input
                            type="text"
                            v-model="threed_game_setting.closing_amount"
                            id="closing_amount"
                            placeholder="Closing Amount"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label for="3dmin" class="text-sm mb-3 relative block"
                            >Min</label
                        >
                        <input
                            id="3dmin"
                            type="number"
                            v-model="threed_game_setting.min"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                        />
                    </div>
                    <div class="mb-6">
                        <label for="3dmax" class="text-sm mb-3 relative block"
                            >Max</label
                        >
                        <input
                            id="3dmax"
                            type="number"
                            v-model="threed_game_setting.max"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                        />
                    </div>
                </div>
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4"
                >
                    <button
                        type="button"
                        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black focus:outline-none focus:ring-00"
                        data-twe-modal-dismiss
                        data-twe-ripple-init
                        data-twe-ripple-color="light"
                        id="modaladd3DGameSetting"
                    >
                        Close
                    </button>
                    <button
                        :disabled="loading"
                        type="button"
                        @click="add3DGameSetting()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        {{ loading ? "Loading..." : "Create" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import { convertToFriendlyDateTime } from "../../utilities/datetime-helpers";
import Multiselect from "vue-multiselect";
import moment from "moment";
import { values } from "lodash";
export default {
    components: {
        Multiselect,
    },
    data() {
        return {
            dashboard: "",
            system_control: "",
            transaction_control: "",
            from_date: "",
            to_date: "",
            isOpen: true,
            fianancial_report: "",
            threed_setting: "",
            transaction_control_deposit: "",
            transaction_control_withdrawal: "",
            edit_value: {
                type: "",
                column: "",
                id: "",
                value: "",
                title: "",
                input_type: "",
            },
            threed_game_setting: {
                opening_date_time: "",
                closing_date_time: "",
                bet_multiplier: "",
                twist_multiplier: "",
                closing_amount: "",
                name: "",
                min: "",
                max: "",
                game_id: "",
                lottery_date_time: "",
            },

            edit_game_setting: {
                id: "",
                game_id: "",
                name: "",
                opening_time: "",
                closing_time: "",
                lottery_time: "",
                bet_multiplier: "",
                closing_amount: "",
                min: "",
                max: "",
            },
            twod_games: [],
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getToken"]),

        totals() {
            if (!this.fianancial_report) {
                return;
            }
            const totals = this.fianancial_report.reduce(
                (acc, account) => {
                    acc.totalTopupAmount += account.total_topup_amount;
                    acc.totalWithdrawalAmount +=
                        account.total_withdrawal_amount;
                    acc.totalTopupCount += account.total_topup_count;
                    acc.totalWithdrawalCount += account.total_withdrawal_count;
                    return acc;
                },
                {
                    totalTopupAmount: 0,
                    totalWithdrawalAmount: 0,
                    totalTopupCount: 0,
                    totalWithdrawalCount: 0,
                }
            );

            totals.totalProfit =
                totals.totalTopupAmount - totals.totalWithdrawalAmount;
            return totals;
        },
        fromDate() {
            if (this.from_date != "") {
                return moment(this.from_date).format("YYYY-MM-DD");
            } else {
                return "";
            }
        },
        toDate() {
            if (this.to_date != "") {
                return moment(this.to_date).format("YYYY-MM-DD");
            } else {
                return "";
            }
        },
    },
    methods: {
        async getDashboard() {
            let url = `/api/dashboard_crn`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.dashboard = response.data.dashboard;
                this.system_control = response.data.system_control;
                this.transaction_control = response.data.transaction_control;
                this.transaction_control_deposit =
                    this.transaction_control?.find((n) => n.name == "Deposit");
                this.transaction_control_withdrawal =
                    this.transaction_control?.find(
                        (n) => n.name == "Withdrawal"
                    );
                this.threed_setting = response.data.threed_setting;
                this.threed_game_setting.game_id =
                    response.data.threed_setting.game_id;
                this.twod_games = response.data.twod_games;
                console.log(response);
            }
        },
        formatTime(time) {
            if (time) {
                return moment(time, "hh:mm:ss").format("hh:mm A");
            }
        },
        async getFianancialReport() {
            let url = `/api/fianancial_report?from_date=${this.fromDate}&to_date=${this.toDate}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.fianancial_report = response.data;
                console.log(response.data);
            }
        },
        async transactionControlIsactive(type, value, id) {
            this.controlUpdateBtn(
                "transaction_control",
                "is_active",
                id,
                value ? 1 : 0,
                ""
            );
            var temp = await this.updateControl();
            console.log(temp);
            if (temp) {
                if (type == "deposit") {
                    this.transaction_control_deposit.is_active = value;
                } else {
                    this.transaction_control_withdrawal.is_active = value;
                }
            }
        },
        controlUpdateBtn(
            type,
            column,
            id,
            value,
            title,
            input_type = "number"
        ) {
            this.edit_value.type = type;
            this.edit_value.column = column;
            this.edit_value.id = id;
            this.edit_value.title = title;
            this.edit_value.input_type = input_type;
            if (input_type == "time") {
                const formattedTime = moment(value, "HH:mm");
                this.edit_value.value = {
                    hours: formattedTime.hours(),
                    minutes: formattedTime.minutes(),
                };
            } else {
                this.edit_value.value = value;
            }
        },
        async systemControlToggleBtn(control_id, value) {
            console.log(control_id, value);
            this.controlUpdateBtn(
                "system_control",
                "is_active",
                control_id,
                value ? 1 : 0,
                ""
            );
            var temp = await this.updateControl();
            if (temp) {
                var temp = this.system_control.find((n) => n.id == control_id);
                temp.is_active = value;
            }
        },
        async updateControl() {
            if (this.edit_value.value === "") {
                console.log(this.edit_value.value);
                return;
            }
            let url = "/api/update_dashboard_data";
            let formData = new FormData();
            formData.append("type", this.edit_value.type);
            formData.append("column", this.edit_value.column);
            formData.append("id", this.edit_value.id);

            if (this.edit_value.input_type == "time") {
                const { hours, minutes } = this.edit_value.value;
                var temp = moment()
                    .hours(hours)
                    .minutes(minutes)
                    .format("HH:mm");

                formData.append("value", temp);
            } else if (this.edit_value.input_type == "datetime-local") {
                var temp = moment(this.edit_value.value).format(
                    "YYYY-MM-DD HH:mm:ss"
                );
                formData.append("value", temp);
            } else {
                formData.append("value", this.edit_value.value);
            }
            this.loading = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            this.loading = false;
            if (response.data) {
                this.getDashboard();

                this.deposit_min_modal_close_btn();

                return true;
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                });

                return false;
            }
        },
        deposit_min_modal_close_btn() {
            const button = document.getElementById("deposit_min_modal_close");
            if (button) {
                button.click();
                this.$notify({
                    title: "Success!",
                    text: "",
                    type: "info",
                });
            }
        },
        async add3DGameSetting() {
            if (
                !this.threed_game_setting.opening_date_time ||
                !this.threed_game_setting.closing_date_time ||
                !this.threed_game_setting.bet_multiplier ||
                !this.threed_game_setting.twist_multiplier ||
                !this.threed_game_setting.closing_amount ||
                !this.threed_game_setting.name ||
                !this.threed_game_setting.min ||
                !this.threed_game_setting.max ||
                !this.threed_game_setting.lottery_date_time
            ) {
                this.$notify({
                    title: "Error!",
                    text: "Please fil all forms!",
                    type: "error",
                });
                return;
            }
            let formData = new FormData();
            formData.append(
                "opening_date_time",
                moment(this.threed_game_setting.opening_date_time).format(
                    "YYYY-MM-DD HH:mm"
                )
            );
            formData.append(
                "closing_date_time",
                moment(this.threed_game_setting.closing_date_time).format(
                    "YYYY-MM-DD HH:mm"
                )
            );
            formData.append(
                "bet_multiplier",
                this.threed_game_setting.bet_multiplier
            );
            formData.append(
                "twist_multiplier",
                this.threed_game_setting.twist_multiplier
            );
            formData.append(
                "closing_amount",
                this.threed_game_setting.closing_amount
            );
            formData.append("name", this.threed_game_setting.name);
            formData.append("min", this.threed_game_setting.min);
            formData.append("max", this.threed_game_setting.max);
            //  formData.append("game_id", this.threed_game_setting.game_id);
            formData.append(
                "lottery_date_time",
                moment(this.threed_game_setting.lottery_date_time).format(
                    "YYYY-MM-DD HH:mm"
                )
            );
            let url = "/api/3d/game_settings";
            this.loading = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            this.loading = false;
            if (response.success) {
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });
                this.getDashboard();
                document.getElementById("modaladd3DGameSetting").click();
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.error,
                    type: "error",
                });
            }
        },
        async addGameSetting() {
            if (
                !this.edit_game_setting.game_id ||
                !this.edit_game_setting.name ||
                !this.edit_game_setting.opening_time ||
                !this.edit_game_setting.closing_time ||
                !this.edit_game_setting.lottery_time ||
                !this.edit_game_setting.bet_multiplier ||
                !this.edit_game_setting.closing_amount ||
                !this.edit_game_setting.min ||
                !this.edit_game_setting.max
            ) {
                this.$notify({
                    title: "Error!",
                    text: "Please fill all forms!",
                    type: "error",
                });
                return;
            }
            let formData = new FormData();
            formData.append("game_id", this.edit_game_setting.game_id);
            formData.append("name", this.edit_game_setting.name);
            formData.append(
                "opening_time",
                moment(this.edit_game_setting.opening_time).format("HH:mm")
            );
            formData.append(
                "closing_time",
                moment(this.edit_game_setting.closing_time).format("HH:mm")
            );
            formData.append(
                "lottery_time",
                moment(this.edit_game_setting.lottery_time).format("HH:mm")
            );
            formData.append(
                "bet_multiplier",
                this.edit_game_setting.bet_multiplier
            );
            formData.append(
                "closing_amount",
                this.edit_game_setting.closing_amount
            );
            formData.append("min", this.edit_game_setting.min);
            formData.append("max", this.edit_game_setting.max);
            let url = "/api/game_settings";
            this.loading = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            this.loading = false;
            if (response.success) {
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });
                this.getDashboard();
                document.getElementById("close_game_setting").click();
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.error,
                    type: "error",
                });
            }
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY hh:mm A");
            }
        },
    },

    mounted() {
        this.getDashboard();
        this.getFianancialReport();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
