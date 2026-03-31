<template>
    <notifications position="top center" />

    <div class="frame-container min-h-[100vh]">
        <div
            class="flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-4 justify-between px-2 sm:px-4 mb-4"
        >
            <div
                class="flex flex-wrap gap-2 sm:gap-4 h-9 w-full lg:w-auto items-center"
            >
                <button class="add-btn py-2" @click="selectAllNumber">
                    {{ checkAll ? "Un Check All" : "Check All" }}
                </button>
                <button
                    :disabled="loading_open"
                    class="add-btn h-9"
                    @click="openNumber"
                >
                    {{ loading_open ? "Loading..." : "Open" }}
                </button>
                <p class="font-bold pt-1 sm:pt-2 whitespace-nowrap">
                    {{ break_percentage }} %
                </p>
            </div>
            <div
                class="flex flex-wrap gap-2 sm:gap-4 w-full lg:w-auto items-center justify-start lg:justify-end"
            >
                <div>
                    <select
                        name=""
                        id=""
                        class="border border-gray-600 text-sm font-inter rounded-lg bg-white min-w-[8rem] px-2 h-9"
                        v-model="from_to_value"
                    >
                        <option
                            v-for="(from_to, index) in from_to_numbers"
                            :key="index"
                            :value="from_to"
                        >
                            {{ from_to.name }}
                        </option>
                    </select>
                </div>
                <div class="flex-1 min-w-[120px]">
                    <input
                        type="number"
                        id="amount"
                        placeholder="Amount"
                        v-model="amount"
                        class="px-3 py-2 border border-gray-600 text-sm font-inter rounded-lg w-full"
                    />
                </div>
                <button
                    class="add-btn h-9 relative shrink-0 w-full sm:w-auto text-center justify-center"
                    :disabled="loading_close"
                    @click="closeNumber"
                >
                    {{ loading_close ? "Loading..." : "Close Number" }}
                </button>
            </div>
        </div>
        <div class="bg-white px-4 pt-4 pb-12 rounded-md overflow-x-auto">
            <div
                v-if="!selectedGameSetting"
                class="text-center py-8 text-gray-600"
            >
                Please create 3D game setting first
            </div>
            <div
                v-else
                class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 xl:grid-cols-10 gap-2 sm:gap-3 w-full"
            >
                <div
                    v-for="(num, index) in formattedNumbers"
                    :key="index"
                    class="relative"
                >
                    <div
                        class="block px-1 py-2 sm:px-3 sm:py-4 opacity-70"
                        :class="num.is_closing ? 'bg-gray-300' : ''"
                    >
                        <label
                            :for="'check' + num.number"
                            class="px-3 py-4 flex gap-x-1 sm:gap-x-3 rounded-md text-md sm:text-lg"
                        >
                            <input
                                :id="'check' + num.number"
                                type="checkbox"
                                v-model="checkedNumber"
                                :value="num.number"
                            />
                            {{ num.number }}
                        </label>
                        <p
                            class="pl-4 text-md sm:text-lg"
                            v-if="num.closing_amount"
                        >
                            {{ num.closing_amount ? num.closing_amount : "" }}
                        </p>
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

export default {
    data() {
        return {
            numbers: "",
            checkedNumber: [],
            gameSettings: "",
            selectedGameSetting: "",
            amount: "",
            checkAll: false,
            break_percentage: "",
            from_to_numbers: [
                {
                    name: "000 - 099",
                    value: 100,
                },
                {
                    name: "100 - 199",
                    value: 200,
                },
                {
                    name: "200 - 299",
                    value: 300,
                },
                {
                    name: "300 - 399",
                    value: 400,
                },
                {
                    name: "400 - 499",
                    value: 500,
                },
                {
                    name: "500 - 599",
                    value: 600,
                },
                {
                    name: "600 - 699",
                    value: 700,
                },
                {
                    name: "700 - 700",
                    value: 800,
                },
                {
                    name: "800 - 899",
                    value: 900,
                },
                {
                    name: "900 - 999",
                    value: 1000,
                },
            ],
            from_to_value: {
                name: "000 - 099",
                value: 100,
            },
            loading_close: false,
            loading_open: false,
        };
    },
    computed: {
        formattedNumbers() {
            return this.numbers.slice(
                this.from_to_value.value - 100,
                this.from_to_value.value
            );
        },
    },
    methods: {
        ...mapGetters(["getToken"]),
        async getGameSettings() {
            let url = "/api/game_list?game_id=2";
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });

            if (response.data) {
                this.selectedGameSetting = response.data.threed_setting;
                this.amount = this.selectedGameSetting.closing_amount;
                this.getNumbers();
            }
        },

        async getNumbers() {
            let url = `/api/closing_number_list?game_setting_id=${this.selectedGameSetting.id}`;
            // gameSettingId;
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.data) {
                this.numbers = response.data.closing_number_list;
                this.break_percentage = response.data.break_percentage;
            } else {
            }
        },
        selectAllNumber() {
            if (!this.checkAll) {
                this.checkAll = true;
                const allNumbersSet = new Set(this.checkedNumber);
                this.numbers.forEach((item) => {
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
                this.$notify({
                    text: "Please select at least one number!",
                    type: "error",
                });
                return;
            }
            if (isNaN(this.amount)) {
                this.$notify({
                    text: "Amount must be a number!",
                    type: "error",
                });
                return;
            }
            if (!this.amount) {
                this.$notify({
                    text: "Amount is required!",
                    type: "error",
                });
                return;
            }
            let formData = new FormData();
            formData.append("number", JSON.stringify(this.checkedNumber));
            if (parseInt(this.amount)) {
                formData.append("amount", this.amount);
            } else {
                formData.append("amount", "");
            }
            formData.append("game_setting_id", this.selectedGameSetting.id);
            formData.append("game_id", 2);
            let url = "/api/create_closing_number";
            this.loading_close = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken(),
            });
            this.loading_close = false;
            if (response.success) {
                this.checkAll = false;
                this.checkedNumber = [];
                this.amount = this.selectedGameSetting.closing_amount;
                this.$notify({
                    text: response.message,
                    type: "info",
                });
                this.getNumbers();
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
            this.loading_open = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken(),
            });
            this.loading_open = false;
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
    },

    mounted() {
        this.getGameSettings();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
