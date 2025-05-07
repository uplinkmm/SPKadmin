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
                <div class="">
                    <select
                        id="game_types"
                        v-model="game_type_id"
                        @change="getProviders"
                        class="block py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                    >
                        <option value="0">All</option>
                        <option
                            v-for="type in game_types"
                            :key="type.id"
                            :value="type.id"
                        >
                            {{ type.name }}
                        </option>
                    </select>
                </div>
                <div class="">
                    <select
                        id="game_types"
                        v-model="provider_id"
                        @change="getBalanceTransaction(true)"
                        class="block ml-3 py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                    >
                        <option value="">All</option>
                        <option
                            v-for="provider in providers"
                            :key="provider.id"
                            :value="provider.id"
                        >
                            {{ provider.name }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- <SearchBox class="mr-3" :search-handler="searchHandler" /> -->
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="">
                    <div class="flex items-center mb-4">
                        <label for="itemsPerPage" class="mr-2 text-gray-700"
                            >Show</label
                        >
                        <select
                            id="itemsPerPage"
                            @change="getBalanceTransaction(true)"
                            v-model="per_page"
                            class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500"
                        >
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="50000">All</option>
                        </select>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col" class="p-4">No</th>
                                    <th scope="col" class="p-4">User</th>
                                    <th scope="col" class="p-4">Phone</th>
                                    <th scope="col" class="p-4">Site</th>
                                    <th scope="col" class="p-4">Game</th>
                                    <th scope="col" class="p-4">Win/Lose</th>
                                    <th scope="col" class="p-4">Bet Amount</th>
                                    <th scope="col" class="p-4">Payout</th>
                                    <th scope="col" class="p-4">User Profit</th>
                                    <th scope="col" class="p-4">Ref Number</th>
                                    <th scope="col" class="p-4">Match Time</th>
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
                                        {{ ++index + (currentPage - 1) * per_page }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.site_name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.game_name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <button
                                            type="button"
                                            v-if="
                                                transaction.win_or_lose == 'win'
                                            "
                                            class="rounded bg-[#45d168] text-xs text-white focus:outline-none focus:ring-0 px-1 py-1"
                                        >
                                            Win
                                        </button>
                                        <button
                                            type="button"
                                            v-else
                                            class="rounded bg-[#a33333] text-xs text-white focus:outline-none focus:ring-0 px-1 py-1"
                                        >
                                            Lose
                                        </button>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.bet_amount?.toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.transaction_amount?.toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.profit?.toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ transaction.ref_no }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ formatDate(transaction.created_at) }}
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

export default {
    components: {
        WebPagination,
        SearchBox,
    },
    data() {
        return {
            transactions: [],
            search_input: "",
            from_date: moment(),
            to_date: moment(),
            per_page: 50,
            users: [],
            game_types: [],
            game_type_id: 1,
            providers: [],
            provider_id: "",
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
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
            let url = `/api/slot_transaction?page=${
                this.currentPage
            }&search_input=${this.search_input}&to_date=${
                this.toDate
            }&from_date=${this.fromDate}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }&game_type_id=${this.game_type_id}&product_id=${this.provider_id}`;
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
        async getGameType() {
            let url = `/api/game_type_list`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.game_types = response.data;
                this.getProviders();
            }
        },
        async getProviders() {
            let url = `/api/product_list_by_game_type/${this.game_type_id}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.providers = response.data;
                this.getBalanceTransaction(true);
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getBalanceTransaction(true);
        },
    },
    created() {},

    mounted() {
        this.getGameType();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
