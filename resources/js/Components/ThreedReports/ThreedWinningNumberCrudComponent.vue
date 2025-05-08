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
                    @update:model-value="getNumberList(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getNumberList(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>
            <div class="flex items-center space-x-2">
                <button
                    type="button"
                    @click="btnClickAddModal()"
                    class="inline-block rounded bg-[#303030] px-2  text-xs font-medium uppercase leading-normal text-white hover:shadow-primary-2 focus:outline-none focus:ring-0"
                    data-twe-toggle="modal"
                    data-twe-target="#handleModal"
                    data-twe-ripple-init
                    data-twe-ripple-color="light"
                >
                    Add 3D Result
                </button>
                <div class="flex  items-center ml-2">
                    <label for="setting" class="mr-2 text-gray-700">Games</label>
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
                    <div class="flex items-center mb-4">
                        <label for="itemsPerPage" class="mr-2 text-gray-700"
                            >Show</label
                        >
                        <select
                            id="itemsPerPage"
                            @change="getNumberList(true)"
                            v-model="per_page"
                            class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500"
                        >
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="50000">All</option>
                        </select>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">No</th>
                                    <th scope="col">3D</th>
                                    <th scope="col">Twist</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Status</th>
                                    <!-- <th scope="col">Time</th> -->
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(num, index) in numberList"
                                    class="text-center"
                                >
                                    <td class="whitespace-nowrap font-medium">
                                        {{
                                            per_page * (currentPage - 1) +
                                            ++index
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ num.number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span
                                            v-for="twist in num.twist_win_numbers"
                                            class="after-coma"
                                        >
                                            &nbsp;{{ twist.number }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- <div
                                            v-if="getDay(num.game_setting.lottery_date_time) == 1"
                                            class="color-text-box text-white bg-gray-800"
                                        >
                                            {{ getDay(num.game_setting.lottery_date_time) }} ရက်
                                        </div>
                                        <div
                                            v-else
                                            class="color-text-box text-white bg-[#322793]"
                                        >
                                            {{ getDay(num.game_setting.lottery_date_time) }} ရက်
                                        </div> -->
                                        <button
                                            :class="
                                                getDay(
                                                    num.game_setting
                                                        .lottery_date_time
                                                ) == 1
                                                    ? 'bg-[#f3b01a]'
                                                    : 'bg-[#2cb12c]'
                                            "
                                            class="rounded px-4 pb-1 pt-1 text-xs text-white w-fit mx-auto"
                                        >
                                            {{
                                                getDay(
                                                    num.game_setting
                                                        .lottery_date_time
                                                )
                                            }}
                                            ရက်
                                        </button>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            formatDate(
                                                num.game_setting
                                                    .lottery_date_time
                                            )
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap text-center">
                                        <div
                                            v-if="num.is_approved == 0"
                                            class=""
                                        >
                                            <button
                                                type="button"
                                                class="approve-btn bg-yellow-500"
                                                @click="getApprovement(num.id)"
                                                data-twe-toggle="modal"
                                                data-twe-target="#approvingModal"
                                                data-twe-ripple-init
                                                data-twe-ripple-color="light"
                                            >
                                                Pending
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button
                                                type="button"
                                                class="approve-btn px-1 py-1"
                                            >
                                                Approved
                                            </button>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4">
                                        <button
                                            @click="btnClickEditNumber(num)"
                                            :disabled="num.is_approved == 1"
                                            :class="
                                                num.is_approved == 1
                                                    ? 'cursor-not-allowed'
                                                    : 'opacity-100 cursor-pointer'
                                            "
                                            class="mr-3 px-2 py-4"
                                            type="button"
                                            data-twe-toggle="modal"
                                            data-twe-target="#editModal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                        >
                                            <i class="fal fa-edit"></i>
                                        </button>
                                        <!-- <button
                                            :class="        <button
                                            :class="
                                                num.is_approved == 1
                                                    ? 'opacity-50 cursor-not-allowed'
                                                    : 'opacity-100 cursor-pointer'
                                            "
                                            :disable="num.is_approved == 1"
                                            type="button"
                                            @click="getApprovement(num.id)"
                                            class="px-2 py-4"
                                            data-twe-toggle="modal"
                                            data-twe-target="#approvingModal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                        >
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                                num.is_approved == 1
                                                    ? 'opacity-50 cursor-not-allowed'
                                                    : 'opacity-100 cursor-pointer'
                                            "
                                            :disable="num.is_approved == 1"
                                            type="button"
                                            @click="getApprovement(num.id)"
                                            class="px-2 py-4"
                                            data-twe-toggle="modal"
                                            data-twe-target="#approvingModal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                        >
                                            <i class="fas fa-check-circle"></i>
                                        </button> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6" v-if="getTotalCount > per_page">
                        <webPagination
                            :total-items-count="getTotalCount"
                            :items-per-page="per_page"
                            :current-page="currentPage"
                            active-color="#ffffff"
                            icon-color="#604f4f"
                            inactive-color="#604f4f"
                            disabled-color="#c8b5db"
                            @pageChanged="
                                setCurrentPage($event);
                                getNumberList(false);
                            "
                        />
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
                        Add 3D Result
                    </h5>
                    <button
                        type="button"
                        id="closeThreedResult"
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
                        <label for="3d" class="text-sm mb-3 relative block"
                            >3d</label
                        >
                        <input
                            type="text"
                            v-model="number"
                            id="3d"
                            placeholder="3d"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                        <span class="text-xs text-red-600" v-if="error.number">
                            {{ error.number }}
                        </span>
                    </div>
                    <div class="mb-6">
                        <label for="twist" class="text-sm mb-3 relative block"
                            >Twist</label
                        >
                        <input
                            type="text"
                            v-model="twist_number"
                            id="twist"
                            placeholder="Eg.123,345,678..."
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                        <span
                            class="text-xs text-red-600"
                            v-if="error.twist_number"
                        >
                            {{ error.twist_number }}
                        </span>
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
                        type="button"
                        @click="btnClickAddNumber()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="editModal"
        tabindex="-1"
        aria-labelledby="editModalLabel"
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
                        id="editModalLabel"
                    >
                        Edit 3D Result
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
                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6">
                        <label for="3d" class="text-sm mb-3 relative block"
                            >3d</label
                        >
                        <input
                            type="text"
                            v-model="editNumber"
                            id="3d"
                            placeholder="3d"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                        <!-- <span class="text-xs text-red-600" v-if="numberIsValid">
                            Number is required!
                        </span> -->
                    </div>
                    <div class="mb-6">
                        <label for="twist" class="text-sm mb-3 relative block"
                            >Twist</label
                        >
                        <input
                            type="text"
                            v-model="editTwistNumberString"
                            id="twist"
                            placeholder="Eg.123,345,678..."
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                        <!-- <span class="text-xs text-red-600" v-if="twistNumberIsValid">
                            Twist Numbers is required!
                        </span> -->
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
                        type="button"
                        @click="confirmEditNumber()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="approvingModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div
            data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-[40vh] min-[576px]:max-w-[500px]"
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
                        Confirm Result
                    </h5>
                    <button
                        type="button"
                        id="close"
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
                        type="button"
                        @click="confirmApprovement()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        Approve
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters, mapMutations } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import moment from "moment";
import SearchBox from "../Common/SearchBox.vue";
import WebPagination from "../Common/webPagination.vue";

export default {
    components: {
        SearchBox,
        WebPagination,
    },
    data() {
        return {
            numberList: null,
            lottery_time: null,
            number: null,
            twist_number: null,
            error: {
                number: "",
                twist_number: ""
            },
            approveId: null,
            editWinningNumber: null,
            editNumber: null,
            edit_twist_number: [],
            editTwistNumberString: null,
            originalArray: ["1", "2", "3"],
            game_setting_id: "",
            game_settings: [],
            search_input: "",
            from_date: "",
            to_date: "",
            per_page: 50,
        };
    },

    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
        convertedString() {
            return this.originalArray.join(",");
        },
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
        ...mapMutations(["setTotalCount", "setCurrentPage"]),

        async getNumberList(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/3d/betting_wins?game_setting_id=${
                this.game_setting_id
            }&from_date=${this.fromDate}&to_date=${this.toDate}&page=${
                this.currentPage
            }${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }&search_input=${this.search_input}`;

            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.success) {
                this.numberList = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        btnClickAddModal() {
            this.error = {
                number: "",
                twist_number: ""
            };
        },
        btnClickAddNumber() {
            this.error = {
                number: "",
                twist_number: ""
            };

            if (!this.number) {
                this.error.number = "Please enter 3D number";
            }
            if (!this.twist_number) {
                this.error.twist_number = "Please enter twist number";
            }

            if (!this.error.number && !this.error.twist_number) {
                this.addWinningNumber();
            }
        },
        async addWinningNumber() {
            let formData = new FormData();
            formData.append("number", this.number);
            formData.append("twist_numbers", this.twist_number);

            let url = "/api/3d/betting_wins";
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.getNumberList();
                console.log("number added");
                document.getElementById("closeThreedResult").click();
                this.number = null;
                this.twist_number = null;
            } else {
                this.$notify({
                    text: response.message,
                    type: "error",
                });
                this.error.number = response.message;
                    
            }
        },
        btnClickEditNumber(num) {
            this.edit_twist_number = [];
            this.editWinningNumber = num;
            this.editNumber = num.number;
            this.editWinningNumber.twist_win_numbers.forEach((numbers) => {
                this.edit_twist_number.push(numbers.number);
            });
            this.editTwistNumberString = this.edit_twist_number.join(",");
        },
        async confirmEditNumber() {
            let formData = new FormData();
            formData.append("number", this.editNumber);
            formData.append("twist_numbers", this.editTwistNumberString);
            let url = "/api/3d/betting_wins/" + this.editWinningNumber.id;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.getNumberList();
                document.getElementById("closeEditModal").click();
            } else {
                console.log(response.error);
            }
        },
        getApprovement(threedId) {
            this.approveId = threedId;
        },
        async confirmApprovement() {
            let url = "/api/3d/betting_wins/" + this.approveId + "/approve";
            let response = await postApiData({
                url: url,
                token: this.getToken,
            });
            if (response.success) {
                console.log("approved");
                document.getElementById("close").click();
                window.location.reload();
            } else {
                this.$notify({
                    text: response.message,
                    type: "error",
                });
            }
        },
        getCurrentDate() {
            this.currentDate = getCurrentDate();
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY");
            }
        },
        getDay(date) {
            if (date) {
                return moment(date).format("DD");
            }
        },
        async getGameSetting() {
            let url = `/api/3d/game_setting`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
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
