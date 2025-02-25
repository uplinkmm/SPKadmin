<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div>
                <p class="font-semibold font-inter text-black mb-3">
                    Feedbacks
                </p>
            </div>
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="overflow-hidden">
                    <div class="flex items-center mb-4">
                        <label for="itemsPerPage" class="mr-2 text-gray-700"
                            >Show</label
                        >
                        <select
                            id="itemsPerPage"
                            @change="getFeedbacks(true)"
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
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No.</th>
                                    <th scope="col" class="">User Name</th>
                                    <th scope="col" class="">Body</th>
                                    <th scope="col" class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(feedback, index) in feedbacks"
                                    :key="index"
                                    class="border-b border-l border-neutral-200"
                                >
                                    <td class="whitespace-nowrap">
                                        {{ ++index + (currentPage - 1) * per_page }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ feedback?.customer.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ feedback?.text }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <button
                                            @click="deleteFeedback(feedback.id)"
                                            class="mr-3 px-2 py-4"
                                            type="button"
                                        >
                                            <i class="fal fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6" v-if="getTotalCount > per_page">
                        <webPagination
                            :total-items-count="getTotalCount"
                            :items-per-page="per_page"
                            :current-page="currentPage"
                            active-color="#ffffff"
                            icon-color="#604f4f"
                            inactive-color="#604f4f"
                            disabled-color="#c8b5db"
                            @pageChanged="
                                setCurrentPage($event);
                                getFeedbacks(false);
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
import {
    getApiData,
    postApiData,
    deleteApiData,
} from "../../utilities/ajax-helpers";
import WebPagination from "../Common/webPagination.vue";

export default {
    components: {
        WebPagination,
    },
    data() {
        return {
            feedbacks: [],
            per_page: 50,
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),

        async getFeedbacks(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/feedbacks?page=${this.currentPage}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.feedbacks = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        modalClose() {
            const button = document.getElementById("modalClose");
            if (button) {
                button.click();
            }
        },
        async deleteFeedback(id) {
            let url = `/api/feedbacks/${id}`;
            let response = await deleteApiData({
                url: url,
                token: this.getToken,
            });
            this.$notify({
                title: "Success!",
                text: response.message,
                type: "info",
            });
            this.getFeedbacks();
        },
    },

    mounted() {
        this.getFeedbacks();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
