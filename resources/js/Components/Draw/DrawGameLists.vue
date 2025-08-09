<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex"></div>

            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <div class="flex justify-between">
                    <div class="flex items-center mb-4">
                        <label for="itemsPerPage" class="mr-2 text-gray-700"
                            >Show</label
                        >
                        <select
                            id="itemsPerPage"
                            @change="getDraws(true)"
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
                        href="/draw/game_create"
                        class="inline-block bg-blue-900 text-white text-sm px-3 py-3 rounded-md"
                        >Add New</a
                    >
                </div>

                <div class="">
                    <div class="table-container">
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No</th>
                                    <th scope="col" class="">Draw Name</th>
                                    <th scope="col" class="">Ticket Price</th>
                                    <th scope="col" class="">Ticket Amount</th>
                                    <th scope="col" class="">Prizes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(draw, index) in draws"
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
                                        {{ draw.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ draw.price }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ draw.limitation_quantity }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span
                                            v-for="(item, index) in draw.prizes"
                                            :key="index"
                                        >
                                            {{ item.name }}
                                        </span>
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
                                    getDraws(false);
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
            draws: [],
            edit_bet_amount: {
                id: "",
                column: "",
                value: "",
            },
            search_input: "",
            per_page: 50,
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),
        async getDraws(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/draws?page=${this.currentPage}&search_input=${
                this.search_input
            }${this.per_page ? `&per_page=${this.per_page}` : ""}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.draws = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        async updateBetAmount() {
            let url = "/api/update_customer_bet_limit";
            let formData = new FormData();
            formData.append("id", this.edit_bet_amount.id);
            formData.append("column", this.edit_bet_amount.column);
            formData.append("value", this.edit_bet_amount.value);
            this.loading = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            this.loading = false;
            if (response.data) {
                this.edit_bet_amount = {
                    id: "",
                    column: "",
                    value: "",
                };
                this.getDraws(false);
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });
                return;
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                });
                return;
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getDraws(true);
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY");
            }
        },
    },

    mounted() {
        this.getDraws(true);
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
