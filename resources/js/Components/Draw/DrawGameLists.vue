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
                        href="/draw/game_create_or_update"
                        class="inline-block bg-blue-900 text-white text-xs px-8 pt-4 pb-1 rounded-md"
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
                                    <th scope="col" class="">Active</th>
                                    <th scope="col" class="">Action</th>
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
                                            {{ item.name }},
                                        </span>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-2 py-2 border-r"
                                    >
                                        <label
                                            :for="`toggle${draw.id}`"
                                            class="small-checkbox-input"
                                        >
                                            <input
                                                type="checkbox"
                                                :checked="draw.is_active"
                                                :id="`toggle${draw.id}`"
                                                class="sr-only peer"
                                                @click="
                                                    drawToggle(
                                                        draw.id,
                                                        !draw.is_active
                                                    )
                                                "
                                            />
                                            <div
                                                class="checkbox-ui peer peer-focus:outline-none peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white peer-checked:bg-blue-600"
                                            ></div>
                                        </label>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <a
                                            :href="`/draw/game_create_or_update?id=${draw.id}`"
                                        >
                                            <i class="fal fa-edit"></i>
                                        </a>
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
        async drawToggle(id, value) {
            const previousState = this.draws.find(
                (draw) => draw.id === id
            ).is_active;
            this.draws.find((draw) => draw.id === id).is_active = value;
            let url = "/api/draws/toggle_is_active";
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
                this.draws.find((draw) => draw.id === id).is_active =
                    previousState;
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getDraws(true);
        },
    },

    mounted() {
        this.getDraws(true);
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
