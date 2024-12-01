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
                    @update:model-value="getTransactionList(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>

                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getTransactionList(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>
            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="flex items-center mb-4">
                    <label for="itemsPerPage" class="mr-2 text-gray-700"
                        >Show</label
                    >
                    <select
                        id="itemsPerPage"
                        @change="getTransactionList(true)"
                        v-model="per_page"
                        class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500"
                    >
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="50000">All</option>
                    </select>
                </div>
                <div class="">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Transaction Id</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Handled By</th>
                                    <th scope="col">Transaction Time</th>
                                    <th scope="col">Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(
                                        transaction, index
                                    ) in transactionList"
                                    :key="index"
                                >
                                    <td class="whitespace-nowrap">
                                        {{
                                            per_page * (currentPage - 1) +
                                            ++index
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ transaction.customer.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ transaction.customer.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div
                                            :style="{
                                                backgroundColor:
                                                    transaction.color_code,
                                            }"
                                            class="color-text-box"
                                            :class="
                                                transaction.payment_provider
                                                    ? 'text-white'
                                                    : 'text-black'
                                            "
                                        >
                                            {{ transaction.payment_provider }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            transaction.amount.toLocaleString()
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ transaction.payment_transaction_id }}
                                    </td>
                                    <td class="whitespace-nowrap text-center">
                                        <div
                                            v-if="
                                                transaction.status == 'pending'
                                            "
                                            class="flex gap-x-2"
                                        >
                                            <button
                                                type="button"
                                                @click="
                                                    confirmApproveTransactionBtnClicked(
                                                        transaction.id
                                                    )
                                                "
                                                class="approve-btn flex"
                                            >
                                                Approve

                                                <svg
                                                    v-if="
                                                        transaction_id ==
                                                        transaction.id
                                                    "
                                                    class="animate-spin h-5 w-5 text-white ml-1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <circle
                                                        class="opacity-25"
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                        stroke="currentColor"
                                                        stroke-width="4"
                                                    ></circle>
                                                    <path
                                                        class="opacity-75"
                                                        fill="currentColor"
                                                        d="M4 12a8 8 0 018-8v8H4z"
                                                    ></path>
                                                </svg>
                                            </button>
                                            <button
                                                type="button"
                                                @click="
                                                    confirmRejectTransactionBtnClicked(
                                                        transaction.id
                                                    )
                                                "
                                                class="reject-btn"
                                            >
                                                Reject
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button
                                                v-if="
                                                    transaction.status ==
                                                    'rejected'
                                                "
                                                type="button"
                                                class="reject-btn px-1 py-1"
                                            >
                                                {{ transaction.status }}
                                            </button>
                                            <button
                                                v-if="
                                                    transaction.status ==
                                                    'confirmed'
                                                "
                                                type="button"
                                                class="approve-btn px-1 py-1"
                                            >
                                                Approved
                                            </button>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div v-if="transaction.confirmed_by">
                                            {{ transaction.confirmed_by.name }}
                                        </div>
                                        <div v-if="transaction.rejected_by">
                                            {{ transaction.rejected_by.name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ formatDate(transaction.created_at) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ formatDate(transaction.updated_at) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">
                                        Pending Deposit:
                                    </td>
                                    <td colspan="5">
                                        {{
                                            total_pending_deposit.toLocaleString()
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">
                                        Complete Deposit:
                                    </td>
                                    <td colspan="5">
                                        {{
                                            total_completed_deposit.toLocaleString()
                                        }}
                                    </td>
                                </tr>
                                <tr
                                    v-for="(acc, index) in account_list"
                                    :key="index"
                                >
                                    <td colspan="5" class="text-right">
                                        {{ acc.name }}:
                                    </td>
                                    <td colspan="5">
                                        {{ acc.total_amount.toLocaleString() }}
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
                                getTransactionList(false);
                            "
                        />
                    </div>
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
                        Confirm Transaction
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

                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div>Are you sure to approve this transaction?</div>
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
                        @click="confirmApproveTransactionBtnClicked()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        data-twe-toggle="modal"
                        data-twe-target="#approvingModal"
                    >
                        Approve
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="rejectingModal"
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
                        Reject Transaction
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

                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div>Are you sure to reject this transaction?</div>
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
                        @click="confirmRejectTransactionBtnClicked()"
                        class="rounded bg-red-600 px-8 pb-2 pt-2.5 text-xs text-white hover:bg-red-500 focus:outline-none focus:ring-0 active:bg-red-600"
                        data-twe-toggle="modal"
                        data-twe-target="#rejectingModal"
                    >
                        Reject
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="approvingModal2"
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
                        Confirm Transaction
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

                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div>
                        <label
                            for="payment-tr-id"
                            class="text-sm mb-3 relative block"
                            >Transaction Id</label
                        >
                        <input
                            type="text"
                            id="payment-tr-id"
                            placeholder="Transaction Id"
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
                        type="button"
                        @click="confirmApproveTransactionBtnClicked()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        data-twe-toggle="modal"
                        data-twe-target="#approvingModal"
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
import { convertToFriendlyDateTime } from "../../utilities/datetime-helpers";
import Multiselect from "vue-multiselect";
import moment from "moment";
import WebPagination from "../Common/webPagination.vue";
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        Multiselect,
        WebPagination,
        SearchBox,
    },
    data() {
        return {
            transactionList: [],
            handlingTransaction: null,
            from_date: moment(),
            to_date: moment(),
            per_page: 50,
            search_input: "",
            account_list: "",
            total_completed_deposit: "",
            total_pending_deposit: "",
            transaction_id: "",
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),

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

        alertValidationMessage(field) {
            this.$notify({
                title: `Input validation`,
                text: `You forgot to provide ${field}, please try again`,
                type: "warn",
            });
        },

        async getTransactionList(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/topup_transactions?from_date=${
                this.fromDate
            }&to_date=${this.toDate}&page=${this.currentPage}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }&search_input=${this.search_input}`;
            let response = await getApiData({ url: url, token: this.getToken });
            if (response.data) {
                console.log(response.data);
                this.transactionList = response.data.transactions.data;
                this.account_list = response.data.account_list;
                this.total_completed_deposit =
                    response.data.total_completed_deposit;
                this.total_pending_deposit =
                    response.data.total_pending_deposit;

                this.transactionList.forEach((transaction) => {
                    transaction.created_at = convertToFriendlyDateTime(
                        transaction.created_at
                    );
                    if (transaction.confirmed_at) {
                        transaction.confirmed_at = convertToFriendlyDateTime(
                            transaction.confirmed_at
                        );
                    }
                    if (transaction.rejected_at) {
                        transaction.rejected_at = convertToFriendlyDateTime(
                            transaction.rejected_at
                        );
                    }

                    transaction.amount = parseFloat(transaction.amount);
                });
                this.setTotalCount(response.data.transactions.total);
            }
        },

        // transactionHandleBtnClicked(id){
        //     let index = this.transactionList.findIndex(transaction => transaction.id == id);
        //     if(index != -1){
        //         this.handlingTransaction = this.transactionList[index];
        //     }
        //     console.log('handling = ' + this.handlingTransaction.id)
        //     console.log('id = ' + id)
        // },

        async confirmApproveTransactionBtnClicked(id) {
            this.transaction_id = id;
            let formData = new FormData();
            formData.append("handle_type", "confirm");
            let url = `/api/topup_transactions/${id}/confirm_reject`;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.$notify({
                    text: response.message,
                    type: "info",
                });
                this.getTransactionList(false);
                this.transaction_id = "";

            } else {
                this.$notify({
                    text: response.message,
                    type: "error",
                });
            }
            this.handlingTransaction = null;
        },

        async confirmRejectTransactionBtnClicked(id) {
            let index = this.transactionList.findIndex(
                (transaction) => transaction.id == id
            );
            if (index != -1) {
                this.handlingTransaction = this.transactionList[index];
            } //remove if modal
            let formData = new FormData();
            formData.append("handle_type", "reject");
            let url = `/api/topup_transactions/${this.handlingTransaction.id}/confirm_reject`;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.$notify({
                    text: response.message,
                    type: "info",
                });
                this.getTransactionList(false);
            } else {
                this.$notify({
                    text: response.message,
                    type: "error",
                });
            }
            this.handlingTransaction = null;
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY h:m A");
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getTransactionList(true);
        },
    },

    created() {
        this.getTransactionList(false);
    },

    mounted() {
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
