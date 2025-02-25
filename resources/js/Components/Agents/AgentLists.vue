<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <button
                type="button"
                data-twe-toggle="modal"
                data-twe-target="#game_modal"
                data-twe-ripple-init
                data-twe-ripple-color="light"
                class="rounded bg-[#303030] px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:shadow-primary-2 focus:outline-none focus:ring-0"
                @click="resetAgentForm"
            >
                Create
            </button>
            <SearchBox :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <div class="">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Agent Name</th>
                                    <th scope="col">Agent Code</th>
                                    <th
                                        v-for="(game, index) in agents[0]
                                            ?.agent_commissions"
                                        :key="index"
                                        scope="col"
                                    >
                                        {{ game.game.name }}
                                    </th>
                                    <th scope="col">Phone Number</th>

                                    <th scope="col">Active</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(agentt, index) in agents"
                                    :key="index"
                                >
                                    <td class="whitespace-nowrap font-medium">
                                        {{ ++index}}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ agentt.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ agentt.code }}
                                    </td>

                                    <td
                                        v-for="(
                                            commi, index
                                        ) in agentt.agent_commissions"
                                        :key="index"
                                        class="whitespace-nowrap"
                                    >
                                        {{ commi.commission_amount }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ agentt.phone_number }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 border-r"
                                    >
                                        <label
                                            :for="`toggle${agentt.id}`"
                                            class="big-checkbox-input"
                                        >
                                            <input
                                                type="checkbox"
                                                :checked="agentt.is_active"
                                                :id="`toggle${agentt.id}`"
                                                class="sr-only peer"
                                                @click="
                                                    agentToggle(
                                                        agentt.id,
                                                        !agentt.is_active
                                                    )
                                                "
                                            />
                                            <div
                                                class="checkbox-ui peer peer-focus:outline-none peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white peer-checked:bg-blue-600"
                                            ></div>
                                        </label>
                                    </td>

                                    <td
                                        class="whitespace-nowrap px-6 py-4 border-r"
                                    >
                                        <button
                                            class="mr-3 px-2 py-4"
                                            type="button"
                                            data-twe-toggle="modal"
                                            data-twe-target="#game_modal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                            @click="editAgent(agentt)"
                                        >
                                            <i class="fal fa-edit"></i>
                                        </button>
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
                                    getAgents();
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            data-twe-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="game_modal"
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
                        >
                            {{ agent.id ? "Edit Agent" : "Add Agent" }}
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
                    <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                        <div class="mb-6">
                            <label for="" class="text-sm mb-3 relative block"
                                >Name</label
                            >
                            <input
                                type="text"
                                placeholder="name"
                                v-model="agent.name"
                                class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                            />
                        </div>
                        <div class="mb-6">
                            <label for="" class="text-sm mb-3 relative block"
                                >Code</label
                            >
                            <input
                                type="text"
                                placeholder="code"
                                v-model="agent.code"
                                class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                            />
                        </div>
                        <div class="mb-6">
                            <label for="" class="text-sm mb-3 relative block"
                                >Phone Number</label
                            >
                            <input
                                type="text"
                                placeholder="phone_number"
                                v-model="agent.phone_number"
                                class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                            />
                        </div>
                        <div class="mb-6">
                            <label for="" class="text-sm mb-3 relative block"
                                >Password</label
                            >
                            <input
                                type="password"
                                placeholder="password"
                                v-model="agent.password"
                                class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                            />
                        </div>
                        <div
                            class="mb-6"
                            v-for="(game, index) in games"
                            :key="index"
                        >
                            <label for="" class="text-sm mb-3 relative block">{{
                                game.name
                            }}</label>
                            <input
                                type="number"
                                placeholder="commission"
                                v-model="game.commission_amount"
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
                            id="modalClose"
                            data-twe-ripple-color="light"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            @click="updateOrCreateAgent"
                            class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        >
                            Done
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
export default {
    components: {
        WebPagination,
        SearchBox,
    },
    data() {
        return {
            agents: [],
            games: [],
            search_input: "",
            agent: {
                id: "",
                name: "",
                code: "",
                phone_number: "",
                password: "",
            },
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),
        async getGames() {
            let url = `/api/games`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.games = response.data.filter((game) => {
                    if (game.is_active == 1) {
                        game.commission_amount = "";
                        game.commission_id = "";
                        return true;
                    }
                    return false;
                });
                console.log(response.data);
            }
        },
        async getAgents() {
            let url = `/api/agents?search_input=${this.search_input}&page=${this.currentPage}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                console.log(response.data);
                this.agents = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getAgents();
        },
        modalClose() {
            const button = document.getElementById("modalClose");
            if (button) {
                button.click();
            }
        },
        async agentToggle(id, value) {
            let url = "/api/agents/toggle_is_active";
            let formData = new FormData();
            formData.append("id", id);
            formData.append("is_active", value ? 1 : 0);
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.data) {
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                });
                var temp = this.agents.find((n) => n.id == id);
                temp.is_active = !value;
            }
        },
        editAgent(agent) {
            this.agent.id = agent.id;
            this.agent.name = agent.name;
            this.agent.code = agent.code;
            this.agent.phone_number = agent.phone_number;
            this.agent.password = "";
            this.games.forEach((game) => {
                var temp = agent.agent_commissions.find(
                    (cm) => cm.game_id == game.id
                );
                game.commission_id = temp.id;
                game.commission_amount = temp.commission_amount;
            });
        },
        resetAgentForm() {
            this.agent = {
                id: "",
                name: "",
                code: "",
                phone_number: "",
                password: "",
            };
            this.games.forEach((game) => {
                game.commission_amount = "";
                game.commission_id = "";
            });
        },
        async updateOrCreateAgent() {
            if (this.agent.id) {
                //edit
                if (
                    !this.agent.name ||
                    !this.agent.code ||
                    !this.agent.phone_number ||
                    !this.games[0].commission_amount
                ) {
                    this.$notify({
                        title: "Error!",
                        text: "Please fill all fields!",
                        type: "error",
                    });
                    return;
                }
            } else {
                //create
                if (
                    !this.agent.name ||
                    !this.agent.code ||
                    !this.agent.phone_number ||
                    !this.agent.password ||
                    !this.games[0].commission_amount
                ) {
                    this.$notify({
                        title: "Error!",
                        text: "Please fill all fields!",
                        type: "error",
                    });
                    return;
                }
            }
            let formData = new FormData();
            if (this.agent.id) {
                formData.append("id", this.agent.id);
                var temp_commission = this.games.map((game) => {
                    return {
                        game_id: game.id,
                        id: game.commission_id,
                        commission_amount: game.commission_amount,
                    };
                });
            } else {
                var temp_commission = this.games.map((game) => {
                    return {
                        game_id: game.id,
                        commission_amount: game.commission_amount,
                    };
                });
            }
            formData.append("name", this.agent.name);
            formData.append("code", this.agent.code);
            formData.append("phone_number", this.agent.phone_number);
            formData.append("password", this.agent.password);
            formData.append("commission", JSON.stringify(temp_commission));

            let url = "/api/agents";
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });
                this.getAgents();
                this.resetAgentForm();
                this.modalClose();
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.error,
                    type: "error",
                });
            }
        },
    },

    created() {},

    mounted() {
        this.getAgents();
        this.getGames();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
