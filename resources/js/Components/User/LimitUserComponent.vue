<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex"></div>

            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <SelectionPaginationCount
                    :handleChange="
                        (value) => ((per_page = value), getUsers(true))
                    "
                    :initialValue="per_page"
                />
                <div class="">
                    <div class="table-container">
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No</th>
                                    <th scope="col" class="">Name</th>
                                    <th scope="col" class="">Phone Number</th>
                                    <th scope="col" class="">Two D Limit</th>
                                    <th scope="col" class="">Three D Limit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(user, index) in users"
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
                                        {{ user.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ user.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span
                                            v-if="
                                                edit_bet_amount.id == user.id &&
                                                edit_bet_amount.column ==
                                                    'two_d_limit'
                                            "
                                        >
                                            <input
                                                type="number"
                                                class="px-4 py-2 text-black"
                                                v-model="edit_bet_amount.value"
                                            />
                                            <button @click="updateBetAmount">
                                                <i
                                                    class="fas fa-check mr-2"
                                                ></i>
                                            </button>

                                            <button
                                                @click="
                                                    this.edit_bet_amount.value =
                                                        user.two_d_limit
                                                "
                                            >
                                                <i class="fal fa-trash"></i>
                                            </button>
                                        </span>
                                        <span
                                            v-else
                                            @click="
                                                edit_bet_amount.id = user.id;
                                                edit_bet_amount.column =
                                                    'two_d_limit';
                                                edit_bet_amount.value =
                                                    user.two_d_limit;
                                                edit_bet_amount.original_amount =
                                                    user.two_d_limit;
                                            "
                                        >
                                            {{ user.two_d_limit }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <span
                                            v-if="
                                                edit_bet_amount.id == user.id &&
                                                edit_bet_amount.column ==
                                                    'three_d_limit'
                                            "
                                        >
                                            <input
                                                type="number"
                                                class="px-4 py-2 text-black"
                                                v-model="edit_bet_amount.value"
                                            />
                                            <button @click="updateBetAmount">
                                                <i
                                                    class="fas fa-check mr-2"
                                                ></i>
                                            </button>

                                            <button
                                                @click="
                                                    this.edit_bet_amount.value =
                                                        user.three_d_limit
                                                "
                                            >
                                                <i class="fal fa-trash"></i>
                                            </button>
                                        </span>
                                        <span
                                            v-else
                                            @click="
                                                edit_bet_amount.id = user.id;
                                                edit_bet_amount.column =
                                                    'three_d_limit';
                                                edit_bet_amount.value =
                                                    user.three_d_limit;
                                                edit_bet_amount.original_amount =
                                                    user.three_d_limit;
                                            "
                                        >
                                            {{ user.three_d_limit }}
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
                                    getUsers(false);
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
import SelectionPaginationCount from "../Common/SelectionPaginationCount.vue";

export default {
    components: {
        WebPagination,
        SearchBox,
        SelectionPaginationCount,
    },
    data() {
        return {
            users: [],
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
        async getUsers(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/get_customer_limitation_list?page=${
                this.currentPage
            }&search_input=${this.search_input}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.users = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        async updateBetAmount() {
            let url = "/api/update_customer_bet_limit";
            let formData = new FormData();
            formData.append("id", this.edit_bet_amount.id);
            formData.append("column", this.edit_bet_amount.column);
            formData.append("value", this.edit_bet_amount.value);
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.data) {
                this.edit_bet_amount = {
                    id: "",
                    column: "",
                    value: "",
                };
                this.getUsers(false);
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
            this.getUsers(true);
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY");
            }
        },
    },

    mounted() {
        this.getUsers(true);
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
