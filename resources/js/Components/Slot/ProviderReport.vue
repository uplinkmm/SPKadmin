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
                    @update:model-value="getProviderReport(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getProviderReport(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
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
                            @change="getProviderReport(true)"
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
                                    <th scope="col" class="p-4">Provider</th>
                                    <th scope="col" class="p-4">Bet amount</th>
                                    <th scope="col" class="p-4">Payout</th>
                                    <th scope="col" class="p-4">User Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(report, index) in reports"
                                    :key="index"
                                >
                                    <td
                                        class="whitespace-nowrap px-6 py-4 font-medium"
                                    >
                                        {{
                                            ++index +
                                            (currentPage - 1) * per_page
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ report.game_name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ report.total_bet_amount?.toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ report.total_transaction_amount?.toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ report.total_profit }}
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
                                    getProviderReport(false);
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
            reports: [],
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
        async getProviderReport(per_page) {
            if (per_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/slot_provider_report?page=${
                this.currentPage
            }&to_date=${
                this.toDate
            }&from_date=${this.fromDate}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                console.log(response.data);
                this.reports = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY h:m A");
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getProviderReport(true);
        },
    },
    created() {},

    mounted() {
        this.getProviderReport(true);
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
