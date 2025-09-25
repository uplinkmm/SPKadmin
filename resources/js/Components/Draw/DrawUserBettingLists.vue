<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex items-center mb-4 space-x-3">
                <label for="itemsPerPage" class="mr-2 text-gray-700"
                    >Show</label
                >
                <select
                    id="itemsPerPage"
                    @change="getBettingLists(true)"
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
                    @change="getBettingLists(true)"
                >
                    <option value="">Select Lottery Game</option>
                    <option
                        :value="draw.id"
                        v-for="(draw, index) in draws"
                        :key="index"
                    >
                        {{ draw.name }}
                    </option>
                </select>
            </div>
            <SearchBox class="mr-3 h-full" :search-handler="searchHandler" />
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
                                    <th scope="col" class="">Number</th>
                                    <th scope="col" class="">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="loading" class="text-center">
                                    <td colspan="5" class="py-4">Loading...</td>
                                </tr>
                                <tr
                                    v-else-if="
                                        betting_lists.length == 0 && !loading
                                    "
                                    class="text-center"
                                >
                                    <td colspan="5" class="py-4">
                                        No betting lists found
                                    </td>
                                </tr>
                                <tr
                                    v-for="(
                                        betting_list, index
                                    ) in betting_lists"
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
                                        {{ betting_list.lottery.customer.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            betting_list.lottery.game_setting
                                                .name
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ betting_list.number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ betting_list.amount }}
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
                                    getBettingLists(false);
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
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        WebPagination,
        SearchBox,
    },
    data() {
        return {
            betting_lists: [],
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
        async getBettingLists(reset_page) {
            this.loading = true;
            this.betting_lists = [];
            if (reset_page) {
                this.setCurrentPage(1);
            }
            try {
                let url = `/api/lottery_betting_list?page=${
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
                    this.betting_lists = response.data.data;
                    this.setTotalCount(response.data.total);
                }
                this.loading = false;
            } catch (e) {
                this.loading = false;
            } finally {
                this.loading = false;
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getBettingLists(true);
        },
        async drawLists() {
            let url = `/api/draws`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.draws = response.data;
                if (this.draws.length > 0) {
                    this.game_setting_id = this.draws[0].id;
                }
                this.getBettingLists(true);
            }
        },
    },

    mounted() {
        this.drawLists();

        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
