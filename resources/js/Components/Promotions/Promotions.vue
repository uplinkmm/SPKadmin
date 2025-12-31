<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <p class="font-medium text-lg font-inter text-black mb-3">
                Promotions
            </p>
            <button
                type="button"
                data-twe-toggle="modal"
                data-twe-target="#new_promotions"
                data-twe-ripple-init
                data-twe-ripple-color="light"
                class="rounded bg-[#303030] px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:shadow-primary-2 focus:outline-none focus:ring-0"
            >
                Create
            </button>
            <SearchBox :search-handler="searchHandler" />
        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <div class="">
                    <SelectionPaginationCount
                        :handleChange="
                            (value) => ((per_page = value), getUsers(true))
                        "
                        :initialValue="per_page"
                    />
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Deposit Amount</th>
                                    <th scope="col">Promotion (%)</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(promotion, index) in promotions"
                                    :key="index"
                                >
                                    <td class="whitespace-nowrap font-medium">
                                        {{
                                            ++index +
                                            (currentPage - 1) * per_page
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ promotion.start_date }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ promotion.end_date }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        {{
                                            promotion.deposit_amount.toLocaleString()
                                        }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ promotion.promotion_percentage }} %
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <label
                                            :for="`toggle${promotion.id}`"
                                            class="big-checkbox-input"
                                        >
                                            <input
                                                type="checkbox"
                                                :checked="promotion.is_active"
                                                :id="`toggle${promotion.id}`"
                                                class="sr-only peer"
                                                @click="
                                                    promotionToggle(
                                                        promotion.id,
                                                        !promotion.is_active
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
                                    getUsers(false);
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Promotions -->
        <div
            data-twe-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="new_promotions"
            tabindex="-1"
            aria-labelledby="ModalLabel"
            aria-hidden="true"
        >
            <div
                data-twe-modal-dialog-ref
                class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
            >
                <div
                    class="p-6 pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none"
                >
                    <div
                        class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4"
                    >
                        <h5
                            class="text-xl font-medium leading-normal text-surface"
                            id="ModalLabel"
                        >
                            Create Promotions
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
                    <div
                        class="relative flex-auto px-4 py-2"
                        data-twe-modal-body-ref
                    >
                        <div class="">
                            <label for="" class="text-sm relative block"
                                >Start Date</label
                            >
                            <VueDatePicker
                                v-model="new_promotion.start_date"
                                :is-24="false"
                                auto-apply
                                format="dd/MM/yyyy hh:mm a"
                            />
                        </div>
                    </div>
                    <div
                        class="relative flex-auto px-4 py-2"
                        data-twe-modal-body-ref
                    >
                        <div class="">
                            <label for="" class="text-sm relative block"
                                >End Date</label
                            >
                            <VueDatePicker
                                v-model="new_promotion.end_date"
                                :is-24="false"
                                auto-apply
                                format="dd/MM/yyyy hh:mm a"
                            />
                        </div>
                    </div>
                    <div
                        class="relative flex-auto px-4 py-2"
                        data-twe-modal-body-ref
                    >
                        <div class="">
                            <label for="" class="text-sm relative block"
                                >Deposit Amount</label
                            >
                            <input
                                type="number"
                                placeholder="Deposit Amount"
                                v-model="new_promotion.deposit_amount"
                                pattern="\\d*"
                                @input="
                                    new_promotion.deposit_amount =
                                        $event.target.value.replace(
                                            /[^0-9]/g,
                                            ''
                                        )
                                "
                                class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                            />
                        </div>
                    </div>
                    <div
                        class="relative flex-auto px-4 py-2"
                        data-twe-modal-body-ref
                    >
                        <div class="">
                            <label for="" class="text-sm mb-3 relative block"
                                >Promotion (%)</label
                            >
                            <input
                                type="number"
                                placeholder="Promotion (%)"
                                v-model="new_promotion.promotion_percentage"
                                pattern="\d*"
                                @input="
                                    new_promotion.promotion_percentage =
                                        $event.target.value.replace(
                                            /[^0-9]/g,
                                            ''
                                        )
                                "
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
                            :disabled="loading"
                            @click="createPromotion"
                            class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        >
                            {{ loading ? "Loading..." : "Create" }}
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
import SelectionPaginationCount from "../Common/SelectionPaginationCount.vue";
import moment from "moment";

export default {
    components: {
        WebPagination,
        SearchBox,
        SelectionPaginationCount,
    },
    data() {
        return {
            promotions: [],
            search_input: "",
            new_promotion: {
                start_date: "",
                end_date: "",
                deposit_amount: "",
                promotion_percentage: "",
            },
            per_page: "50",

            loading: false,
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
            let url = `/api/game_promotions?search_input=${
                this.search_input
            }&page=${this.currentPage}${
                this.per_page ? `&per_page=${this.per_page}` : ""
            }`;

            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.message.data) {
                console.log(response.message.data);
                this.promotions = response.message.data;
                this.setTotalCount(response.message.total);
            }
        },

        async createPromotion() {
            if (
                !this.new_promotion.start_date ||
                !this.new_promotion.end_date ||
                !this.new_promotion.deposit_amount ||
                !this.new_promotion.promotion_percentage
            ) {
                this.$notify({
                    title: "Error!",
                    text: "Please fill all forms!",
                    type: "error",
                });
                return;
            }
            if (this.new_promotion.promotion_percentage > 100) {
                this.$notify({
                    title: "Error!",
                    text: "Promotion percentage must be below 100%!",
                    type: "error",
                });
                return;
            }

            let formData = new FormData();
            formData.append(
                "start_date",
                moment(this.new_promotion.start_date).format(
                    "YYYY-MM-DD HH:mm:ss"
                )
            );
            formData.append(
                "end_date",
                moment(this.new_promotion.end_date).format(
                    "YYYY-MM-DD HH:mm:ss"
                )
            );
            formData.append(
                "deposit_amount",
                this.new_promotion.deposit_amount
            );
            formData.append(
                "promotion_percentage",
                this.new_promotion.promotion_percentage
            );

            let url = "/api/game_promotions";
            this.loading = true;
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            this.loading = false;
            if (response.success) {
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });
                this.getPromotions(true);
                this.new_promotion = {
                    start_date: "",
                    end_date: "",
                    deposit_amount: "",
                    promotion_percentage: "",
                };
                this.modalClose("modalClose");
            } else {
                this.$notify({
                    title: "Error!",
                    text:
                        response.message.start_date ||
                        response.message.end_date ||
                        response.message.deposit_amount ||
                        response.message.promotion_percentage ||
                        response.message,
                    type: "error",
                });
            }
        },
        async promotionToggle(id, value) {
            const previousState = this.promotions.find(
                (promotion) => promotion.id === id
            ).is_active;
            this.promotions.find((promotion) => promotion.id === id).is_active =
                value;
            let url = `/api/toggle_is_active?is_active=${value}&id=${id}&type=game_promotion`;

            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            console.log(response);
            if (response.success) {
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
                this.promotions.find(
                    (promotion) => promotion.id === id
                ).is_active = previousState;
            }
        },
        modalClose(id) {
            const button = document.getElementById(id);
            if (button) {
                button.click();
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getPromotions(true);
        },
        formatDate(date) {
            if (date) {
                return moment(date).format("DD/MM/YYYY hh:mm A");
            }
        },
    },

    created() {},

    mounted() {
        this.getPromotions(true);
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
