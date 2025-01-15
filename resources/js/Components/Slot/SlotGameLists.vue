<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between mb-4">
            <div class="flex">
                <div class="">
                    <select
                        id="game_types"
                        v-model="game_type_id"
                        @change="getProviders"
                        class="block py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                    >
                        <option value="">All</option>
                        <option
                            v-for="type in game_types"
                            :key="type.id"
                            :value="type.id"
                        >
                            {{ type.name }}
                        </option>
                    </select>
                </div>
                <div class="">
                    <select
                        id="game_types"
                        v-model="provider_id"
                        @change="getGameLists(true)"
                        class="block ml-3 py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                    >
                        <option value="">All</option>
                        <option
                            v-for="provider in providers"
                            :key="provider.id"
                            :value="provider.id"
                        >
                            {{ provider.name }}
                        </option>
                    </select>
                </div>
            </div>

            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="">
                    <div class="flex items-center mb-4">
                        <label for="itemsPerPage" class="mr-2 text-gray-700"
                            >Show</label
                        >
                        <select
                            id="itemsPerPage"
                            @change="getGameLists(true)"
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
                                <tr>
                                    <th scope="col" class="p-4">No</th>
                                    <th scope="col" class="p-4">Game Code</th>
                                    <th scope="col" class="p-4">Game Name</th>
                                    <th scope="col" class="p-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(game, index) in game_lists"
                                    :key="index"
                                >
                                    <td
                                        class="whitespace-nowrap px-6 py-4 font-medium"
                                    >
                                        {{
                                            ++index +
                                            (currentPage - 1) * per_page
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ game.code }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ game.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <label
                                            :for="`toggle${game.id}`"
                                            class="big-checkbox-input"
                                        >
                                            <input
                                                type="checkbox"
                                                :checked="game.status"
                                                :id="`toggle${game.id}`"
                                                class="sr-only peer"
                                                @click="
                                                    gameToggle(
                                                        game.id,
                                                        !game.status
                                                    )
                                                "
                                            />
                                            <div
                                                class="checkbox-ui peer peer-focus:outline-none peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white peer-checked:bg-blue-600"
                                            ></div>
                                        </label>
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
                                    getGameLists(false);
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

export default {
    components: {
        WebPagination,
        SearchBox,
    },
    data() {
        return {
            game_lists: [],
            search_input: "",
            per_page: 50,
            game_types: [],
            game_type_id: "",
            providers: [],
            provider_id: "",
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),
        async getGameLists(per_page) {
            if (per_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/all_game_list?page=${
                this.currentPage
            }&search_input=${this.search_input}&${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }&game_type_id=${this.game_type_id}&product_id=${this.provider_id}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                console.log(response.data);
                this.game_lists = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        async gameToggle(id, value) {
            let url = "/api/toggle_game";
            let formData = new FormData();
            formData.append("id", id);
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });

            this.$notify({
                title: "Success!",
                text: response.message,
                type: "info",
            });
            var temp = this.games.find((n) => n.id == id);
            temp.status = !value;
        },
        async getGameType() {
            let url = `/api/game_type_list`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.game_types = response.data;
                this.getProviders();
            }
        },
        async getProviders() {
            if (!this.game_type_id) {
                this.getGameLists(true);
                return;
            }
            let url = `/api/product_list_by_game_type/${this.game_type_id}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.providers = response.data;
                this.getGameLists(true);
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getGameLists(true);
        },
    },
    created() {},

    mounted() {
        this.getGameType();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
