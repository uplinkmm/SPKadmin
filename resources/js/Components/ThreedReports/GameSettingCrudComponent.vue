<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex">
                <button
                    type="button"
                    class="inline-block rounded bg-[#303030] px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:shadow-primary-2 focus:outline-none focus:ring-0"
                    data-twe-toggle="modal"
                    data-twe-target="#handleModal"
                    data-twe-ripple-init
                    data-twe-ripple-color="light"
                >
                    Add Game Setting
                </button>
            </div>
            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="">
                    <div class="overflow-hidden">
                        <table
                            class="min-w-full text-left text-sm font-inter text-black"
                        >
                            <thead
                                class="border-b border-neutral-200 font-medium"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-4">No</th>
                                    <th scope="col" class="px-6 py-4">
                                        Opening
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Closing
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Lottery Time
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Multiplier
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Twist Multiplier
                                    </th>
                                    <th scope="col" class="px-6 py-4">
                                        Closing Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(setting, index) in settingList"
                                    :key="index"
                                    class="border-b border-neutral-200 transition duration-300 ease-in-out hover:bg-neutral-100"
                                >
                                    <td
                                        class="whitespace-nowrap px-6 py-4 font-medium"
                                    >
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{
                                            formatDate(
                                                setting.opening_date_time
                                            )
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{
                                            formatDate(
                                                setting.closing_date_time
                                            )
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{
                                            formatDate(
                                                setting.lottery_date_time
                                            )
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ setting.bet_multiplier }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ setting.twist_multiplier }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{
                                            setting.closing_amount?.toLocaleString()
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
    <!--Create Modal -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="handleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
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
                    <h5
                        class="text-xl font-medium leading-normal text-surface"
                        id="exampleModalLabel"
                    >
                        Add Game Setting
                    </h5>
                    <button
                        type="button"
                        id="closeAddModal"
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

                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6">
                        <label for="opening" class="text-sm mb-3 relative block"
                            >Opening Date Time</label
                        >
                        <input
                            type="datetime-local"
                            v-model="openDateTime"
                            @change="getOpenDateTime()"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                        />
                    </div>
                    <div class="mb-6">
                        <label for="closing" class="text-sm mb-3 relative block"
                            >Closing Date Time</label
                        >
                        <input
                            type="datetime-local"
                            v-model="closeDateTime"
                            @change="getCloseDateTime()"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="multiplier"
                            class="text-sm mb-3 relative block"
                            >Bet Multiplier</label
                        >
                        <input
                            type="text"
                            v-model="betMultiplier"
                            id="multiplier"
                            placeholder="Multiplier"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="twist_multiplier"
                            class="text-sm mb-3 relative block"
                            >Twist Multiplier</label
                        >
                        <input
                            type="text"
                            v-model="twistMultiplier"
                            id="twist_multiplier"
                            placeholder="Twist Multiplier"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="closing_amount"
                            class="text-sm mb-3 relative block"
                            >Closing Amount</label
                        >
                        <input
                            type="text"
                            v-model="closingAmount"
                            id="closing_amount"
                            placeholder="Closing Amount"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
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
                        @click="btnClickedAddGameSetting()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        {{ loading ? "Loading..." : "Add" }}
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
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        SearchBox,
    },
    data() {
        return {
            numberList: null,
            openDateTime: null,
            newOpenDateTime: null,
            closeDateTime: null,
            newCloseDateTime: null,
            betMultiplier: null,
            twistMultiplier: null,
            closingAmount: null,
            settingList: null,
            search_input: "",
            loading: false,
        };
    },

    methods: {
        ...mapGetters(["getToken"]),

        async getSettingList() {
            let url = `/api/3d/game_settings?search_input=${this.search_input}`;

            let response = await getApiData({
                url: url,
                token: this.getToken(),
            });
            if (response.success) {
                this.settingList = response.data;
            }
        },
        getOpenDateTime() {
            let openYear = this.openDateTime.slice(0, 4);
            let openMonth = this.openDateTime.slice(5, 7);
            let openDate = this.openDateTime.slice(8, 10);
            let openTime = this.openDateTime.slice(11, 16);
            this.newOpenDateTime =
                openDate + "-" + openMonth + "-" + openYear + " " + openTime;
        },
        getCloseDateTime() {
            let closeYear = this.closeDateTime.slice(0, 4);
            let closeMonth = this.closeDateTime.slice(5, 7);
            let closeDate = this.closeDateTime.slice(8, 10);
            let closeTime = this.closeDateTime.slice(11, 16);
            this.newCloseDateTime =
                closeDate +
                "-" +
                closeMonth +
                "-" +
                closeYear +
                " " +
                closeTime;
        },
        btnClickedAddGameSetting() {
            this.addGameSetting();
        },
        async addGameSetting() {
            let formData = new FormData();
            formData.append("opening_date_time", this.openDateTime);
            formData.append("closing_date_time", this.closeDateTime);
            formData.append("bet_multiplier", this.betMultiplier);
            formData.append("twist_multiplier", this.twistMultiplier);
            formData.append("closing_amount", this.closingAmount);
            let url = "/api/3d/game_settings";
            this.loading = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken(),
            });
            this.loading = false;
            if (response.success) {
                this.getSettingList();
                console.log("setting added");
                document.getElementById("closeAddModal").click();
            } else {
                console.log(response.error);
            }
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY h:m A");
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getSettingList(true);
        },
    },

    created() {},

    mounted() {
        this.getSettingList();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
