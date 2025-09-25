<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex"></div>

            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <div class="flex justify-between mb-2">
                    <div class="flex items-center mb-4">
                        <label for="itemsPerPage" class="mr-2 text-gray-700"
                            >Show</label
                        >
                        <select
                            id="itemsPerPage"
                            @change="getPromotions(true)"
                            v-model="per_page"
                            class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500"
                        >
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="50000">All</option>
                        </select>
                    </div>
                    <a
                        href="/draw/promotion_create_or_update"
                        class="inline-block bg-blue-900 text-white text-sm px-8 pt-4 pb-1 rounded-md"
                        >Add New</a
                    >
                </div>

                <div class="">
                    <div class="table-container">
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No</th>
                                    <th scope="col" class="">Game Name</th>
                                    <th scope="col" class="">From</th>
                                    <th scope="col" class="">TO</th>
                                    <th scope="col" class="">Promotion</th>
                                    <!-- <th scope="col" class="">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(promotion, index) in promotions"
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
                                        {{
                                            promotion.lottery_promotion
                                                .game_setting.name
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            promotion.lottery_promotion
                                                .start_date
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{
                                            promotion.lottery_promotion.end_date
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ promotion.qty }} +
                                        {{ promotion.additional_qty }}
                                    </td>
                                    <!-- <td class="whitespace-nowrap">
                                        <a :href="`/promotion/game_create_or_update?id=${promotion.id}`">
                                            <i class="fal fa-edit"></i>
                                        </a>
                                    </td> -->
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
                                    getPromotions(false);
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
            promotions: [],
            search_input: "",
            per_page: 50,
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),
        async getPromotions(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/lottery_promotions?page=${
                this.currentPage
            }&search_input=${this.search_input}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.promotions = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },

        searchHandler(search_input) {
            this.search_input = search_input;
            this.getPromotions(true);
        },
    },

    mounted() {
        this.getPromotions(true);
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
