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
                    @update:model-value="getNumberListByDate"
                    format="dd/MM/yyyy"

                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getNumberListByDate"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>


            <SearchBox class="mr-3" :search-handler="searchHandler" />

        </div>
            <div class="px-4 mb-5">
                <button v-for="(gameSetting, gameSettingIndex) in gameSettings" @click="gameSettingBtnClicked(gameSetting, gameSettingIndex)" :id="`gameSettingBtn${gameSettingIndex}`"
                :class="gameSetting.id==selectedGameSetting.id ? 'border-black text-black' : 'bg-transparent border-transparent text-gray-700'"
                class="border-b-2 text-sm px-7 pb-2 pt-2 game-setting-btns">
                    {{ formatTime(gameSetting.lottery_time) }}
                </button>
            </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">2D</th>
                            <th scope="col">Bet</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Bingo 85/95</th>
                            <th scope="col">Profit & loss</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(num,index) in numberList" :class="num.is_win ? 'green-and-white' : ''">
                            <td class="whitespace-nowrap font-medium">
                                {{ index+1 }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ num.number }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ num.bets }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ (num.total_amount).toLocaleString() }}
                             </td>
                             <td class="whitespace-nowrap">
                                 {{ (num.total_bingo_amount).toLocaleString() }}
                              </td>
                            <td class="whitespace-nowrap" :class="num.total_prize < 0 ? 'text-red-600' : ''">
                                {{ (num.total_prize).toLocaleString() }}
                            </td>

                        </tr>
                        <tr class="border-b bg-gray-100">
                            <td colspan="2" class="border-l"></td>
                            <td class="px-6 py-4 font-semibold">
                                {{ totalBets }}
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                {{ total.toLocaleString() }}
                            </td>
                            <td colspan="1" class="px-6 py-4 font-semibold border-r">
                                <!-- {{ totalPrize.toLocaleString() }} -->
                            </td>
                            <td colspan="1" class="px-6 py-4 font-semibold border-r">
                                <!-- {{ (totalPandL).toLocaleString() }} -->
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters } from 'vuex';
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import moment from "moment";
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        SearchBox,
    },
    data() {
        return {
            selectedTime: 'morning',
            numberList: [],
            totalBets: 0,
            total:0,
            totalPrize: 0,
            totalPandL: 0,
            gameSettings: [],
            selectedGameSetting: null,
            from_date:moment(),
            to_date:moment(),
            search_input:""

        }
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
        ...mapGetters(['getToken']),

        async getGameSettings(){
            let url = '/api/2d/game_settings';
            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.gameSettings = response.data;
                this.selectedGameSetting = this.gameSettings[0];
                this.getNumberList(this.gameSettings[0].id);
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
            this.getNumberList(gameSetting.id);
        },

        async getNumberList(gameSettingId){
            let url = `/api/2d/report/detail?from_date=${this.fromDate}&to_date=${this.toDate}&game_setting_id=${gameSettingId}&search_input=${this.search_input}`;

            let response = await getApiData({url: url, token: this.getToken()});
            if(response.data){
                this.totalBets = 0;
                this.numberList = response.data.bet_numbers;
                this.total = response.data.total_amount;
                this.totalPrize = 0;
                this.numberList.forEach(number => {
                    this.totalBets += number.bets;
                    number.PandL = (number.amount - number.total_prize);
                    this.totalPrize += number.total_prize;
                });
                this.totalPandL = this.total - this.totalPrize;
            }
        },

        async getNumberListByDate(){
            this.getNumberList(this.selectedGameSetting.id);
        },
        formatTime(time){
         return moment(time,'H:m:s').format('hh:mm A');
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getNumberList(this.selectedGameSetting.id);
        },
    },

    created() {
        this.getGameSettings();
    },

    mounted() {
        initTWE({Modal, Ripple, Dropdown});
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
