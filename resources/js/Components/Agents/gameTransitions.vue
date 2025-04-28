<template>
    <div class="frame-container min-h-[100vh] bg-white">
        <div class="flex justify-between px-4 mb-4">
            <div class="px-4 rounded-md">
                <p class="font-semibold font-inter text-black mb-3">
                    {{ gameType }} transactions
                </p>
            </div>
            <SearchBox :search-handler="searchHandler" />
        </div>

        <div class="flex px-4 pt-4 pb-12 rounded-md">
            <div class="w-60">
                <VueDatePicker
                    v-model="date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    @update:model-value="getTransitions"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>

            <div class="pl-6" v-if="getUser.login_type == 'admin'">
                <select
                    name=""
                    id=""
                    v-model="agent_id"
                    @change="getTransitions()"
                    class="border border-gray-600 text-sm font-inter rounded-lg bg-white min-w-[8rem] px-2 h-9 mb-4"
                >
                    <option value="">All</option>
                    <option :value="agent.id" v-for="agent in agents">
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
                            <th scope="col" class="px-6 py-4 border-r">
                                User Name
                            </th>
                            <th scope="col" class="px-6 py-4 border-r">
                                Phone Number
                            </th>
                            <th scope="col" class="px-6 py-4 border-r">
                                {{ gameType }}
                            </th>
                            <th scope="col" class="px-6 py-4 border-r">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-4 border-r">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="transitions.length > 0"
                            v-for="(transition, index) in transitions"
                            :key="index"
                            class="border-b border-l border-neutral-200"
                        >
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ ++index }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ transition.name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ transition.phone_number }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                <span
                                    v-for="(num, index) in transition.betting_numbers"
                                    :key="index"
                                >
                                    {{ num.number }},
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ transition.total_amount }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 border-r">
                                {{ transition.commission_percentage }}
                            </td>
                            
                        </tr>
                        <tr v-else>
                            <td
                                colspan="6"
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
                            getTransitions();
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
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import WebPagination from "../Common/webPagination.vue";
import SearchBox from "../Common/SearchBox.vue";
import moment from "moment";

export default {
    components: {
        WebPagination,
        SearchBox,
    },
    props: {
        gameType: {},
    },
    data() {
        return {
            transitions: [],
            search_input: "",
            agent_id: "",
            agents: [],
            date: moment(),
        };
    },
    computed: {
        ...mapGetters(["getToken", "getUser", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),

        async getTransitions() {
            if (this.date != null) {
                var date = moment(this.date).format("YYYY-MM-DD");
            } else {
                var date = "";
            }
            if (this.gameType == "2D") {
                var game_id = 1;
            } else {
                var game_id = 2;
            }
            if (this.getUser.login_type == "admin") {
                var url = `/api/transaction_list_by_agent?game_id=${game_id}&date=${date}&search_input=${this.search_input}&agent_id=${this.agent_id}&page=${this.currentPage}`;
            } else {
                var url = `/api/transaction_list_by_agent?game_id=${game_id}&date=${date}&search_input=${this.search_input}&agent_id=${this.getUser.id}&page=${this.currentPage}`;
            }
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.transitions = response.data.data;
                console.log(response.data.data);
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
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getTransitions();
        },
    },

    mounted() {
        if (this.getUser.login_type == "admin") {
            this.getAgents();
        }
        this.getTransitions();

        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
