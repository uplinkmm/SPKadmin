<template>
    <notifications position="top center" />

    <div class="frame-container min-h-[100vh]">
        <div
            v-for="gameSetting in orderedGameSettings"
            :key="gameSetting.id"
            class="mb-8 last:mb-0"
        >
            <div
                class="bg-white px-4 pt-4 pb-4 rounded-md mb-4 overflow-x-auto"
            >
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b bg-slate-50">
                        <tr>
                            <th
                                scope="col"
                                class="px-6 py-4 font-medium text-slate-700"
                            >
                                Status
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 font-medium text-slate-700"
                            >
                                Name
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 font-medium text-slate-700"
                            >
                                Odds
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 font-medium text-slate-700"
                            >
                                Closing Amount
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 font-medium text-slate-700"
                            >
                                Opening Time
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 font-medium text-slate-700"
                            >
                                Closing Time
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-4 font-medium text-slate-700"
                            >
                                Lottery Time
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div
                                    class="relative inline-block w-20 h-8 sm:w-24 sm:h-10"
                                >
                                    <input
                                        :id="`table-toggle-${gameSetting.id}`"
                                        type="checkbox"
                                        class="hidden"
                                        :checked="
                                            gameSetting.is_active ? true : false
                                        "
                                        :disabled="
                                            settingStates[gameSetting.id]
                                                .loading_toggle
                                        "
                                        @change="
                                            toggleGameSettingIsActive(
                                                gameSetting,
                                                $event.target.checked
                                            )
                                        "
                                    />
                                    <label
                                        :for="`table-toggle-${gameSetting.id}`"
                                        class="block rounded-md p-0.5 sm:p-1 relative flex items-center justify-between"
                                        :class="[
                                            gameSetting.is_active
                                                ? 'bg-green-500'
                                                : 'bg-gray-300',
                                            settingStates[gameSetting.id]
                                                .loading_toggle
                                                ? 'cursor-not-allowed opacity-70'
                                                : 'cursor-pointer',
                                        ]"
                                    >
                                        <span
                                            class="absolute left-2/4 top-1/2 -translate-y-1/2 text-[10px] sm:text-xs font-bold text-white"
                                            v-if="!gameSetting.is_active"
                                        >
                                            Close
                                        </span>
                                        <span
                                            class="absolute left-1/4 top-1/2 -translate-y-1/2 text-[10px] sm:text-xs font-bold text-white"
                                            v-if="gameSetting.is_active"
                                        >
                                            Open
                                        </span>
                                        <span
                                            class="block w-4 h-6 sm:w-5 sm:h-8 bg-white rounded-md shadow transform transition-transform"
                                            :class="{
                                                'translate-x-14 sm:translate-x-16':
                                                    gameSetting.is_active,
                                            }"
                                        >
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                {{ gameSetting.name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button
                                    type="button"
                                    data-twe-toggle="modal"
                                    data-twe-target="#edit_field_modal"
                                    data-twe-ripple-init
                                    data-twe-ripple-color="light"
                                    @click="
                                        openEditModal(
                                            gameSetting,
                                            'bet_multiplier',
                                            'Odds',
                                            'number'
                                        )
                                    "
                                    class="text-left w-full hover:bg-slate-100 px-0 py-0"
                                >
                                    <span class="underline-dotted">
                                        {{ gameSetting.bet_multiplier }}
                                    </span>
                                </button>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button
                                    type="button"
                                    data-twe-toggle="modal"
                                    data-twe-target="#edit_field_modal"
                                    data-twe-ripple-init
                                    data-twe-ripple-color="light"
                                    @click="
                                        openEditModal(
                                            gameSetting,
                                            'closing_amount',
                                            'Closing Amount',
                                            'number'
                                        )
                                    "
                                    class="text-left w-full hover:bg-slate-100 px-0 py-0"
                                >
                                    <span class="underline-dotted">
                                        {{
                                            gameSetting.closing_amount?.toLocaleString()
                                        }}
                                    </span>
                                </button>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button
                                    type="button"
                                    data-twe-toggle="modal"
                                    data-twe-target="#edit_field_modal"
                                    data-twe-ripple-init
                                    data-twe-ripple-color="light"
                                    @click="
                                        openEditModal(
                                            gameSetting,
                                            'opening_time',
                                            'Opening Time',
                                            'time'
                                        )
                                    "
                                    class="text-left w-full hover:bg-slate-100 px-0 py-0"
                                >
                                    <span class="underline-dotted">
                                        {{
                                            formatTime(gameSetting.opening_time)
                                        }}
                                    </span>
                                </button>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button
                                    type="button"
                                    data-twe-toggle="modal"
                                    data-twe-target="#edit_field_modal"
                                    data-twe-ripple-init
                                    data-twe-ripple-color="light"
                                    @click="
                                        openEditModal(
                                            gameSetting,
                                            'closing_time',
                                            'Closing Time',
                                            'time'
                                        )
                                    "
                                    class="text-left w-full hover:bg-slate-100 px-0 py-0"
                                >
                                    <span class="underline-dotted">
                                        {{
                                            formatTime(gameSetting.closing_time)
                                        }}
                                    </span>
                                </button>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button
                                    type="button"
                                    data-twe-toggle="modal"
                                    data-twe-target="#edit_field_modal"
                                    data-twe-ripple-init
                                    data-twe-ripple-color="light"
                                    @click="
                                        openEditModal(
                                            gameSetting,
                                            'lottery_time',
                                            'Lottery Time',
                                            'time'
                                        )
                                    "
                                    class="text-left w-full hover:bg-slate-100 px-0 py-0"
                                >
                                    <span class="underline-dotted">
                                        {{
                                            formatTime(gameSetting.lottery_time)
                                        }}
                                    </span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div
                v-if="settingStates[gameSetting.id]"
                class="flex flex-wrap md:flex-nowrap items-center gap-1.5 sm:gap-2 px-2 sm:px-3 lg:px-4 mb-4 md:overflow-x-auto"
            >
                <button
                    class="add-btn py-1.5 sm:py-2 px-2 text-xs sm:text-sm shrink-0"
                    @click="selectAllNumber(gameSetting.id)"
                >
                    {{
                        settingStates[gameSetting.id].checkAll
                            ? "Un Check All"
                            : "Check All"
                    }}
                </button>
                <button
                    class="add-btn h-8 sm:h-9 px-2 text-xs sm:text-sm shrink-0"
                    :disabled="settingStates[gameSetting.id].loading_open"
                    @click="openNumber(gameSetting.id)"
                >
                    {{
                        settingStates[gameSetting.id].loading_open
                            ? "Loading..."
                            : "Open"
                    }}
                </button>
                <span
                    class="inline-flex h-8 sm:h-9 shrink-0 items-center justify-center rounded-md border border-slate-300 bg-slate-100 px-2 sm:px-3 text-xs sm:text-sm font-bold text-slate-700"
                >
                    {{ settingStates[gameSetting.id].break_percentage }} %
                </span>
                <span
                    class="inline-flex h-8 sm:h-9 shrink-0 items-center justify-center rounded-md border border-amber-300 bg-amber-100 px-2 sm:px-3 text-center text-xs sm:text-sm md:text-base font-semibold text-amber-900 shadow-sm whitespace-nowrap"
                >
                    <span>{{ gameSetting.name }}</span>
                    <span class="hidden lg:inline"
                        >({{ formatTime(gameSetting.lottery_time) }})</span
                    >
                </span>
                <div class="w-24 sm:w-28 lg:w-40 shrink-0">
                    <input
                        v-model="settingStates[gameSetting.id].amount"
                        type="number"
                        :id="'amount-' + gameSetting.id"
                        placeholder="Amount"
                        class="px-2 py-1.5 sm:px-3 sm:py-2 border border-gray-600 text-xs sm:text-sm font-inter rounded-lg w-full"
                    />
                </div>
                <button
                    class="add-btn h-8 sm:h-9 px-2 text-xs sm:text-sm relative shrink-0 text-center justify-center"
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

            <div
                v-if="settingStates[gameSetting.id]"
                class="bg-white px-2 sm:px-4 pt-3 sm:pt-4 pb-8 sm:pb-12 rounded-md overflow-x-auto"
            >
                <div
                    class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 xl:grid-cols-10 gap-2 sm:gap-3 w-full"
                >
                    <div
                        v-for="col in 10"
                        :key="'topCheck-' + gameSetting.id + '-' + col"
                        class="relative text-yellow-600"
                    >
                        <label
                            :for="'topCheck-' + gameSetting.id + '-' + col"
                            class="flex items-center justify-center gap-x-0.5 sm:gap-x-1 rounded-md px-0.5 py-1 sm:px-2 sm:py-2 text-[11px] sm:text-sm lg:text-base"
                        >
                            <input
                                :id="'topCheck-' + gameSetting.id + '-' + col"
                                type="checkbox"
                                class="h-3 w-3 sm:h-4 sm:w-4"
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
                            <span>{{ col - 1 }}</span>
                            <span class="hidden sm:inline">ထိပ်</span>
                        </label>
                    </div>

                    <div
                        v-for="(num, index) in settingStates[gameSetting.id]
                            .formattedNumbers"
                        :key="gameSetting.id + '-number-' + index"
                        class="relative"
                    >
                        <div
                            class="block px-0.5 py-1 sm:px-2 sm:py-2 opacity-70"
                            :class="num.is_closing ? 'bg-gray-300' : ''"
                        >
                            <label
                                :for="
                                    'check-' + gameSetting.id + '-' + num.number
                                "
                                class="flex items-center justify-center gap-x-0.5 sm:gap-x-1 rounded-md px-0.5 py-1 text-xs sm:text-sm lg:text-base"
                            >
                                <input
                                    :id="
                                        'check-' +
                                        gameSetting.id +
                                        '-' +
                                        num.number
                                    "
                                    type="checkbox"
                                    class="h-3 w-3 sm:h-4 sm:w-4"
                                    v-model="
                                        settingStates[gameSetting.id]
                                            .checkedNumber
                                    "
                                    :value="normalizeNumber(num.number)"
                                    @change="syncSelectionState(gameSetting.id)"
                                />
                                {{ num.number }}
                            </label>
                            <p
                                class="pl-1 sm:pl-2 text-[11px] sm:text-sm lg:text-base"
                                v-if="num.closing_amount"
                            >
                                {{
                                    num.closing_amount ? num.closing_amount : ""
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="edit_field_modal"
        tabindex="-1"
        aria-hidden="true"
    >
        <div
            data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
        >
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none"
            >
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4"
                >
                    <h5 class="text-xl font-medium leading-normal text-surface">
                        Edit {{ editField.title }}
                    </h5>
                    <button
                        type="button"
                        id="closeEditModal"
                        class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-twe-modal-dismiss
                        aria-label="Close"
                    >
                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </span>
                    </button>
                </div>

                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6">
                        <label
                            v-if="editField.input_type === 'time'"
                            class="text-sm mb-3 relative block"
                            >{{ editField.title }}</label
                        >
                        <VueDatePicker
                            v-if="editField.input_type === 'time'"
                            v-model="editField.value"
                            time-picker
                            :is-24="true"
                            auto-apply
                        />
                        <div v-else>
                            <label class="text-sm mb-3 relative block">{{
                                editField.title
                            }}</label>
                            <input
                                type="number"
                                v-model="editField.value"
                                class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4"
                >
                    <button
                        type="button"
                        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black focus:outline-none focus:ring-00"
                        data-twe-modal-dismiss
                        data-twe-ripple-init
                        data-twe-ripple-color="light"
                    >
                        Close
                    </button>
                    <button
                        :disabled="loading"
                        type="button"
                        @click="updateGameSettingField()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        {{ loading ? "Loading..." : "Update" }}
                    </button>
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
            loading: false,
            editField: {
                id: "",
                column: "",
                value: "",
                title: "",
                input_type: "number",
            },
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

        createDefaultState() {
            return {
                checkedNumber: [],
                amount: "",
                formattedNumbers: [],
                checkAll: false,
                break_percentage: "",
                loading_open: false,
                loading_close: false,
                loading_toggle: false,
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
                        this.createDefaultState();
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
                const gameSetting = this.gameSettings.find(
                    (setting) => setting.id === gameSettingId
                );
                if (gameSetting && response.data.game_setting) {
                    gameSetting.is_active =
                        response.data.game_setting.is_active;
                }
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
                // state.amount = gameSetting.closing_amount ?? state.amount;
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

        async toggleGameSettingIsActive(gameSetting, value) {
            const state = this.settingStates[gameSetting.id];
            if (!state || state.loading_toggle) {
                return;
            }

            const previousState = Number(gameSetting.is_active);
            gameSetting.is_active = value ? 1 : 0;

            let formData = new FormData();
            formData.append("id", gameSetting.id);
            formData.append("is_active", value ? 1 : 0);

            state.loading_toggle = true;
            let response = await postApiData({
                url: "/api/game_settings/toggle_is_active",
                form_data: formData,
                token: this.getToken(),
            });
            state.loading_toggle = false;

            if (response.success) {
                await this.getNumbers(gameSetting.id);
                this.$notify({
                    text:
                        response.message ||
                        "Game setting active status updated successfully.",
                    type: "info",
                });
            } else {
                gameSetting.is_active = previousState;
                this.$notify({
                    text:
                        response.message ||
                        "Unable to update game setting active status.",
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

        openEditModal(gameSetting, column, title, inputType) {
            this.editField.id = gameSetting.id;
            this.editField.column = column;
            this.editField.title = title;
            this.editField.input_type = inputType;

            if (inputType === "time") {
                const timeValue = gameSetting[column];
                if (timeValue) {
                    const [hours, minutes] = timeValue.split(":").map(Number);
                    this.editField.value = { hours, minutes };
                } else {
                    this.editField.value = { hours: 0, minutes: 0 };
                }
            } else {
                this.editField.value = gameSetting[column];
            }
        },

        async updateGameSettingField() {
            const gameSetting = this.gameSettings.find(
                (gs) => gs.id === this.editField.id
            );
            if (!gameSetting) return;

            let valueToSend = this.editField.value;
            if (this.editField.input_type === "time") {
                const { hours, minutes } = this.editField.value;
                valueToSend = moment()
                    .hours(hours)
                    .minutes(minutes)
                    .format("HH:mm");
            }

            let formData = new FormData();
            formData.append("type", "game_setting");
            formData.append("column", this.editField.column);
            formData.append("id", this.editField.id);
            formData.append("value", valueToSend);

            this.loading = true;
            let response = await postApiData({
                url: "/api/update_dashboard_data",
                form_data: formData,
                token: this.getToken(),
            });
            this.loading = false;

            if (response.success) {
                gameSetting[this.editField.column] =
                    this.editField.input_type === "time"
                        ? valueToSend + ":00"
                        : valueToSend;
                this.$notify({
                    text: response.message || "Updated successfully",
                    type: "info",
                });
                const closeBtn = document.getElementById("closeEditModal");
                if (closeBtn) closeBtn.click();
            } else {
                this.$notify({
                    text: response.message || "Update failed",
                    type: "error",
                });
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
