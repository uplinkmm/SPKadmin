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
            <div class="">
                <SelectionPaginationCount
                    :handleChange="
                        (value) => (
                            (per_page = value), getTransactionList(true)
                        )
                    "
                    :initialValue="per_page"
                />
                <div class="">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Account Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Transfer Phone Number</th>
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
                                    <td class="whitespace-nowrap font-medium">
                                        {{
                                            per_page * (currentPage - 1) +
                                            ++index
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <a
                                            class="underline text-blue-500 font-semibold"
                                            :href="`balance?user_id=${transaction.customer.id}`"
                                        >
                                            {{ transaction.customer.name }}</a
                                        >
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ transaction.customer.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ transaction.account_name }}
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
                                        {{ transaction.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            transaction.amount.toLocaleString()
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ transaction.payment_transaction_id }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div
                                            v-if="
                                                transaction.status == 'pending'
                                            "
                                            class="flex gap-x-2"
                                        >
                                            <button
                                                type="button"
                                                :disabled="loading"
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
                                                        confirm_transaction_id ==
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
                                                :disabled="loading"
                                                @click="
                                                    confirmRejectTransactionBtnClicked(
                                                        transaction.id
                                                    )
                                                "
                                                class="reject-btn"
                                            >
                                                Reject
                                                <svg
                                                    v-if="
                                                        reject_transaction_id ==
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
                                        </div>
                                        <div v-else class="text-center">
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

                                    <!-- <td class="whitespace-nowrap">
                                        <button :class="transaction.status !== 'pending' ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" :disabled="transaction.status !== 'pending'"
                                            type="button" @click="transactionHandleBtnClicked(transaction.id)"
                                            class="px-3 py-4"
                                            data-twe-toggle="modal"
                                            data-twe-target="#approvingModal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light">
                                            <i class="fas fa-check-circle"></i>
                                        </button>

                                        <button :class="transaction.status !== 'pending' ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" :disabled="transaction.status !== 'pending'"
                                            type="button" @click="transactionHandleBtnClicked(transaction.id)"
                                            class="px-3 py-4 text-red-600 "
                                            data-twe-toggle="modal"
                                            data-twe-target="#rejectingModal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </td> -->
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">
                                        Pending Deposit:
                                    </td>
                                    <td colspan="5">
                                        {{
                                            total_pending_withdrawal?.toLocaleString()
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">
                                        Complete Deposit:
                                    </td>
                                    <td colspan="5">
                                        {{ total_completed_withdrawal }}
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
import SelectionPaginationCount from "../Common/SelectionPaginationCount.vue";

export default {
    components: {
        Multiselect,
        WebPagination,
        SearchBox,
        SelectionPaginationCount,
    },
    data() {
        return {
            transactionList: [],

            handlingTransaction: null,
            handleOptions: ["confirm", "reject"],
            handleOption: null,
            // paymentTrId: null,
            from_date: moment(),
            to_date: moment(),
            per_page: 50,
            search_input: "",
            account_list: "",
            total_completed_withdrawal: "",
            total_pending_withdrawal: "",
            confirm_transaction_id: "",
            reject_transaction_id: "",
            loading: false,
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
            let url = `/api/cash_withdrawl_transactions?from_date=${
                this.fromDate
            }&to_date=${this.toDate}&page=${this.currentPage}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }&search_input=${this.search_input}`;
            let response = await getApiData({ url: url, token: this.getToken });
            if (response.data) {
                this.transactionList = response.data.transactions.data;
                this.account_list = response.data.account_list;
                this.total_completed_withdrawal =
                    response.data.total_completed_withdrawal;
                this.total_pending_withdrawal =
                    response.data.total_pending_withdrawal;

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
        // },

        async confirmApproveTransactionBtnClicked(id) {
            this.confirm_transaction_id = id;
            let formData = new FormData();
            formData.append("handle_type", "confirm");
            let url = `/api/cash_withdrawl_transactions/${id}/confirm_reject`;
            this.loading = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            this.loading = false;
            this.confirm_transaction_id = "";
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
            // this.paymentTrId = null;
        },

        async confirmRejectTransactionBtnClicked(id) {
            this.reject_transaction_id = id;
            let formData = new FormData();
            formData.append("handle_type", "reject");
            let url = `/api/cash_withdrawl_transactions/${id}/confirm_reject`;
            this.loading = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            this.loading = false;
            this.reject_transaction_id = "";
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
