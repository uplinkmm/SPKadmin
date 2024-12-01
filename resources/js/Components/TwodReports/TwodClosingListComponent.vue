<template>
    <notifications position="top center" />

    <div class="frame-container min-h-[100vh]">
        <div class="flex flex-col lg:flex-row gap-y-4 justify-between px-4 mb-4">
            <div class="flex gap-x-4 h-9">
                <button class="add-btn" @click="selectAllNumber">
                    {{ checkAll ? "Un Check All" : "Check All" }}
                </button>
                <button class="add-btn h-9" @click="openNumber">Open</button>
                <p class="font-bold pt-2">{{ break_percentage }} %</p>
            </div>
            <div class="flex gap-x-4">
                <div>
                    <select
                        name=""
                        id=""
                        v-model="selectedGameSetting"
                        @change="gameSettingSelectChanged()"
                        class="border border-gray-600 text-sm font-inter rounded-lg bg-white min-w-[8rem] px-2 h-9"
                    >
                        <option
                            :value="gameSetting"
                            v-for="gameSetting in gameSettings"
                        >
                            {{ formatTime(gameSetting.lottery_time) }}
                        </option>
                    </select>
                </div>
                <div class="">
                    <input
                        v-model="amount"
                        type="text"
                        id="amount"
                        placeholder="Amount"
                        class="px-3 py-2 border border-gray-600 text-sm font-inter rounded-lg w-full"
                    />
                </div>
                <button class="add-btn h-9 relative shrink-0" @click="closeNumber()">
                    Close Number
                </button>
            </div>
        </div>
        <div class="bg-white px-4 pt-4 pb-12 rounded-md overflow-x-auto">
            <div class="grid grid-cols-10 gap-y-4 gap-x-8 min-w-max w-full">
                <!-- Top row of checkboxes -->
                <div
                    v-for="col in 10"
                    :key="'topChecki' + col"
                    class="relative ml-3"
                >
                    <label
                        :for="'topCheck' + col"
                        class="px-3 py-4 flex gap-x-3 rounded-md"
                    >
                        <input
                            :id="'topCheck' + col"
                            type="checkbox"
                            :value="'topCheck' + col - 1"
                            @input="
                                numbersStartingWith(
                                    col - 1,
                                    $event.target.checked
                                )
                            "
                        />
                        {{ col - 1 }}ထိပ်
                    </label>
                </div>

                <!-- Checkbox grid -->
                <div
                    v-for="(num, index) in formattedNumbers"
                    :key="index"
                    class="relative"
                    :style="{
                        gridColumn: Math.floor(index / 10) + 1,
                        gridRow: (index % 10) + 2,
                    }"
                >
                    <div
                        class="block px-3 py-4 opacity-70"
                        :class="num.is_closing ? 'bg-gray-300' : ''"
                    >
                        <label
                            :for="'check' + num.number"
                            class="px-3 py-4 flex gap-x-3 rounded-md"
                        >
                            <input
                                :id="'check' + num.number"
                                type="checkbox"
                                v-model="checkedNumber"
                                :value="num.number"
                            />
                            {{ num.number }}
                        </label>
                        <p class="pl-4" v-if="num.closing_amount">
                            {{ num.closing_amount ? num.closing_amount : "" }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";

export default {
    data() {
        return {
            numbers: Array.from({ length: 100 }, (_, index) => index), // Array from 0 to 99
            checkedNumber: [],
            amount: 200000,
            gameSettings: [],
            selectedGameSetting: null,
            formattedNumbers: [],
            checkAll: false,
            break_percentage:""
        };
    },

    methods: {
        ...mapGetters(["getToken"]),

        async getGameSettings() {
            let url = "/api/game_list?game_id=1";
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });

            if (response.data) {
                this.gameSettings = response.data.twod_settings;
                this.selectedGameSetting = this.gameSettings[0];
                this.getNumbers();
            }
        },

        gameSettingSelectChanged() {
            this.checkedNumber = [];
            this.getNumbers();
        },

        async getNumbers() {
            let url = `/api/closing_number_list?game_setting_id=${this.selectedGameSetting.id}`;
            // gameSettingId;
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.formattedNumbers = response.data.closing_number_list;
                this.break_percentage = response.data.break_percentage;
            } else {
            }
        },

        selectAllNumber() {
            if (!this.checkAll) {
                this.checkAll = true;
                const allNumbersSet = new Set(this.checkedNumber);
                this.formattedNumbers.forEach((item) => {
                    allNumbersSet.add(item.number);
                });
                this.checkedNumber = Array.from(allNumbersSet);
            } else {
                this.checkAll = false;
                this.checkedNumber = [];
            }
        },

        async closeNumber() {
            if (this.checkedNumber.length == 0) {
                return;
            }
            let formData = new FormData();
            formData.append("number", JSON.stringify(this.checkedNumber));
            if(parseInt(this.amount)){
                formData.append("amount", this.amount);
            }else{
                formData.append("amount", "");
            }
            formData.append("game_setting_id", this.selectedGameSetting.id);
            formData.append("game_id", 1);
            let url = "/api/create_closing_number";
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken(),
            });
            if (response.success) {
                this.checkAll = false;
                this.checkedNumber = [];
                this.amount = 200000;
                this.getNumbers();
                this.$notify({
                    text: response.message,
                    type: "info",
                });
            } else {
                this.$notify({
                    text: response.message,
                    type: "error",
                });
            }
        },

        async openNumber() {
            let formData = new FormData();
            formData.append("number", this.checkedNumber);
            formData.append("game_setting_id", this.selectedGameSetting.id);
            let url = "/api/2d/closing_numbers/set_inactive";
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken(),
            });
            if (response.success) {
                this.checkAll = false;
                this.checkedNumber = [];
                this.getNumbers();
                this.$notify({
                    text: response.message,
                    type: "info",
                });
            } else {
                this.$notify({
                    text: response.message,
                    type: "error",
                });
            }
        },

        numbersStartingWith(col, checked) {
            console.log(col, checked);
            const start = parseInt(col + "0");
            const digits = Array.from({ length: 10 }, (_, i) =>
                String(start + i).padStart(2, "0")
            );

            if (checked) {
                this.checkedNumber = [
                    ...this.checkedNumber,
                    ...digits.map((digit) => digit),
                ];
            } else {
                this.checkedNumber = this.checkedNumber.filter(
                    (number) => !digits.some((digit) => digit === number)
                );
            }

            console.log(digits);
        },
        formatTime(time){
            if(time){
                return moment(time,'H:m:s').format('hh:mm A');
            }
        },
    },

    created() {
        this.getGameSettings();
        // this.getTimeStatus();
        // this.getNumbers();
    },

    mounted() {
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>

