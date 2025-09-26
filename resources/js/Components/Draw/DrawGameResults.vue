<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex"></div>

            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <div class="flex justify-between mb-2">
                    <div class="flex gap-6">
                        <div class="flex items-center mb-4">
                            <label for="itemsPerPage" class="mr-2 text-gray-700"
                                >Show</label
                            >
                            <select
                                id="itemsPerPage"
                                @change="getResults(true)"
                                v-model="per_page"
                                class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500"
                            >
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="50000">All</option>
                            </select>
                        </div>
                        <select
                            v-model="game_setting_id"
                            name=""
                            id=""
                            class="select-form"
                            @change="getResults(true)"
                        >
                            <option value="">Select Lottery Game</option>
                            <option
                                :value="draw.id"
                                v-for="(draw, index) in draws"
                                :key="index"
                            >
                                {{ draw.name }}
                            </option>
                        </select>
                    </div>
                    <button
                        type="button"
                        data-twe-toggle="modal"
                        data-twe-target="#new_result"
                        data-twe-ripple-init
                        data-twe-ripple-color="light"
                        @click="addResult"
                        class="inline-block bg-blue-800 px-8 pb-2 pt-2.5 text-xs text-white rounded-md"
                    >
                        Add New
                    </button>
                </div>

                <div class="">
                    <div class="table-container">
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No</th>
                                    <th scope="col" class="">Game</th>
                                    <th scope="col" class="">Prize Title</th>
                                    <th scope="col" class="">Prize</th>
                                    <th scope="col" class="">Winning Nubmer</th>
                                    <th scope="col" class="">Stage</th>
                                    <th scope="col" class="">Date Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(result, index) in results"
                                    :key="index"
                                    class=""
                                >
                                    <td class="whitespace-nowrap">
                                        {{
                                            ++index +
                                            (currentPage - 1) * per_page
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ result.prize.game_setting.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ result.prize.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ result.prize.prize }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span
                                            v-if="
                                                !result.is_approved &&
                                                game_result_edit.id != result.id
                                            "
                                        >
                                            <span> {{ result.number }}</span>
                                            <i
                                                v-if="!result.is_approve"
                                                @click="
                                                    game_result_edit.id =
                                                        result.id
                                                "
                                                class="pl-4 fal fa-edit cursor-pointer"
                                            ></i>
                                        </span>
                                        <div
                                            v-if="
                                                !result.is_approved &&
                                                game_result_edit.id == result.id
                                            "
                                            class="flex items-center gap-3"
                                        >
                                            <svg
                                                class="cursor-pointer"
                                                width="15"
                                                height="15"
                                                viewBox="0 0 15 15"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                @click="
                                                    game_result_edit.id = ''
                                                "
                                            >
                                                <path
                                                    d="M11.7816 4.03157C12.0062 3.80702 12.0062 3.44295 11.7816 3.2184C11.5571 2.99385 11.193 2.99385 10.9685 3.2184L7.50005 6.68682L4.03164 3.2184C3.80708 2.99385 3.44301 2.99385 3.21846 3.2184C2.99391 3.44295 2.99391 3.80702 3.21846 4.03157L6.68688 7.49999L3.21846 10.9684C2.99391 11.193 2.99391 11.557 3.21846 11.7816C3.44301 12.0061 3.80708 12.0061 4.03164 11.7816L7.50005 8.31316L10.9685 11.7816C11.193 12.0061 11.5571 12.0061 11.7816 11.7816C12.0062 11.557 12.0062 11.193 11.7816 10.9684L8.31322 7.49999L11.7816 4.03157Z"
                                                    fill="currentColor"
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                ></path>
                                            </svg>
                                            <input
                                                type="text"
                                                v-model="result.number"
                                                class="px-2 w-16 py-1 border-neutral-300 border rounded-md focus:outline-none focus:ring-0 focus:shadow-none"
                                            />
                                            <svg
                                                @click="
                                                    editLotteryNumber(result)
                                                "
                                                class="cursor-pointer"
                                                width="15"
                                                height="15"
                                                viewBox="0 0 15 15"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M11.4669 3.72684C11.7558 3.91574 11.8369 4.30308 11.648 4.59198L7.39799 11.092C7.29783 11.2452 7.13556 11.3467 6.95402 11.3699C6.77247 11.3931 6.58989 11.3355 6.45446 11.2124L3.70446 8.71241C3.44905 8.48022 3.43023 8.08494 3.66242 7.82953C3.89461 7.57412 4.28989 7.55529 4.5453 7.78749L6.75292 9.79441L10.6018 3.90792C10.7907 3.61902 11.178 3.53795 11.4669 3.72684Z"
                                                    fill="currentColor"
                                                    fill-rule="evenodd"
                                                    clip-rule="evenodd"
                                                ></path>
                                            </svg>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span
                                            v-if="result.is_approve"
                                            class="text-green-600"
                                            >Confirm</span
                                        >
                                        <span
                                            v-else
                                            class="text-yellow-600 cursor-pointer"
                                            title="Click to confirm"
                                        >
                                            <button
                                                class="px-2 py-4"
                                                type="button"
                                                data-twe-toggle="modal"
                                                data-twe-target="#confirm_result"
                                                data-twe-ripple-init
                                                data-twe-ripple-color="light"
                                                @click="
                                                    approved_result.id =
                                                        result.id
                                                "
                                            >
                                                Not Yet
                                            </button>
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ formatDate(result.updated_at) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4" v-if="getTotalCount > per_page">
                            <webPagination
                                :total-items-count="getTotalCount"
                                :items-per-page="per_page"
                                :current-page="currentPage"
                                active-color="#fff"
                                icon-color="#fff"
                                inactive-color="#c8b5db"
                                disabled-color="#c8b5db"
                                @pageChanged="
                                    setCurrentPage($event);
                                    getResults(false);
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Create Result -->
        <div
            data-twe-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="new_result"
            tabindex="-1"
            aria-labelledby="ModalLabel"
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
                        class="flex flex-shrink-0 pl-16 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4"
                    >
                        <h5
                            class="text-xl font-medium leading-normal text-surface"
                            id="ModalLabel"
                        >
                            Add Result
                        </h5>
                        <button
                            type="button"
                            id="closeModal"
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
                    <div
                        class="relative flex-auto px-16 py-2"
                        data-twe-modal-body-ref
                    >
                        <div class="mb-6">
                            <label for="Name" class="text-sm relative block"
                                >Lottery Game</label
                            >
                            <select
                                v-model="create_game_setting_id"
                                name=""
                                id=""
                                class="select-form"
                                @change="getPrizeListsByGameSetting()"
                            >
                                <option value="">Select Lottery Game</option>
                                <option
                                    :value="draw.id"
                                    v-for="(draw, index) in draws"
                                    :key="index"
                                >
                                    {{ draw.name }}
                                </option>
                            </select>
                        </div>
                        <div
                            class="mb-5"
                            v-for="(prize, index) in prizes"
                            :key="index"
                        >
                            <label for="" class="text-sm relative block"
                                >{{ prize.name }} ({{ prize.prize }})</label
                            >
                            <input
                                type="text"
                                placeholder="number"
                                v-model="prize.number"
                                class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                            />
                        </div>
                    </div>

                    <div
                        class="flex px-16 flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4"
                    >
                        <button
                            type="button"
                            class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black focus:outline-none focus:ring-00"
                            data-twe-modal-dismiss
                            data-twe-ripple-init
                            id="modalClose"
                            data-twe-ripple-color="light"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            @click="createResult"
                            :disabled="loading"
                            class="bg-blue-800 px-8 disabled:bg-blue-400 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        >
                            {{ loading ? "Adding..." : "Add" }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Result -->
        <div
            data-twe-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="confirm_result"
            tabindex="-1"
            aria-labelledby="ModalLabel"
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
                            id="ModalLabel"
                        ></h5>
                        <button
                            type="button"
                            id="closeModalApprove"
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
                    <div
                        class="relative flex-auto px-4 py-4"
                        data-twe-modal-body-ref
                    >
                        <p class="text-lg pl-8 font-medium">
                            Are you sure to confirm the results?
                        </p>
                    </div>
                    <div
                        class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4"
                    >
                        <button
                            type="button"
                            class="inline-block bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black focus:outline-none focus:ring-00"
                            data-twe-modal-dismiss
                            data-twe-ripple-init
                            id="modalCloseDeposit"
                            data-twe-ripple-color="light"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            @click="approvedLotteryNumber"
                            class="bg-blue-600 px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        >
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters, mapMutations } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import WebPagination from "../Common/webPagination.vue";
import SearchBox from "../Common/SearchBox.vue";
import moment from "moment";

export default {
    components: {
        WebPagination,
        SearchBox,
    },
    data() {
        return {
            results: [],
            draws: [],
            new_result: {
                number: "",
                prize_id: "",
                game_setting_id: "",
            },
            game_setting_id: "",
            create_game_setting_id: "",
            prizes: [],
            game_result_edit: {
                id: "",
                number: "",
                prize_id: "",
                game_setting_id: "",
            },
            approved_result: {
                id: "",
            },
            search_input: "",
            per_page: 50,
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),
        async getResults(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/lottery_winning_numbers?game_setting_id=${
                this.game_setting_id
            }&page=${this.currentPage}&search_input=${this.search_input}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.results = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        async createResult() {
            if (
                !this.create_game_setting_id ||
                this.prizes.length == 0 ||
                this.prizes.some((prize) => !prize.number)
            ) {
                this.$notify({
                    title: "Error!",
                    text: "Please fill all fields.",
                    type: "error",
                });
                return;
            }
            let url = "/api/lottery_winning_numbers";
            let formData = new FormData();
            formData.append("game_setting_id", this.create_game_setting_id);
            const temp = this.prizes.map((prize) => ({
                prize_id: prize.id,
                number: prize.number,
            }));
            formData.append("prizes", JSON.stringify(temp));
            this.loading = true;

            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            this.loading = false;
            if (response.data) {
                this.new_result = {
                    number: "",
                    prize_id: "",
                };
                this.getResults(false);
                this.modalClose("modalClose");
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });

                return;
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                });
                return;
            }
        },
        async getDraws() {
            let url = `/api/draws`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.draws = response.data;
            }
        },
        async getPrizeListsByGameSetting() {
            console.log(this.new_result.game_setting_id);
            let url = `/api/prize_list_by_game?game_setting_id=${this.create_game_setting_id}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.prizes = response.data.map((prize) => ({
                    ...prize,
                    number: null,
                }));
            }
        },
        async editLotteryNumber(result) {
            if (result.number == "" || result.number == null) {
                this.$notify({
                    title: "Error!",
                    text: "Please fill all fields.",
                    type: "error",
                });
                return;
            }
            let url = "/api/edit_lottery_winning_number";
            let formData = new FormData();
            formData.append("number", result.number);
            formData.append("id", result.id);
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.getResults(false);
                this.game_result_edit = {
                    id: "",
                    number: "",
                    prize_id: "",
                    game_setting_id: "",
                };
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });

                return;
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                });
                return;
            }
        },
        async approvedLotteryNumber() {
            let url = "/api/approve_lottery_winning_number";
            let formData = new FormData();
            formData.append("id", this.approved_result.id);
            formData.append("is_approved", 1);
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.getResults(false);
                this.modalClose("closeModalApprove");
                this.approved_result = {
                    id: "",
                };
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });

                return;
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                });
                return;
            }
        },
        modalClose(id) {
            const button = document.getElementById(id);
            if (button) {
                button.click();
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getResults(true);
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY HH:mm A");
            }
        },
    },

    mounted() {
        this.getResults(true);
        this.getDraws();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
