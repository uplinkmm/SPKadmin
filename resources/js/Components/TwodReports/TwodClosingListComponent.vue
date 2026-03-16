<template>
    <notifications position="top center" />

    <div class="frame-container min-h-[100vh]">
        <div
            v-for="gameSetting in orderedGameSettings"
            :key="gameSetting.id"
            class="mb-8 last:mb-0"
        >
            <div
                v-if="settingStates[gameSetting.id]"
                class="flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-4 justify-between px-2 sm:px-4 mb-4"
            >
                <div
                    class="flex flex-wrap gap-2 sm:gap-4 min-h-9 w-full lg:w-auto items-center justify-center lg:justify-start"
                >
                    <button
                        class="add-btn py-2"
                        @click="selectAllNumber(gameSetting.id)"
                    >
                        {{
                            settingStates[gameSetting.id].checkAll
                                ? "Un Check All"
                                : "Check All"
                        }}
                    </button>
                    <button
                        class="add-btn h-9"
                        :disabled="settingStates[gameSetting.id].loading_open"
                        @click="openNumber(gameSetting.id)"
                    >
                        {{
                            settingStates[gameSetting.id].loading_open
                                ? "Loading..."
                                : "Open"
                        }}
                    </button>
                    <div
                        class="flex flex-wrap items-center justify-center lg:justify-start gap-2 sm:gap-3 w-full lg:w-auto lg:ml-4"
                    >
                        <span
                            class="inline-flex h-9 items-center justify-center rounded-md border border-slate-300 bg-slate-100 px-3 text-sm font-bold text-slate-700"
                        >
                            {{ settingStates[gameSetting.id].break_percentage }}
                            %
                        </span>
                        <span
                            class="inline-flex h-9 items-center justify-center rounded-md border border-amber-300 bg-amber-100 px-3 text-center text-sm sm:text-base font-semibold text-amber-900 shadow-sm"
                        >
                            {{ gameSetting.name }}
                            ({{ formatTime(gameSetting.lottery_time) }})
                        </span>
                    </div>
                </div>

                <div
                    class="flex flex-wrap gap-2 sm:gap-4 w-full lg:w-auto items-center justify-start lg:justify-end"
                >
                    <div class="flex-1 min-w-[120px]">
                        <input
                            v-model="settingStates[gameSetting.id].amount"
                            type="number"
                            :id="'amount-' + gameSetting.id"
                            placeholder="Amount"
                            class="px-3 py-2 border border-gray-600 text-sm font-inter rounded-lg w-full"
                        />
                    </div>
                    <button
                        class="add-btn h-9 relative shrink-0 w-full sm:w-auto text-center justify-center"
                        :disabled="settingStates[gameSetting.id].loading_close"
                        @click="closeNumber(gameSetting)"
                    >
                        {{
                            settingStates[gameSetting.id].loading_close
                                ? "Loading..."
                                : "Close Number"
                        }}
                    </button>
                </div>
            </div>

            <div
                v-if="settingStates[gameSetting.id]"
                class="bg-white px-4 pt-4 pb-12 rounded-md overflow-x-auto"
            >
                <div
                    class="grid grid-cols-10 gap-y-1 gap-x-0 sm:gap-y-1 sm:gap-x-1 min-w-max w-full"
                >
                    <div
                        v-for="col in 10"
                        :key="'topCheck-' + gameSetting.id + '-' + col"
                        class="relative ml-3"
                    >
                        <label
                            :for="'topCheck-' + gameSetting.id + '-' + col"
                            class="px-1 py-2 sm:px-3 sm:py-4 flex gap-x-1 sm-gap-x-3 rounded-md text-md sm:text-lg"
                        >
                            <input
                                :id="'topCheck-' + gameSetting.id + '-' + col"
                                type="checkbox"
                                :checked="
                                    settingStates[gameSetting.id].topChecks[
                                        col - 1
                                    ]
                                "
                                @change="
                                    numbersStartingWith(
                                        gameSetting.id,
                                        col - 1,
                                        $event.target.checked
                                    )
                                "
                            />
                            {{ col - 1 }}ထိပ်
                        </label>
                    </div>

                    <div
                        v-for="(num, index) in settingStates[gameSetting.id]
                            .formattedNumbers"
                        :key="gameSetting.id + '-number-' + index"
                        class="relative"
                        :style="{
                            gridColumn: Math.floor(index / 10) + 1,
                            gridRow: (index % 10) + 2,
                        }"
                    >
                        <div
                            class="block px-1 py-2 sm:px-3 sm:py-4 opacity-70"
                            :class="num.is_closing ? 'bg-gray-300' : ''"
                        >
                            <label
                                :for="'check-' + gameSetting.id + '-' + num.number"
                                class="px-3 py-4 flex gap-x-1 sm:gap-x-3 rounded-md text-md sm:text-lg"
                            >
                                <input
                                    :id="
                                        'check-' + gameSetting.id + '-' + num.number
                                    "
                                    type="checkbox"
                                    v-model="
                                        settingStates[gameSetting.id].checkedNumber
                                    "
                                    :value="normalizeNumber(num.number)"
                                    @change="syncSelectionState(gameSetting.id)"
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
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import moment from "moment";

export default {
    data() {
        return {
            gameSettings: [],
            settingStates: {},
        };
    },

    computed: {
        orderedGameSettings() {
            const statusOrder = { morning: 0, evening: 1 };
            return [...this.gameSettings].sort((a, b) => {
                const aOrder = statusOrder[a.time_status] ?? 99;
                const bOrder = statusOrder[b.time_status] ?? 99;

                if (aOrder !== bOrder) {
                    return aOrder - bOrder;
                }

                return a.id - b.id;
            });
        },
    },

    methods: {
        ...mapGetters(["getToken"]),

        createDefaultState(gameSetting) {
            return {
                checkedNumber: [],
                amount: gameSetting?.closing_amount ?? "",
                formattedNumbers: [],
                checkAll: false,
                break_percentage: "",
                loading_open: false,
                loading_close: false,
                topChecks: Array(10).fill(false),
            };
        },

        async getGameSettings() {
            let url = "/api/game_list?game_id=1";
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });

            if (response.data) {
                this.gameSettings = response.data.twod_settings ?? [];
                this.settingStates = {};

                this.gameSettings.forEach((gameSetting) => {
                    this.settingStates[gameSetting.id] =
                        this.createDefaultState(gameSetting);
                });

                await Promise.all(
                    this.gameSettings.map((gameSetting) =>
                        this.getNumbers(gameSetting.id)
                    )
                );
            }
        },

        async getNumbers(gameSettingId) {
            const state = this.settingStates[gameSettingId];
            if (!state) {
                return;
            }

            let url = `/api/closing_number_list?game_setting_id=${gameSettingId}`;
            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });

            if (response.data) {
                state.formattedNumbers = response.data.closing_number_list;
                state.break_percentage = response.data.break_percentage;
                this.syncSelectionState(gameSettingId);
            }
        },

        normalizeNumber(number) {
            return String(number).padStart(2, "0");
        },

        syncSelectionState(gameSettingId) {
            const state = this.settingStates[gameSettingId];
            if (!state) {
                return;
            }

            const selectedSet = new Set(
                state.checkedNumber.map((number) =>
                    this.normalizeNumber(number)
                )
            );
            const allNumberSet = new Set(
                state.formattedNumbers.map((item) =>
                    this.normalizeNumber(item.number)
                )
            );

            state.checkAll =
                allNumberSet.size > 0 &&
                Array.from(allNumberSet).every((number) =>
                    selectedSet.has(number)
                );

            for (let col = 0; col < 10; col++) {
                const start = col * 10;
                const digits = Array.from({ length: 10 }, (_, i) =>
                    this.normalizeNumber(start + i)
                );
                state.topChecks[col] = digits.every((digit) =>
                    selectedSet.has(digit)
                );
            }
        },

        selectAllNumber(gameSettingId) {
            const state = this.settingStates[gameSettingId];
            if (!state) {
                return;
            }

            if (!state.checkAll) {
                const allNumbersSet = new Set(
                    state.formattedNumbers.map((item) =>
                        this.normalizeNumber(item.number)
                    )
                );
                state.checkedNumber = Array.from(allNumbersSet);
            } else {
                state.checkedNumber = [];
            }

            this.syncSelectionState(gameSettingId);
        },

        async closeNumber(gameSetting) {
            const state = this.settingStates[gameSetting.id];
            if (!state) {
                return;
            }

            if (isNaN(state.amount)) {
                this.$notify({
                    text: "Amount must be a number!",
                    type: "error",
                });
                return;
            }

            if (!state.amount) {
                this.$notify({
                    text: "Amount is required!",
                    type: "error",
                });
                return;
            }
            if (state.checkedNumber.length === 0) {
                this.$notify({
                    text: "Please select at least one number!",
                    type: "error",
                });
                return;
            }

            let formData = new FormData();
            formData.append("number", JSON.stringify(state.checkedNumber));
            if (parseInt(state.amount)) {
                formData.append("amount", state.amount);
            } else {
                formData.append("amount", "");
            }
            formData.append("game_setting_id", gameSetting.id);
            formData.append("game_id", 1);
            let url = "/api/create_closing_number";
            state.loading_close = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken(),
            });
            state.loading_close = false;

            if (response.success) {
                state.checkedNumber = [];
                state.amount = gameSetting.closing_amount ?? state.amount;
                this.syncSelectionState(gameSetting.id);
                await this.getNumbers(gameSetting.id);
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

        async openNumber(gameSettingId) {
            const state = this.settingStates[gameSettingId];
            if (!state) {
                return;
            }

            let formData = new FormData();
            formData.append("number", state.checkedNumber);
            formData.append("game_setting_id", gameSettingId);
            let url = "/api/2d/closing_numbers/set_inactive";
            state.loading_open = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken(),
            });
            state.loading_open = false;

            if (response.success) {
                state.checkedNumber = [];
                this.syncSelectionState(gameSettingId);
                await this.getNumbers(gameSettingId);
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

        numbersStartingWith(gameSettingId, col, checked) {
            const state = this.settingStates[gameSettingId];
            if (!state) {
                return;
            }

            const start = parseInt(col + "0");
            const digits = Array.from({ length: 10 }, (_, i) =>
                this.normalizeNumber(start + i)
            );

            if (checked) {
                const selectedSet = new Set(
                    state.checkedNumber.map((number) =>
                        this.normalizeNumber(number)
                    )
                );
                digits.forEach((digit) => selectedSet.add(digit));
                state.checkedNumber = Array.from(selectedSet);
            } else {
                const digitsSet = new Set(digits);
                state.checkedNumber = state.checkedNumber.filter(
                    (number) => !digitsSet.has(this.normalizeNumber(number))
                );
            }

            this.syncSelectionState(gameSettingId);
        },
        formatTime(time) {
            if (time) {
                return moment(time, "H:m:s").format("hh:mm A");
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
