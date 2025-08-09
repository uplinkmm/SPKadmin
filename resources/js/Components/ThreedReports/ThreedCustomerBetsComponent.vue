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
                    @update:model-value="getNumberList"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getNumberList"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <div class="flex items-center">
                    <label for="setting" class="mr-2 text-gray-700"
                        >Games</label
                    >
                    <select
                        id="setting"
                        @change="getNumberList"
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
                                    :key="index"
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
                                        <td
                                            class="whitespace-nowrap font-medium"
                                        >
                                            {{ index + 1 }}
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
                                        {{
                                            (
                                                total - totalPrize
                                            ).toLocaleString()
                                        }}
                                    </td>
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
import { getApiData } from "../../utilities/ajax-helpers";
import moment from "moment";
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        SearchBox,
    },
    data() {
        return {
            numberList: [],
            totalBets: 0,
            total: 0,
            totalPrize: 0,
            from_date: moment(),
            to_date: moment(),
            game_setting_id: "",
            game_settings: [],
            search_input: "",
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

        async getNumberList() {
            let url = `/api/3d/report/customer_bets?game_setting_id=${this.game_setting_id}&from_date=${this.fromDate}&to_date=${this.toDate}&search_input=${this.search_input}`;

            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.numberList = response.data.customer_bet_amounts;
                this.total = response.data.all_total_betted_amount;
                this.totalPrize = 0;
                this.totalBets = 0;
                response.data.customer_bet_amounts.forEach((bet) => {
                    this.totalBets += bet.total_bettings;
                    this.totalPrize += bet.total_bingo_amount;
                });
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
                this.getNumberList();
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getNumberList();
        },
    },

    created() {},

    mounted() {
        this.getGameSetting();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
