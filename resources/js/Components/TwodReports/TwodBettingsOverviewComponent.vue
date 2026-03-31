<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <p class="w-full">2D Dashboard</p>
            <VueDatePicker
                v-model="currentDate"
                :enable-time-picker="false"
                auto-apply
                class="!w-fit"
                placeholder="Date"
                @update:model-value="getNumberList()"
                format="dd/MM/yyyy"
            ></VueDatePicker>
        </div>

        <!--Tabs navigation-->
        <ul
            class="mb-5 flex list-none flex-row flex-wrap border-b-0 ps-0"
            role="tablist"
            data-twe-nav-ref
        >
            <li role="presentation">
                <a
                    href="#tabs-home"
                    @click="getNumberListbyMorning()"
                    class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-sm text-gray-700 hover:isolate hover:border-transparent hover:text-black focus:isolate focus:border-transparent data-[twe-nav-active]:border-black data-[twe-nav-active]:text-black"
                    data-twe-toggle="pill"
                    data-twe-target="#tabs-home"
                    data-twe-nav-active
                    role="tab"
                    aria-controls="tabs-home"
                    aria-selected="true"
                >
                    {{
                        gameSettings[0]
                            ? formatTime(gameSettings[0].lottery_time)
                            : ""
                    }}
                </a>
            </li>
            <li role="presentation">
                <a
                    href="#tabs-profile"
                    @click="getNumberListbyEvening()"
                    class="my-2 block border-x-0 border-b-2 border-t-0 border-transparent px-7 pb-3.5 pt-4 text-sm text-gray-700 hover:isolate hover:border-transparent hover:text-black focus:isolate focus:border-transparent data-[twe-nav-active]:border-black data-[twe-nav-active]:text-black"
                    data-twe-toggle="pill"
                    data-twe-target="#tabs-profile"
                    role="tab"
                    aria-controls="tabs-profile"
                    aria-selected="false"
                >
                    {{
                        gameSettings[1]
                            ? formatTime(gameSettings[1].lottery_time)
                            : ""
                    }}
                </a>
            </li>
        </ul>

        <!--Tabs content-->
        <div class="overflow-x-auto">
            <div class="mb-6 w-full">
                <div
                    class="hidden opacity-100 w-full transition-opacity duration-150 ease-linear data-[twe-tab-active]:block"
                    id="tabs-home"
                    role="tabpanel"
                    aria-labelledby="tabs-home-tab"
                    data-twe-tab-active
                >
                    <div
                        class="grid grid-cols-5 lg:grid-cols-10 gap-1.5 border-collapse border border-gray-200 w-full"
                    >
                        <div
                            v-for="(num, index) in numberList"
                            class="number-container"
                            :key="index"
                        >
                            <p class="bet-number">
                                {{ num.number }}
                            </p>
                            <p class="bet-price">
                                {{ num.total_bet_amount.toLocaleString() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[twe-tab-active]:block"
                    id="tabs-profile"
                    role="tabpanel"
                    aria-labelledby="tabs-profile-tab"
                >
                    <div
                        class="grid grid-cols-5 lg:grid-cols-10 gap-1.5"
                    >
                        <div
                            v-for="(num, index) in numberList"
                            class="number-container"
                            :key="index"
                        >
                            <p class="bet-number">
                                {{ num.number }}
                            </p>
                            <p class="bet-price">
                                {{ num.total_bet_amount.toLocaleString() }}
                            </p>
                        </div>
                    </div>
                </div>
                <data
                    class="grid grid-cols-10 text-right border border-gray-300 border-collapse border-t-0"
                >
                    <p
                        class="font-bold py-3 col-span-9 border border-gray-300 pr-3 border-t-0"
                    >
                        Total
                    </p>
                    <p class="font-bold py-3 pr-3 border-t-0">
                        {{ total_amount?.toLocaleString() }}
                    </p>
                </data>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown, Tab } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";

import Multiselect from "vue-multiselect";
import moment from "moment";

export default {
    components: {
        Multiselect,
    },
    data() {
        return {
            currentDate: moment(),
            selectedTime: "morning",
            numberList: null,
            gameSettings: [],
            game_setting_id: null,
            total_amount: "",
        };
    },

    methods: {
        ...mapGetters(["getToken"]),

        async getGameSettings() {
            let url = "/api/2d/game_settings";
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.gameSettings = response.data;
                this.game_setting_id = this.gameSettings[0].id;
                this.getNumberList();
            }
        },

        getNumberListbyMorning() {
            this.selectedTime = "morning";
            this.game_setting_id = this.gameSettings[0].id;
            this.getNumberList();
        },

        getNumberListbyEvening() {
            this.selectedTime = "evening";
            this.game_setting_id = this.gameSettings[1].id;
            this.getNumberList();
        },

        async getNumberList() {
            var temp = moment(this.currentDate).format("YYYY-MM-DD");
            let url = `/api/2d/report/summary?date=${temp}&game_setting_id=${this.game_setting_id}`;
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.numberList = response.data.dashboard;
                this.total_amount = response.data.total_amount;
            }
        },
        formatTime(time) {
            return moment(time, "H:m:s").format("hh:mm A");
        },
    },

    created() {
        this.getGameSettings();
        // this.getNumberList();
    },

    mounted() {
        initTWE({ Modal, Ripple, Dropdown, Tab });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
