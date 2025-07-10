<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between mb-4">
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
                            @change="getSlotUsers(true)"
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
                                    <th scope="col" class="p-4">Name</th>
                                    <th scope="col" class="p-4">
                                        Phone Number
                                    </th>
                                    <th scope="col" class="p-4">Balance</th>
                                    <th scope="col" class="p-4">
                                        Register Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(user, index) in user_lists"
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
                                        {{ user.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ user.phone_number }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span v-if="user.show_balance">{{
                                            user.game_money_balance
                                        }}</span>
                                        <span v-else class="">*****</span>
                                        <button
                                            class="ml-4"
                                            @click="
                                                changeShowBalance(user.id, true)
                                            "
                                            v-if="!user.show_balance"
                                        >
                                            <i class="far fa-eye text-lg"></i>
                                        </button>
                                        <button
                                            class="ml-4"
                                            @click="
                                                changeShowBalance(
                                                    user.id,
                                                    false
                                                )
                                            "
                                            v-else
                                        >
                                            <i
                                                class="far fa-eye-slash text-lg"
                                            ></i>
                                        </button>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ formatDate(user.verified_at) }}
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
                                    getSlotUsers(false);
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
            user_lists: [],
            search_input: "",
            per_page: 50,
            users: [],
            game_types: [],
            game_type_id: 1,
            providers: [],
            provider_id: "",
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),
        changeShowBalance(userId, status) {
            this.user_lists = this.user_lists.map((user) =>
                user.id === userId ? { ...user, show_balance: status } : user
            );
        },
        async getSlotUsers(per_page) {
            if (per_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/slot_user_list?page=${
                this.currentPage
            }&search_input=${this.search_input}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                // console.log(response.data.customers);
                this.user_lists = response.data.customers.data.map((user) => ({
                    ...user,
                    show_balance: false,
                }));
                this.setTotalCount(response.data.total);
            }
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY h:m A");
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getSlotUsers(true);
        },
    },
    created() {},

    mounted() {
        this.getSlotUsers(true);
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
