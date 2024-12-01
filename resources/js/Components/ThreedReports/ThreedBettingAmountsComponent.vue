<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex">
                <VueDatePicker
                    v-model="from_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="From"
                    @update:model-value="getNumberList(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getNumberList(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>
            <div class="flex items-center">
                <label for="setting" class="mr-2 text-gray-700">Games</label>
                <select
                    id="setting"
                    @change="getNumberList(true)"
                    v-model="game_setting_id"
                    class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500"
                >
                    <option
                        v-for="(setting, index) in game_settings"
                        :key="index"
                        :value="setting.id"
                    >
                            {{ setting.name }}
                        </option>
                    </select>
                </div>
            <SearchBox class="mr-3" :search-handler="searchHandler" />

            </div>
            
     

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <div class="">
                    <div class="flex items-center mb-4">
                        <label for="itemsPerPage" class="mr-2 text-gray-700"
                            >Show</label
                        >
                        <select
                            id="itemsPerPage"
                            @change="getNumberList(true)"
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
                                    <th scope="col">No</th>
                                    <th scope="col">3D</th>
                                    <th scope="col">Bet</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Bingo 700</th>
                                    <th scope="col">Twist 10</th>
                                    <th scope="col">Profit & loss</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(num, index) in numberList">
                                    <td class="whitespace-nowrap font-medium">
                                        {{ per_page * (currentPage - 1) + (++index) }}

                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ num.number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ num.bets }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ num.total_amount.toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            num.total_bingo_amount.toLocaleString()
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            num.total_twist_amount.toLocaleString()
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap" :class="num.total_prize < 0 ? 'text-red-600' : ''">
                                        {{ num.total_prize.toLocaleString() }}
                                    </td>
                                </tr>
                                <tr class="border-b bg-gray-100">
                                    <td colspan="2" class="border-l text-right pr-3">
                                        Total
                                    </td>
                                    <td class="px-6 py-4 font-semibold">
                                        {{ totalBets }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold">
                                        {{ total.toLocaleString() }}
                                    </td>
                                    <td
                                        colspan="1"
                                        class="px-6 py-4 font-semibold border-r"
                                    >
                                        <!-- {{ totalPrize.toLocaleString() }} -->
                                    </td>
                                    <td
                                        colspan="1"
                                        class="px-6 py-4 font-semibold border-r"
                                    >
                                        <!-- {{ total_twist_amount.toLocaleString() }} -->
                                    </td>
                                    <td
                                        colspan="1"
                                        class="px-6 py-4 font-semibold border-r"
                                    >
                                        <!-- {{ totalPandL.toLocaleString() }} -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6" v-if="getTotalCount > per_page">
                        <webPagination
                            :total-items-count="getTotalCount"
                            :items-per-page="per_page"
                            :current-page="currentPage"
                            active-color="#ffffff"
                            icon-color="#604f4f"
                            inactive-color="#604f4f"
                            disabled-color="#c8b5db"
                            @pageChanged="
                                setCurrentPage($event);
                                getNumberList(false);
                            "
                        />
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
import moment from "moment";
import SearchBox from "../Common/SearchBox.vue";
import WebPagination from "../Common/webPagination.vue";

export default {
    components: {
        SearchBox,
        WebPagination,
    },
    data() {
        return {
            numberList: [],
            totalBets: 0,
            total: 0,
            totalPrize: 0,
            totalPandL: 0,
            total_twist_amount: 0,
            from_date: moment(),
            to_date: moment(),
            game_setting_id: "",
            game_settings: [],
            search_input: "",
            per_page: 50,
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

        async getNumberList(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/3d/report/detail?game_setting_id=${
                this.game_setting_id
            }&from_date=${this.fromDate}&to_date=${this.toDate}&search_input=${
                this.search_input
            }&page=${this.currentPage}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }`;

            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.totalBets = 0;
                this.numberList = response.data.data;
                this.total = 0;
                this.totalPrize = 0;
                this.total_twist_amount = 0;
                this.numberList.forEach((number) => {
                    this.totalBets += number.bets;
                    number.PandL =
                        number.total_prize +
                        number.total_twist_amount -
                        number.total_amount;
                    this.total += number.total_amount;
                    this.totalPrize += number.total_prize;
                    this.total_twist_amount += number.total_twist_amount;
                });
                this.totalPandL =
                    this.total - (this.totalPrize + this.total_twist_amount);
                    this.setTotalCount(response.data.total);
                
            }
        },
        async getGameSetting() {
            let url = `/api/3d/game_setting`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.game_settings = response.data;
                this.game_setting_id = response.data[0].id;
                this.getNumberList(true);
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getNumberList(true);
        },
    },

    created() {
        this.getGameSetting();
    },

    mounted() {
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
