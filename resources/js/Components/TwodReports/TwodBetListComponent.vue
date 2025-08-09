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
                    @update:model-value="getBetListByDate"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getBetListByDate"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>
            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="px-4 mb-5">
            <button
                v-for="(gameSetting, gameSettingIndex) in gameSettings"
                :key="gameSettingIndex"
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
            <SelectionPaginationCount
                :handleChange="
                    (value) => ((per_page = value), getBetListByDate())
                "
                :initialValue="per_page"
            />
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">2D</th>
                            <th scope="col">Time</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(bet, index) in betList" :key="index">
                            <td class="whitespace-nowrap font-medium">
                                {{ per_page * (currentPage - 1) + ++index }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ bet.name }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ bet.phone_number }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ bet.number }}
                            </td>

                            <td class="whitespace-nowrap">
                                <div
                                    :style="{
                                        backgroundColor:
                                            selectedGameSetting.time_status ==
                                            'morning'
                                                ? '#2cb12c'
                                                : '#f3b01a',
                                    }"
                                    class="color-text-box text-white"
                                >
                                    {{
                                        formatTime(
                                            selectedGameSetting.lottery_time
                                        )
                                    }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap">
                                {{ bet.total_betted_amount.toLocaleString() }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ formatDate(bet.date_time) }}
                            </td>
                        </tr>
                        <tr class="border-b bg-gray-100">
                            <td colspan="5" class="border-l"></td>
                            <td class="px-6 py-4 font-semibold">
                                {{ total.toLocaleString() }}
                            </td>
                            <td colspan="2" class="border-r"></td>
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
                        getBetList(selectedGameSetting.id, false);
                    "
                />
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters, mapMutations } from "vuex";
import { getApiData } from "../../utilities/ajax-helpers";
import { getCurrentDate } from "../../utilities/datetime-helpers";
import moment from "moment";
import SearchBox from "../Common/SearchBox.vue";
import WebPagination from "../Common/webPagination.vue";
import SelectionPaginationCount from "../Common/SelectionPaginationCount.vue";

export default {
    components: {
        SearchBox,
        WebPagination,
        SelectionPaginationCount,
    },
    data() {
        return {
            selectedDate: getCurrentDate(),
            selectedTime: "morning",
            betList: null,
            dateTimeList: null,
            total: 0,
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
                this.getBetList(this.gameSettings[0].id, false);
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
            this.getBetList(gameSetting.id, true);
        },

        async getBetList(gameSettingId, reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            this.total = 0;
            let url = `/api/2d/report/bet_list?from_date=${this.fromDate}&to_date=${this.toDate}&game_setting_id=${gameSettingId}&search_input=${this.search_input}&per_page=${this.per_page}&page=${this.currentPage}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.betList = response.data.betting_customers.data;
                this.dateTimeList = response.data;
                this.betList.forEach((bet) => {
                    this.total = this.total + bet.total_betted_amount;
                });
                this.setTotalCount(response.data.betting_customers.total);
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getBetList(this.selectedGameSetting.id, true);
        },

        async getBetListByDate() {
            this.getBetList(this.selectedGameSetting.id, true);
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY h:m A");
            }
        },
        formatTime(time) {
            if (time) {
                return moment(time, "H:m:s").format("hh:mm A");
            }
        },
    },

    created() {
        // this.getBetList('morning');
        this.getGameSettings();
    },

    mounted() {
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
