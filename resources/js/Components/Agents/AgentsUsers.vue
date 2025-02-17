<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <SearchBox :search-handler="searchHandler" />
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div>
                <p class="font-semibold font-inter text-black mb-3">
                    Agent's Users
                </p>
            </div>
            <div v-if="getUser.login_type == 'admin'">
                <select
                    name=""
                    id=""
                    v-model="agent_id"
                    @change="getAgentUsers()"
                    class="border border-gray-600 text-sm font-inter rounded-lg bg-white min-w-[8rem] px-2 h-9 mb-4"
                >
                    <option value="">All</option>
                    <option :value="agent.id" v-for="agent in agents">
                        {{ agent.name }}
                    </option>
                </select>
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
                                <th scope="col" class="px-6 py-4 border-r">
                                    No.
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    User Name
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Phone Number
                                </th>
                                <div
                                    class="contents"
                                    v-for="(game, index) in games"
                                    :key="index"
                                >
                                    <th scope="col" class="px-6 py-4 border-r">
                                        {{ game.name }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 border-r">
                                        {{ game.name }} %
                                    </th>
                                </div>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v
                                v-for="(user, index) in agent_users"
                                :key="index"
                                class="border-b border-l border-neutral-200"
                            >
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ ++index}}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ user.name }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ user.phone_number }}
                                </td>
                                <div
                                    class="contents"
                                    v-for="(game, index) in user.games"
                                    :key="index"
                                >
                                    <td
                                        class="whitespace-nowrap px-6 py-4 border-r"
                                    >
                                        {{ game.total_bet_amount }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 border-r"
                                    >
                                        {{ game.commission_percentage }}
                                    </td>
                                </div>
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
                                getAgentUsers();
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
import WebPagination from "../Common/webPagination.vue";
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        WebPagination,
        SearchBox,
    },
    data() {
        return {
            agent_users: [],
            search_input: "",
            agent_id: "",
            agents: [],
            games: [],
        };
    },
    computed: {
        ...mapGetters(["getToken", "getUser", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),

        async getAgentUsers() {
            if (this.getUser.login_type == "admin") {
                var url = `/api/customer_list_by_agent?search_input=${this.search_input}&agent_id=${this.agent_id}&page=${this.currentPage}`;
            } else {
                var url = `/api/customer_list_by_agent?search_input=${this.search_input}&agent_id=${this.getUser.id}&page=${this.currentPage}`;
            }
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.agent_users = response.data.data;
                this.games = response.data.data[0].games;
                this.setTotalCount(response.data.total);
                console.log(response.data.data[0].games);
            }
        },
        async getAgents() {
            let url = `/api/agents`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                console.log(response.data);
                this.agents = response.data;
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getAgentUsers();
        },
    },

    mounted() {
        if (this.getUser.login_type == "admin") {
            this.getAgents();
        }
        this.getAgentUsers();

        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
