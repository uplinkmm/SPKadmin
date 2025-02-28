<template>
    <div class="frame-container min-h-[100vh] bg-white">
        <div class="flex justify-between px-4 mb-4">
            <div class="px-4 rounded-md">
                <p class="font-semibold font-inter text-black mb-3">
                    Agents transcations
                </p>
            </div>
            <!-- <SearchBox :search-handler="searchHandler" /> -->
        </div>

        <div class="flex px-4 pt-4 pb-12 rounded-md">
            <div class="w-60 mr-8">
                <VueDatePicker
                    v-model="from_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    @update:model-value="getTransactions"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>
            <div class="w-60">
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    @update:model-value="getTransactions"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>

            <div class="pl-6" v-if="getUser.login_type == 'admin'">
                <select
                    name=""
                    id=""
                    v-model="agent_id"
                    @change="getTransactions()"
                    class="border border-gray-600 text-sm font-inter rounded-lg bg-white min-w-[8rem] px-2 h-9 mb-4"
                >
                    <option value="">All</option>
                    <option :value="agent.id" v-for="agent in agents">
                        {{ agent.name }}
                    </option>
                </select>
            </div>

            <div class="ml-20" v-if="getUser.login_type == 'agent'">
                <div class="flex items-center gap-4">
                    <button
                        type="button"
                        class="inline-block rounded bg-[#303030] px-6 pb-2 pt-2.5 text-xs uppercase leading-normal text-white hover:shadow-primary-2 focus:outline-none focus:ring-0"
                        data-twe-toggle="modal"
                        data-twe-target="#handleModal"
                        data-twe-ripple-init
                        data-twe-ripple-color="light"
                    >
                        Withdrawal
                    </button>
                    <p class="text-sm font-inter text-black">
                        Current Balance: {{ agent_current_balance }} MMK
                    </p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <div class="overflow-hidden">
                <table
                    class="min-w-full text-left text-sm font-inter text-black"
                >
                    <thead
                        class="border-b border-t border-l border-neutral-200 font-medium"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-4 border-r">No.</th>
                            <th scope="col" class="px-6 py-4 border-r">
                                Agent Name
                            </th>
                            <th scope="col" class="px-6 py-4 border-r">Date</th>
                            <th scope="col" class="px-6 py-4 border-r">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-4 border-r">
                                Status
                            </th>
                            <th
                                v-if="getUser.login_type == 'admin'"
                                scope="col"
                                class="px-6 py-4 border-r"
                            >
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="transcations.length > 0"
                            v-for="(transcation, index) in transcations"
                            :key="index"
                            class="border-b border-l border-neutral-200"
                        >
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ ++index }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ transcation.agent.name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ formatDate(transcation.date) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ transcation.amount }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ transcation.status }}
                            </td>
                            <td
                                v-if="getUser.login_type == 'admin'"
                                class="whitespace-nowrap px-6 py-4 border-r"
                            >
                                <button
                                    :class="
                                        transcation.status !== 'received'
                                            ? 'opacity-50 cursor-not-allowed'
                                            : 'opacity-100 cursor-pointer'
                                    "
                                    :disabled="
                                        transcation.status !== 'received'
                                    "
                                    type="button"
                                    @click="
                                        editTransaction(
                                            transcation.id,
                                            'confirmed'
                                        )
                                    "
                                    class="px-3 py-4"
                                    data-twe-toggle="modal"
                                    data-twe-target="#approvingModal"
                                    data-twe-ripple-init
                                    data-twe-ripple-color="light"
                                >
                                    <i class="fas fa-check-circle"></i>
                                </button>

                                <button
                                    :class="
                                        transcation.status !== 'received'
                                            ? 'opacity-50 cursor-not-allowed'
                                            : 'opacity-100 cursor-pointer'
                                    "
                                    :disabled="
                                        transcation.status !== 'received'
                                    "
                                    type="button"
                                    @click="
                                        editTransaction(
                                            transcation.id,
                                            'rejected'
                                        )
                                    "
                                    class="px-3 py-4 text-red-600"
                                    data-twe-toggle="modal"
                                    data-twe-target="#approvingModal"
                                    data-twe-ripple-init
                                    data-twe-ripple-color="light"
                                >
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-else>
                            <td
                                colspan="6"
                                class="whitespace-nowrap px-6 py-4 border-r"
                            >
                                No data available.
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="contents" v-if="getTotalCount > 20">
                    <webPagination
                        :total-items-count="getTotalCount"
                        :items-per-page="20"
                        :current-page="currentPage"
                        active-color="#33146c"
                        icon-color="#33146c"
                        inactive-color="#c8b5db"
                        disabled-color="#c8b5db"
                        @pageChanged="
                            setCurrentPage($event);
                            getTransactions();
                        "
                    />
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
                            {{
                                update_transaction.status == "confirmed"
                                    ? "Confirmed"
                                    : "Rejected"
                            }}
                            Transaction
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
                            Are you sure to {{ update_transaction.status }} this
                            transaction?
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
                            @click="updateTransaction()"
                            class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                            data-twe-toggle="modal"
                            data-twe-target="#approvingModal"
                        >
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!--Create Withdrawal Modal -->
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
                            Withdrawal
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
                            <label
                                for="opening"
                                class="text-sm mb-3 relative block"
                                >Amount</label
                            >
                            <input
                                type="text"
                                v-model="withdrawal.amount"
                                class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                            />
                        </div>
                        <div class="mb-6">
                            <label
                                for="closing"
                                class="text-sm mb-3 relative block"
                                >Remark</label
                            >
                            <input
                                type="text"
                                v-model="withdrawal.remark"
                                class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
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
                            @click="addWithdrawal()"
                            class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        >
                            Add
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
            transcations: [],
            search_input: "",
            agent_id: "",
            agents: [],
            from_date: null,
            to_date: null,
            update_transaction: {
                id: "",
                status: "", //confirmed,rejected
            },
            withdrawal: {
                amount: "",
                remark: "",
            },
            agent_current_balance: 0,
        };
    },
    computed: {
        ...mapGetters(["getToken", "getUser", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),

        async getTransactions() {
            if (this.from_date != null) {
                var from_date = moment(this.from_date).format("YYYY-MM-DD");
            } else {
                var from_date = "";
            }
            if (this.to_date != null) {
                var to_date = moment(this.to_date).format("YYYY-MM-DD");
            } else {
                var to_date = "";
            }
            if (this.getUser.login_type == "admin") {
                var url = `/api/agent_withdrawal_transactions?from_date=${from_date}&to_date=${to_date}&search_input=${this.search_input}&agent_id=${this.agent_id}&page=${this.currentPage}`;
            } else {
                var url = `/api/agent_withdrawal_transactions?from_date=${from_date}&to_date=${to_date}&search_input=${this.search_input}&agent_id=${this.getUser.id}&page=${this.currentPage}`;
            }
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.transcations = response.data.wallets.data;
                this.agent_current_balance = response.data.agent_wallet_balance;
                this.setTotalCount(response.data.wallets.total);
            }
        },
        async getAgents() {
            let url = `/api/agents`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.agents = response.data;
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getTransactions();
        },
        formatDate(date) {
            return moment(date).format("MMM D");
        },
        editTransaction(id, status) {
            this.update_transaction.id = id;
            this.update_transaction.status = status;
        },
        async updateTransaction() {
            let formData = new FormData();
            formData.append("id", this.update_transaction.id);
            formData.append("status", this.update_transaction.status);
            let url = "/api/update_agent_wallet_transaction_status";
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.update_transaction = {
                    id: "",
                    status: "",
                };
                this.getTransactions();
                document.getElementById("closeAddModal").click();
                this.$notify({
                    text: "Success",
                    type: "info",
                });
            } else {
                this.$notify({
                    text: response.message,
                    type: "error",
                });
                console.log(response.error);
            }
        },
        async addWithdrawal() {
            if (!this.withdrawal.amount) {
                this.$notify({
                    text: "Please enter amount!",
                    type: "error",
                });
            }
            let formData = new FormData();
            formData.append("amount", this.withdrawal.amount);
            formData.append("remark", this.withdrawal.remark);
            formData.append("agent_id", this.getUser.id);
            let url = "/api/create_agent_withdrawal_transaction";
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.getTransactions();
                document.getElementById("closeAddModal").click();
                this.$notify({
                    text: "Success! ",
                    type: "info",
                });
            } else {
                this.$notify({
                    text: response.message,
                    type: "error",
                });
                console.log(response.error);
            }
        },
    },

    mounted() {
        if (this.getUser.login_type == "admin") {
            this.getAgents();
        }
        this.getTransactions();

        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
