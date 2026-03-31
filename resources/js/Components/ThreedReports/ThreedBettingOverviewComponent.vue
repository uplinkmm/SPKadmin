<template>
    <div class="frame-container min-h-[100vh]">
        <div class="mb-4">
            <p>3D Bet List By Time</p>
        </div>

        <div class="mb-4 flex">
            <select
                name=""
                id=""
                v-model="selectedAmount"
                @change="getNumberList()"
                class="border border-gray-600 text-sm font-inter rounded-lg bg-white min-w-[8rem] px-2 h-9"
            >
                <option value="0">All</option>
                <option v-for="(num, index) in 10" :value="num" :key="index">
                    {{ (num - 1) * 100 < 100 ? "000" : (num - 1) * 100 }} -
                    {{
                        (num - 1) * 100 + 99 < 100
                            ? "099"
                            : (num - 1) * 100 + 99
                    }}
                    <!-- {{ num*100 }} - {{ (num*100)+99 }} -->
                </option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <div class="mb-6 w-full min-w-max">
                <div
                    :class="selectedAmount == '0' ? 'grid-100' : 'grid-rows-10'"
                    class="grid grid-cols-5 lg:grid-cols-10 gap-2 border border-gray-200 w-full"
                >
                    <div
                        class="number-container"
                        v-for="(num, index) in numberList"
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
                <div
                    class="grid grid-cols-10 grid-flow-col border border-gray-300 border-t-0"
                >
                    <div class="col-span-8"></div>
                    <p
                        class="font-bold text-right py-3 border-r border-gray-300 pr-2"
                    >
                        Total
                    </p>
                    <p class="font-bold text-right py-3 pr-2">{{ total }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown, Tab } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";

import Multiselect from "vue-multiselect";

export default {
    components: {
        Multiselect,
    },
    data() {
        return {
            currentDate: null,
            selectedAmount: "0",
            numberList: null,
            total: "",
        };
    },

    methods: {
        ...mapGetters(["getToken"]),

        async getNumberList(selectedTime) {
            let url = "/api/3d/report/summary?page=" + this.selectedAmount;
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.numberList = response.data.dashboard;
                this.total = response.data.total_amount;
            }
        },
        //getCurrentDate(){
        //    this.currentDate = getCurrentDate()
        //}
    },

    created() {
        this.getNumberList();
    },

    mounted() {
        initTWE({ Modal, Ripple, Dropdown, Tab });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
