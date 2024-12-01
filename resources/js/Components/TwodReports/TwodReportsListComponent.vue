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
                    @update:model-value="getNumberListByDate(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getNumberListByDate(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>
            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="px-4 mb-5">
            <button
                v-for="(gameSetting, gameSettingIndex) in gameSettings"
                @click="gameSettingBtnClicked(gameSetting, gameSettingIndex)"
                :id="`gameSettingBtn${gameSettingIndex}`"
                :class="
                    gameSetting.id == selectedGameSetting.id
                        ? 'border-black text-black'
                        : 'bg-transparent border-transparent text-gray-700'
                "
                class="border-b-2 text-sm px-7 pb-2 pt-2 game-setting-btns"
            >
                {{ formatTime(gameSetting.lottery_time) }}
            </button>
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="flex items-center mb-4">
                <label for="itemsPerPage" class="mr-2 text-gray-700"
                    >Show</label
                >
                <select
                    id="itemsPerPage"
                    @change="getNumberListByDate(true)"
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
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Bet %</th>
                            <th scope="col">Bet</th>
                            <th scope="col">Amounts</th>
                            <th scope="col">Bingo 85/90</th>
                            <th scope="col">Profit & loss</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div
                            class="contents"
                            v-for="(num, index) in numberList"
                        >
                            <tr
                                :class="
                                    num.total_betted_amount -
                                        num.total_bingo_amount <
                                    0
                                        ? 'bg-red-400'
                                        : ''
                                "
                            >
                                <td class="whitespace-nowrap font-medium">
                                    {{ per_page * (currentPage - 1) + (++index) }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ num.name }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ num.phone_number }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ num.betting_percentage }}%
                                </td>
                                <td class="whitespace-nowrap">
                                    {{ num.total_bettings }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{
                                        num.total_betted_amount.toLocaleString()
                                    }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{
                                        num.total_bingo_amount.toLocaleString()
                                    }}
                                </td>
                                <td class="whitespace-nowrap">
                                    {{
                                        (
                                            num.total_betted_amount -
                                            num.total_bingo_amount
                                        ).toLocaleString()
                                    }}
                                </td>
                            </tr>
                        </div>

                        <tr class="border-b bg-gray-100">
                            <td colspan="4" class="border-l"></td>
                            <td class="px-6 py-4 font-semibold">
                                {{ totalBets }}
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                {{ total.toLocaleString() }}
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                {{ totalPrize.toLocaleString() }}
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                {{ (total - totalPrize).toLocaleString() }}
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
                        getNumberListByDate(false);
                    "
                />
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters, mapMutations } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import WebPagination from "../Common/webPagination.vue";

import {
    convertToFriendlyDateTime,
    getCurrentDate,
} from "../../utilities/datetime-helpers";
import moment from "moment";
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        SearchBox,
        WebPagination,
    },
    data() {
        return {
            currentDate: getCurrentDate(),
            selectedTime: "morning",
            numberList: [],
            totalBets: 0,
            total: 0,
            totalPrize: 0,
            gameSettings: [],
            selectedGameSetting: null,
            from_date: moment(),
            to_date: moment(),
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

        async getGameSettings() {
            let url = "/api/2d/game_settings";
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.gameSettings = response.data;
                this.selectedGameSetting = this.gameSettings[0];
                this.getNumberList(this.gameSettings[0].id, true);
            }
        },

        gameSettingBtnClicked(gameSetting, index) {
            $(".game-setting-btns").each(function () {
                // Add a new class to each button
                $(this).addClass(
                    "bg-transparent border-transparent text-gray-700"
                ); // Replace 'new-class-name' with the class you want to add
            });

            $(`#gameSettingBtn${index}`).removeClass(
                "bg-transparent border-transparent text-gray-700"
            );
            $(`#gameSettingBtn${index}`).addClass("border-black text-black");

            this.selectedGameSetting = gameSetting;
            this.getNumberList(gameSetting.id, true);
        },

        async getNumberList(gameSettingId, reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/2d/report/customer_bets?from_date=${
                this.fromDate
            }&to_date=${this.toDate}&game_setting_id=${gameSettingId}&page=${
                this.currentPage
            }${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }&search_input=${this.search_input}`;

            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.numberList = response.data.customer_bet_amounts.data;
                this.total = response.data.all_total_betted_amount;
                this.totalBets = 0;
                this.totalPrize = 0;
                this.numberList.forEach((number) => {
                    this.totalBets += number.total_bettings;
                    this.totalPrize += number.total_bingo_amount;
                });
                this.setTotalCount(response.data.customer_bet_amounts.total);
            }
        },

        async getNumberListByDate(rest_page) {
            this.getNumberList(this.selectedGameSetting.id, rest_page);
        },
        formatTime(time) {
            return moment(time, "H:m:s").format("hh:mm A");
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getNumberList(this.selectedGameSetting.id, true);
        },
    },

    created() {
        this.getGameSettings();
    },

    mounted() {
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
