<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between mb-4">
            <div class="flex">
                <VueDatePicker
                    v-model="from_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="From"
                    @update:model-value="getBalanceTransaction(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getBalanceTransaction(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>
            <div class="px-10 mx-10" v-if="all_data">
                <select
                    id="2d_games"
                    v-model="user_id"
                    @change="getBalanceTransaction(true)"
                    class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                >
                    <option value="0">All</option>
                    <option
                        v-for="user in users"
                        :key="user.id"
                        :value="user.id"
                    >
                        {{ user.name }}
                    </option>
                </select>
            </div>
            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="">
                    <div class="flex items-center mb-4">
                        <SelectionPaginationCount
                            :handleChange="
                                (value) => (
                                    (per_page = value),
                                    getBalanceTransaction(true)
                                )
                            "
                            :initialValue="per_page"
                        />
                        <p v-if="!all_data" class="ml-8">
                            User Name: {{ userData.name }}
                        </p>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col" class="p-4">No</th>
                                    <th scope="col" class="p-4">Name</th>
                                    <th scope="col" class="p-4">
                                        Phone Number
                                    </th>
                                    <th scope="col" class="p-4">Type</th>
                                    <th scope="col" class="p-4">Reason</th>
                                    <th scope="col" class="p-4">
                                        Previous Balance
                                    </th>
                                    <th scope="col" class="p-4">Amount</th>
                                    <th scope="col" class="p-4">
                                        Current Amount
                                    </th>
                                    <th scope="col" class="p-4">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(transaction, index) in transactions"
                                    :key="index"
                                >
                                    <td
                                        class="whitespace-nowrap px-6 py-4 font-medium"
                                    >
                                        {{ ++index + (currentPage - 1) * 20 }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <button
                                            title="Wallet Transfer"
                                            type="button"
                                            v-if="
                                                transaction.walletable_type ==
                                                    'wallet_transfer' &&
                                                transaction.action == 'out'
                                            "
                                            class="rounded bg-[#bb262e] text-xs text-white focus:outline-none focus:ring-0 px-1 py-1"
                                        >
                                            Main To Game
                                        </button>
                                        <button
                                            title="Wallet Transfer"
                                            type="button"
                                            v-if="
                                                transaction.walletable_type ==
                                                    'wallet_transfer' &&
                                                transaction.action == 'in'
                                            "
                                            class="rounded bg-[#50bb26] text-xs text-white focus:outline-none focus:ring-0 px-1 py-1"
                                        >
                                            Game To Main
                                        </button>
                                        <button
                                            title="Cash Withdrawal"
                                            type="button"
                                            v-if="
                                                transaction.walletable_type ==
                                                    'cash_withdrawl_transaction' &&
                                                transaction.action == 'out'
                                            "
                                            class="rounded bg-[#46d2b4] text-xs text-white focus:outline-none focus:ring-0 px-1 py-1"
                                        >
                                            Withdrawal
                                        </button>
                                        <button
                                            title="Cash Withdrawal"
                                            type="button"
                                            v-if="
                                                transaction.walletable_type ==
                                                    'cash_withdrawl_transaction' &&
                                                transaction.action == 'in'
                                            "
                                            class="rounded bg-[#b6676b] text-xs text-white focus:outline-none focus:ring-0 px-1 py-1"
                                        >
                                            Withdrawal_Rejected
                                        </button>
                                        <button
                                            title="Deposit"
                                            type="button"
                                            v-if="
                                                transaction.walletable_type ==
                                                'topup_transaction'
                                            "
                                            class="rounded bg-[#4650d2] text-xs text-white focus:outline-none focus:ring-0 px-1 py-1"
                                        >
                                            Deposit
                                        </button>

                                        <p
                                            v-if="
                                                transaction.walletable_type ==
                                                'betting'
                                            "
                                        >
                                            Bet
                                        </p>
                                        <p
                                            v-if="
                                                transaction.walletable_type ==
                                                'betting_number'
                                            "
                                        >
                                            Win
                                        </p>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.description }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{
                                            transaction.previous_amount?.toLocaleString()
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <p
                                            v-if="
                                                transaction.walletable_type ==
                                                    'betting' ||
                                                (transaction.walletable_type ==
                                                    'wallet_transfer' &&
                                                    transaction.action == 'out')
                                            "
                                            class="text-red-600"
                                        >
                                            {{
                                                transaction.amount?.toLocaleString()
                                            }}
                                        </p>
                                        <p
                                            v-if="
                                                transaction.walletable_type ==
                                                    'betting' ||
                                                (transaction.walletable_type ==
                                                    'wallet_transfer' &&
                                                    transaction.action == 'out')
                                            "
                                            class="text-red-600"
                                        >
                                            {{
                                                transaction.amount?.toLocaleString()
                                            }}
                                        </p>

                                        <p
                                            v-if="
                                                transaction.walletable_type ==
                                                    'cash_withdrawl_transaction' &&
                                                transaction.action == 'out'
                                            "
                                            class="text-red-500"
                                        >
                                            {{
                                                transaction.amount?.toLocaleString()
                                            }}
                                        </p>
                                        <p
                                            v-if="
                                                transaction.walletable_type ==
                                                    'cash_withdrawl_transaction' &&
                                                transaction.action == 'in'
                                            "
                                            class="text-green-600"
                                        >
                                            {{
                                                transaction.amount?.toLocaleString()
                                            }}
                                        </p>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{
                                            transaction.current_amount?.toLocaleString()
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ formatDate(transaction.date_time) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="contents" v-if="getTotalCount > per_page">
                            <webPagination
                                :total-items-count="getTotalCount"
                                :items-per-page="per_page"
                                :current-page="currentPage"
                                active-color="#fff"
                                icon-color="#fff"
                                inactive-color="#c8b5db"
                                disabled-color="#c8b5db"
                                @pageChanged="
                                    setCurrentPage($event);
                                    getBalanceTransaction(false);
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters, mapMutations } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import WebPagination from "../Common/webPagination.vue";
import moment from "moment";
import SearchBox from "../Common/SearchBox.vue";
import SelectionPaginationCount from "../Common/SelectionPaginationCount.vue";

export default {
    components: {
        WebPagination,
        SearchBox,
        SelectionPaginationCount,
    },
    data() {
        return {
            transactions: [],
            search_input: "",
            from_date: moment(),
            to_date: moment(),
            per_page: 50,
            users: [],
            user_id: 0,
            all_data: true,
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
        userData() {
            return this.users.find((user) => user.id == this.user_id) || {};
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
        ...mapMutations(["setTotalCount", "setCurrentPage"]),
        async getBalanceTransaction(per_page) {
            if (per_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/get_balance_transaction?page=${
                this.currentPage
            }&search_input=${this.search_input}&to_date=${
                this.toDate
            }&from_date=${this.fromDate}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }&customer_id=${this.user_id}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                console.log(response.data);
                this.transactions = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY h:m A");
            }
        },
        async getUsers() {
            let url = `/api/customers`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.users = response.data;
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getBalanceTransaction(true);
        },
    },
    created() {},

    mounted() {
        let urlParams = new URLSearchParams(window.location.search);
        let user_id = urlParams.get("user_id");
        if (user_id) {
            this.user_id = user_id;
            this.all_data = false;
        }
        this.getUsers();
        this.getBalanceTransaction(true);

        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
