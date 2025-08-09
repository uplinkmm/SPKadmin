<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex">
                <button
                    type="button"
                    class="add-btn"
                    data-twe-toggle="modal"
                    data-twe-target="#createModal"
                >
                    Add
                </button>
            </div>
            <label for="search" class="search-ui">
                <input type="text" id="search" placeholder="Search" />
            </label>
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Payment</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Transaction</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="num in 3" :key="num">
                            <td class="whitespace-nowrap">
                                {{ num }}
                            </td>
                            <td class="whitespace-nowrap">Khant</td>
                            <td class="whitespace-nowrap">09 112233</td>
                            <td class="whitespace-nowrap">K Pay</td>
                            <td class="whitespace-nowrap">
                                {{ Math.floor(Math.random() * 90000) }}
                            </td>
                            <td class="whitespace-nowrap">112233</td>
                            <td class="whitespace-nowrap">Approved</td>
                            <td class="whitespace-nowrap">
                                {{ Math.floor(Math.random() * 10) }} May
                            </td>
                            <td class="whitespace-nowrap">
                                {{ Math.floor(10 + Math.random() * 10) }} May
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Create Modal -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="createModal"
        tabindex="-1"
        aria-labelledby="createModalLabel"
        aria-hidden="true"
    >
        <div
            data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
        >
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none"
            >
                <div class="modal-header-box">
                    <h5 class="modal-header-title" id="createModalLabel">
                        Add
                    </h5>
                    <button
                        type="button"
                        id="closeCreateModal"
                        class="close-modal-btn"
                        data-twe-modal-dismiss
                        aria-label="Close"
                    >
                        <i class="fal fa-times text-xl"></i>
                    </button>
                </div>
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6">
                        <label for="Name" class="text-sm mb-3 relative block"
                            >Name</label
                        >
                        <input
                            type="text"
                            id="name"
                            placeholder="Name"
                            class="input-form"
                        />
                    </div>
                    <div class="mb-6">
                        <label for="Name" class="text-sm mb-3 relative block"
                            >Payment Type</label
                        >
                        <select name="" id="" class="select-form">
                            <option value="kpay">KBZ Pay</option>
                            <option value="wave">Wave Pay</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label
                            for="ph_number"
                            class="text-sm mb-3 relative block"
                            >Phone Number</label
                        >
                        <input
                            type="text"
                            id="ph_number"
                            placeholder="Phone Number"
                            class="input-form"
                        />
                    </div>
                </div>
                <div class="modal-footer-box">
                    <button
                        type="button"
                        class="camcel-btn-form"
                        data-twe-modal-dismiss
                        id="modal_close"
                    >
                        Close
                    </button>
                    <button type="button" class="add-btn-form">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";

import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters, mapMutations } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import {
    convertToFriendlyDateTime,
    getCurrentDate,
} from "../../utilities/datetime-helpers";
export default {
    data() {
        return {};
    },

    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),
    },

    mounted() {
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
