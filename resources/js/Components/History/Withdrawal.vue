<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <VueDatePicker
                v-model="from_date"
                :enable-time-picker="false"
                auto-apply
                class="mr-3"
                format="dd/MM/yyyy"
                @update:model-value="getWithdrawal(true)"
            ></VueDatePicker>
            <VueDatePicker
                v-model="to_date"
                :enable-time-picker="false"
                auto-apply
                class="mr-3"
                format="dd/MM/yyyy"
                @update:model-value="getWithdrawal(true)"
            ></VueDatePicker>
            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="">
                    <SelectionPaginationCount
                        :handleChange="
                            (value) => ((per_page = value), getWithdrawal(true))
                        "
                        :initialValue="per_page"
                    />
                    <div class="table-container">
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No</th>
                                    <th scope="col" class="">Name</th>
                                    <th scope="col" class="">Phone Number</th>
                                    <th scope="col" class="">Payment</th>
                                    <th scope="col" class="">Amount</th>
                                    <th scope="col" class="">Transaction</th>
                                    <th scope="col" class="">Status</th>
                                    <th scope="col" class="">Created At</th>
                                    <th scope="col" class="">Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(withdrawal, index) in withdrawals"
                                    :key="index"
                                    class=""
                                >
                                    <td class="whitespace-nowrap font-medium">
                                        {{
                                            ++index +
                                            (currentPage - 1) * per_page
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ withdrawal.customer.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ withdrawal.customer.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ withdrawal.payment_provider }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            withdrawal.amount?.toLocaleString()
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ withdrawal.payment_transaction_id }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <!-- {{ withdrawal.status }} -->
                                        <p
                                            v-if="
                                                withdrawal.status == 'pending'
                                            "
                                            class="!bg-orange-500 approved-text-box"
                                        >
                                            {{ withdrawal.status }}
                                        </p>
                                        <p
                                            v-if="
                                                withdrawal.status == 'confirmed'
                                            "
                                            class="approved-text-box"
                                        >
                                            {{ withdrawal.status }}
                                        </p>
                                        <p
                                            v-if="
                                                withdrawal.status == 'rejected'
                                            "
                                            class="!bg-red-500 rejected-text-box"
                                        >
                                            {{ withdrawal.status }}
                                        </p>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ formatDate(withdrawal.created_at) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            formatDate(
                                                withdrawal.transaction_updated_date
                                            )
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="contents" v-if="getTotalCount > per_page">
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
                                    getWithdrawal(false);
                                "
                            />
                        </div>
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
import moment from "moment";
import SearchBox from "../Common/SearchBox.vue";
import SelectionPaginationCount from "../Common/SelectionPaginationCount.vue";

export default {
    components: {
        WebPagination,
        SearchBox,
        SelectionPaginationCount,
    },
    data() {
        return {
            withdrawals: [],
            search_input: "",
            from_date: moment(),
            to_date: moment(),
            per_page: 50,
            transactionNotificationHandler: null,
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
        async getWithdrawal(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/withdrawal_transaction_history?page=${
                this.currentPage
            }&search_input=${this.search_input}&from_date=${
                this.fromDate
            }&to_date=${this.toDate}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.withdrawals = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY h:m A");
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getWithdrawal(true);
        },
    },

    mounted() {
        this.getWithdrawal(true);
        initTWE({ Modal, Ripple, Dropdown });

        this.transactionNotificationHandler = (event) => {
            const type = event?.detail?.type;
            if (type === "cash_withdrawl_transaction") {
                this.getWithdrawal(true);
            }
        };
        window.addEventListener(
            "transaction-notification",
            this.transactionNotificationHandler
        );
    },

    beforeUnmount() {
        if (this.transactionNotificationHandler) {
            window.removeEventListener(
                "transaction-notification",
                this.transactionNotificationHandler
            );
        }
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
