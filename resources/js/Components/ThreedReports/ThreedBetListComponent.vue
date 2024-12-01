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
                    @update:model-value="getBetList"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getBetList"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <div class="flex items-center">
                    <label for="setting" class="mr-2 text-gray-700">Games</label>
                    <select
                        id="setting"
                        @change="getBetList"
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
            </div>
            
            <SearchBox class="mr-3" :search-handler="searchHandler" />

        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <div class="">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">3D</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(num, index) in betList">
                                    <td class="whitespace-nowrap font-medium">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ num.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ num.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ num.number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button :class="getDay(num.lottery_date_time) == 1 ? 'bg-[#f3b01a]' : 'bg-[#2cb12c]'" class="rounded  px-4 pb-1 pt-1 text-xs text-white w-fit mx-auto">
                                            {{ getDay(num.lottery_date_time) }} ရက်
                                        </button>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            num.total_betted_amount.toLocaleString()
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ formatDate(num.date_time) }}
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";

import moment from "moment";
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
      SearchBox,
    },
    data() {
        return {
            betList: null,
            dateList: null,
            total: 0,
            from_date: moment(),
            to_date: moment(),
            game_setting_id: "",
            game_settings: [],
            search_input:""

        };
    },
    computed: {
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
        ...mapGetters(["getToken"]),

        async getBetList() {
            this.total = 0;
            let url = `/api/3d/report/bet_list?game_setting_id=${this.game_setting_id}&from_date=${this.fromDate}&to_date=${this.toDate}&search_input=${this.search_input}`;

            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.dateList = response.data;
                this.betList = response.data.betting_customers;
                if (this.betList) {
                    this.betList.forEach((bet) => {
                        this.total = this.total + bet.total_betted_amount;
                    });
                }
            }
        },
        async getGameSetting() {
            let url = `/api/3d/game_setting`;
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.game_settings = response.data;
                this.game_setting_id = response.data[0].id;
                this.getBetList();
            }
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY hh:mm A");
            }
        },
        formatTime(date){
            if(date){
                return moment(date).format("hh:mm A");
            }
        },
        searchHandler(search_input) {
          this.search_input = search_input;
          this.getBetList();
        },
        getDay(date) {
            if (date) {
                return moment(date).format("DD");
            }
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
