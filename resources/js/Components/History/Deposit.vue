<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between mb-4">
            <div class="flex gap-x-4">
                <VueDatePicker
                    v-model="from_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    format="dd/MM/yyyy"
                    @update:model-value="getDeposit(true)"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    format="dd/MM/yyyy"
                    @update:model-value="getDeposit(true)"
                ></VueDatePicker>
            </div>
            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="">
                    <SelectionPaginationCount
                        :handleChange="(value) => (per_page=value, getDeposit(true))"
                        :initialValue="per_page"
                    />
                    <div class="table-container">
                        <table
                            class=""
                        >
                            <thead
                                class=""
                            >
                                <tr>
                                    <th scope="col" class="">No</th>
                                    <th scope="col" class="">Name</th>
                                    <th scope="col" class="">
                                        Phone Number
                                    </th>
                                    <th scope="col" class="">
                                        Payment
                                    </th>
                                    <th scope="col" class="">
                                        Amount
                                    </th>
                                    <th scope="col" class="">
                                        Transaction
                                    </th>
                                    <th scope="col" class="">
                                        Status
                                    </th>
                                    <th scope="col" class="">
                                        Created At
                                    </th>
                                    <th scope="col" class="">
                                        Updated At
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(deposit, index) in deposits"
                                    :key="index"
                                    class=""
                                >
                                    <td
                                        class="whitespace-nowrap  font-medium"
                                    >
                                        {{ ++index + (currentPage - 1) * per_page }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ deposit.customer.name }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ deposit.customer.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ deposit.payment_provider }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ deposit.amount?.toLocaleString() }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ deposit.payment_transaction_id }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        <!-- {{ deposit.status }} -->
                                        <p v-if="deposit.status == 'pending'" class=" !bg-orange-500 approved-text-box">{{ deposit.status }}</p>
                                        <p v-if="deposit.status == 'confirmed'" class="approved-text-box">{{ deposit.status }}</p>
                                        <p v-if="deposit.status == 'rejected'" class="!bg-red-500 rejected-text-box">{{ deposit.status }}</p>
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{ formatDate(deposit.created_at) }}
                                    </td>
                                    <td class="whitespace-nowrap ">
                                        {{
                                            formatDate(
                                                deposit.transaction_updated_date
                                            )
                                        }}
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
                                    getDeposit(false);
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
            deposits: [],
            search_input: "",
            from_date:moment(),
            to_date:moment(),
            per_page: 50,
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
        async getDeposit(per_page) {
            if(per_page){
                this.setCurrentPage(1);
            }
            let url = `/api/topup_transaction_history?page=${this.currentPage}&search_input=${this.search_input}&from_date=${this.fromDate}&to_date=${this.toDate}${this.per_page ? `&per_page=${this.per_page}` : ""}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                console.log(response.data);
                this.deposits = response.data.data;
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
            this.getDeposit(true);
        },
    },
    created() {},

    mounted() {
        this.getDeposit(true);
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
