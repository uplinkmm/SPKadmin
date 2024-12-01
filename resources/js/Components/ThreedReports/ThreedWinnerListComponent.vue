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
                    @update:model-value="getWinnerList(true)"
                    format="dd/MM/yyyy"

                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getWinnerList(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <div class="flex items-center">
                    <label for="setting" class="mr-2 text-gray-700">Games</label>
                    <select
                        id="setting"
                        @change="getWinnerList(true)"
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
                <div class="flex items-center mb-4">
                    <label for="itemsPerPage" class="mr-2 text-gray-700">Show</label>
                    <select id="itemsPerPage" @change="getWinnerList(true)" v-model="per_page" class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500">
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="50000">All</option>
                    </select>
                </div> 
                <div class="">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">3D</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Bingo</th>
                                    <th scope="col">Twist/Bingo</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(winner,index) in winnerList" class="text-center">

                                    <td class="whitespace-nowrap font-medium">
                                        {{ per_page * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ winner.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ winner.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ winner.number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button :class="getDay(winner.lottery_date_time) == 1 ? 'bg-[#f3b01a]' : 'bg-[#2cb12c]'" class="rounded  px-4 pb-1 pt-1 text-xs text-white w-fit mx-auto">
                                            {{ getDay(winner.lottery_date_time) }} ရက်
                                        </button>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ (winner.total_betted_amount).toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ (winner.bingo_amount).toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button type="button" v-if="winner.is_twist==0" class="rounded bg-[#46d2b4] text-xs text-white focus:outline-none focus:ring-0 px-1 py-1"> ပေါက်ကွက် </button>
                                        <button type="button" v-if="winner.is_twist==1" class="rounded bg-[#4650d2] text-xs text-white focus:outline-none focus:ring-0 px-1 py-1"> တွတ် </button>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ formatDate(winner.date_time)}}
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6"  v-if="getTotalCount>per_page">
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
                                getWinnerList(false);
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
import { mapGetters,mapMutations } from 'vuex';
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import moment from "moment";
import WebPagination from "../Common/webPagination.vue";
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        WebPagination,
        SearchBox
    },
    data() {
        return {
            selectedDate : null,
            selectedTime: 'morning',
            winnerList:null,
            dateTimeList:null,
            total:null,
            from_date: moment(),
            to_date:moment(),
            per_page:50,
            game_setting_id: "",
            game_settings: [],
            search_input:""

        }
    },
    computed: {
        ...mapGetters(["getToken","getTotalCount", "currentPage"]),

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

        async getWinnerList(reset_page){
            if(reset_page){
                this.setCurrentPage(1);
            }
            let url = `/api/3d/bingo/customers?game_setting_id=${this.game_setting_id}&from_date=${this.fromDate}&to_date=${this.toDate}&page=${this.currentPage}${this.per_page ? `&per_page=${this.per_page}` : ""}&search_input=${this.search_input}`;

            let response = await getApiData({url: url, token: this.getToken});
            if(response.data){
                this.winnerList = response.data.bingo_customers.data;
                this.setTotalCount(response.data.bingo_customers.total);
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
                this.getWinnerList(true);
            }
        },
        formatDate(date){
            if(date){date
                return moment(date).format("DD/MM/YYYY");
            }
        },
        formatTime(date){
            if(date){
                return moment(date).format("hh:mm A");
            }
        },

        searchHandler(search_input) {
          this.search_input = search_input;
          this.getWinnerList(true);
        },
        getDay(date) {
            if (date) {
                return moment(date).format("DD");
            }
        },
    },

    created() {
    },

    mounted() {
        this.getGameSetting();
        initTWE({Modal, Ripple, Dropdown})
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
