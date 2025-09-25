<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex items-center mb-4 space-x-3">
                <label for="itemsPerPage" class="mr-2 text-gray-700"
                    >Show</label
                >
                <select
                    id="itemsPerPage"
                    @change="getWinnerLists(true)"
                    v-model="per_page"
                    class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500"
                >
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="50000">All</option>
                </select>
                <select
                    v-model="game_setting_id"
                    name=""
                    id=""
                    class="select-form"
                    @change="getWinnerLists(true)"
                >
                    <option value="">All</option>
                    <option
                        :value="draw.id"
                        v-for="(draw, index) in draws"
                        :key="index"
                    >
                        {{ draw.name }}
                    </option>
                </select>
            </div>

            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <div class="">
                    <div class="table-container">
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No</th>
                                    <th scope="col" class="">Customer Name</th>
                                    <th scope="col" class="">Draw Name</th>
                                    <th scope="col" class="">Prize Name</th>
                                    <th scope="col" class="">Winning Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-if="winner_lists.length == 0 && !loading"
                                    class="text-center"
                                >
                                    <td colspan="5" class="py-4">
                                        No winner lists found
                                    </td>
                                </tr>
                                <tr v-else-if="loading" class="text-center">
                                    <td colspan="5" class="py-4">Loading...</td>
                                </tr>
                                <tr
                                    v-for="(winner_list, index) in winner_lists"
                                    :key="index"
                                    class=""
                                >
                                    <td class="whitespace-nowrap">
                                        {{
                                            ++index +
                                            (currentPage - 1) * per_page
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ winner_list.approved_by.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            winner_list.prize.game_setting.name
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ winner_list.prize.name }} ({{
                                            winner_list.prize.prize
                                        }})
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ winner_list.number }}
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
                                    getWinnerLists(false);
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
import SearchBox from "../Common/SearchBox.vue";
import WebPagination from "../Common/webPagination.vue";

export default {
    components: {
        SearchBox,
        WebPagination,
    },
    data() {
        return {
            winner_lists: [],
            search_input: "",
            per_page: 50,
            draws: [],
            game_setting_id: "",
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),

        async getWinnerLists(reset_page) {
            this.winner_lists = [];
            this.loading = true;
            if (reset_page) {
                this.setCurrentPage(1);
            }
            try {
                let url = `/api/lottery_winning_user_list?page=${
                    this.currentPage
                }&search_input=${this.search_input}${
                    this.per_page ? `&per_page=${this.per_page}` : ""
                }${
                    this.game_setting_id
                        ? `&game_setting_id=${this.game_setting_id}`
                        : ""
                }`;
                let response = await getApiData({
                    url: url,
                    token: this.getToken,
                });
                if (response.data) {
                    this.winner_lists = response.data.data;
                    this.setTotalCount(response.data.total);
                }
            } catch (e) {
                this.loading = false;
            } finally {
                this.loading = false;
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getWinnerLists(true);
        },
        async drawLists() {
            let url = `/api/draws`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.draws = response.data;
            }
        },
    },

    mounted() {
        this.drawLists();
        this.getWinnerLists(true);

        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
