<template>
    <div class="frame-container min-h-[100vh] bg-white">
        <div class="flex justify-between px-4 mb-4">
            <div class="px-4 rounded-md">
                <p class="font-semibold font-inter text-black mb-3">
                    Agents wallets
                </p>
            </div>
            <!-- <SearchBox :search-handler="searchHandler" /> -->
        </div>

        <div class="flex px-4 pt-4 pb-12 rounded-md">
            <div class="flex gap-x-4">
                <VueDatePicker
                    v-model="from_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    format="dd/MM/yyyy"
                    @update:model-value="getWallets"
                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    format="dd/MM/yyyy"
                    @update:model-value="getWallets"
                ></VueDatePicker>
            </div>

            <div class="pl-6" v-if="getUser.login_type == 'admin'">
                <select
                    name=""
                    id=""
                    v-model="agent_id"
                    @change="getWallets()"
                    class="border border-gray-600 text-sm font-inter rounded-lg bg-white min-w-[8rem] px-2 h-9 mb-4"
                >
                    <option value="">All</option>
                    <option
                        :value="agent.id"
                        v-for="agent in agents"
                        :key="agent.id"
                    >
                        {{ agent.name }}
                    </option>
                </select>
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
                            <th scope="col" class="px-6 py-4 border-r">Date</th>
                            <th scope="col" class="px-6 py-4 border-r">
                                Action
                            </th>
                            <th scope="col" class="px-6 py-4 border-r">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-4 border-r">
                                Balance
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(commission, index) in wallets"
                            :key="index"
                            class="border-b border-l border-neutral-200"
                        >
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ ++index }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ formatDate(commission.date) }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ commission.action }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ commission.amount?.toLocaleString() }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ commission.balance?.toLocaleString() }}
                            </td>
                        </tr>
                        <tr v-if="wallets.length == 0">
                            <td
                                colspan="100%"
                                class="whitespace-nowrap px-6 py-4 border-r"
                            >
                                No data available!
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
                            getWallets();
                        "
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters, mapMutations } from "vuex";
import { getApiData } from "../../utilities/ajax-helpers";
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
            wallets: [],
            search_input: "",
            agent_id: "",
            agents: [],
            from_date: null,
            to_date: null,
        };
    },
    computed: {
        ...mapGetters(["getToken", "getUser", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),

        async getWallets() {
            let from_date = this.from_date
                ? moment(this.from_date).format("YYYY-MM-DD")
                : "";
            let to_date = this.to_date
                ? moment(this.to_date).format("YYYY-MM-DD")
                : "";
            if (this.getUser.login_type == "admin") {
                var url = `/api/agent_wallets?from_date=${from_date}&to_date=${to_date}&search_input=${this.search_input}&agent_id=${this.agent_id}&page=${this.currentPage}`;
            } else {
                var url = `/api/agent_wallets?from_date=${from_date}&to_date=${to_date}&search_input=${this.search_input}&agent_id=${this.getUser.id}&page=${this.currentPage}`;
            }
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.wallets = response.data.data;
                this.setTotalCount(response.data.total);
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
                this.agent_id = response.data[0].id;
                this.getWallets();
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getWallets();
        },
        formatDate(date) {
            return moment(date).format("MMM D");
        },
    },

    mounted() {
        if (this.getUser.login_type == "admin") {
            this.getAgents();
        } else {
            this.getWallets();
        }

        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
