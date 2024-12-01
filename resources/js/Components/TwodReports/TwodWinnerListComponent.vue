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
            </div>
            <SearchBox class="mr-3" :search-handler="searchHandler" />

        </div>
        <div class="px-4 mb-5">
            <button v-for="(gameSetting, gameSettingIndex) in gameSettings"
                @click="gameSettingBtnClicked(gameSetting, gameSettingIndex)" :id="`gameSettingBtn${gameSettingIndex}`"
                :class="gameSetting.id==selectedGameSetting.id ? 'border-black text-black' : 'bg-transparent border-transparent text-gray-700'"

                class="border-b-2 text-sm px-7 pb-2 pt-2 game-setting-btns">
                {{ formatTime(gameSetting.lottery_time) }}
            </button>

        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="">
                    <div class="overflow-hidden">
                        <div class="flex items-center mb-4">
                            <label for="itemsPerPage" class="mr-2 text-gray-700">Show</label>
                            <select id="itemsPerPage" @change="getWinnerList(true)" v-model="per_page" class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500">
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="50000">All</option>
                            </select>
                        </div> 
                        <div class="table-container">
                            <table class="min-w-full text-left text-sm font-inter text-black">
                                <thead class="border-b border-neutral-200 font-medium ">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">ID</th>
                                        <th scope="col" class="px-6 py-4">Name</th>
                                        <th scope="col" class="px-6 py-4">Phone</th>
                                        <th scope="col" class="px-6 py-4">2D</th>
                                        <th scope="col" class="px-6 py-4">Time</th>
                                        <th scope="col" class="px-6 py-4">Amount</th>
                                        <th scope="col" class="px-6 py-4">Bingo</th>
                                        <th scope="col" class="px-6 py-4">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(winner,index) in winnerList"
                                        class="border-b border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100">
    
                                        <td class="whitespace-nowrap px-6 py-4 font-medium">
                                            {{ per_page * (currentPage - 1) + (++index) }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ winner.name }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ winner.phone_number }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ winner.number }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div :style="{ backgroundColor: selectedGameSetting.time_status=='morning' ? '#2cb12c' :'#f3b01a' }" class="color-text-box text-white">
                                                {{ formatTime(selectedGameSetting.lottery_time) }}
                                            </div>
                                            <!-- {{ dateTimeList ? dateTimeList.time : '' }} -->
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ (winner.total_betted_amount).toLocaleString() }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ (winner.bingo_amount).toLocaleString() }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ formatDate(winner.date_time)}}
                                        </td>
    
                                    </tr>
    
                                </tbody>
                            </table>
                        </div>
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
import { convertToFriendlyDateTime, getCurrentDate } from '../../utilities/datetime-helpers';
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
            selectedDate : getCurrentDate(),
            selectedTime: 'morning',
            winnerList:null,
            dateTimeList:null,
            total:null,
            gameSettings: [],
            selectedGameSetting: null,
            from_date:moment(),
            to_date:moment(),
            per_page:50,
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

        async getGameSettings(){
            let url = '/api/2d/game_settings';
            let response = await getApiData({url: url, token: this.getToken});
            if(response.data){
                this.gameSettings = response.data;
                this.selectedGameSetting = this.gameSettings[0];
                this.getWinnerList(false);
            }
        },

        gameSettingBtnClicked(gameSetting, index){
            $('.game-setting-btns').each(function() {
                // Add a new class to each button
                $(this).addClass('bg-transparent border-transparent text-gray-700'); // Replace 'new-class-name' with the class you want to add
            });

            $(`#gameSettingBtn${index}`).removeClass('bg-transparent border-transparent text-gray-700');
            $(`#gameSettingBtn${index}`).addClass('border-black text-black');

            this.selectedGameSetting = gameSetting;
            this.getWinnerList(true);
        },

        async getWinnerList(reset_page){
            if(reset_page){
                this.setCurrentPage(1);
            }
            let url = `/api/2d/bingo/customers?from_date=${this.fromDate}&to_date=${this.toDate}&game_setting_id=${this.selectedGameSetting.id}&page=${this.currentPage}${this.per_page ? `&per_page=${this.per_page}` : ""}&search_input=${this.search_input}`;

            let response = await getApiData({url: url, token: this.getToken});
            if(response.data){
                this.winnerList = response.data.bingo_customers.data;
                this.dateTimeList = response.data;
                this.setTotalCount(response.data.bingo_customers.total);
            }
        },

        formatDate(date){
            if(date){
                return moment(date).format("DD/MM/YYYY");
            }
        },
        formatTime(time){
            if(time){
                return moment(time,'H:m:s').format('hh:mm A');
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getWinnerList(true);
        },
    },

    created() {
        this.getGameSettings();
    },

    mounted() {
        initTWE({Modal, Ripple, Dropdown})
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
