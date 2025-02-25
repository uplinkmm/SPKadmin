<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div class="flex">
                <button
                    type="button"
                    class="add-btn"
                    data-twe-toggle="modal"
                    data-twe-target="#createModal"
                    @click="
                        (new_edit_account.id = ''),
                            (new_edit_account.account_type = ''),
                            (new_edit_account.phone_number = ''),
                            (new_edit_account.name = ''),
                            (new_edit_account.color_code = '')
                    "
                >
                    Add Payment Provider
                </button>
            </div>
            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col" class="text-center">Logo</th>
                            <th scope="col">Payment Provider</th>
                            <th scope="col">Owner</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Status</th>
                            <th scope="col">Updated At</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(account, index) in accounts" :key="index">
                            <td class="whitespace-nowrap">
                                {{ ++index }}
                            </td>
                            <td class="whitespace-nowrap">
                                <div class="flex justify-center">
                                    <img
                                        v-if="account.account_type == 'kpay'"
                                        class="rounded-full w-14 h-14"
                                        src="../../../../public/img/kpay.png"
                                        alt=""
                                    />
                                    <img
                                        v-else
                                        class="rounded-full w-14 h-14"
                                        src="../../../../public/img/wave.png"
                                        alt=""
                                    />
                                </div>
                            </td>
                            <td class="whitespace-nowrap">
                                {{ account.account_type }}
                            </td>
                            <td class="whitespace-nowrap">
                                <div
                                    :style="{
                                        backgroundColor: account.color_code,
                                    }"
                                    class="color-text-box"
                                    :class="
                                        account.color_code
                                            ? 'text-white'
                                            : 'text-black'
                                    "
                                >
                                    {{ account.name }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap">
                                {{ account.phone_number }}
                            </td>
                            <td class="whitespace-nowrap">
                                <label
                                    :for="`toggle${account.id}`"
                                    class="big-checkbox-input"
                                >
                                    <input
                                        type="checkbox"
                                        :checked="account.is_active"
                                        :id="`toggle${account.id}`"
                                        class="sr-only peer"
                                        @click="
                                            accountToggle(
                                                account.id,
                                                account.name,
                                                account.phone_number,
                                                account.account_type,
                                                !account.is_active
                                            )
                                        "
                                    />
                                    <div
                                        class="checkbox-ui peer peer-focus:outline-none peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white peer-checked:bg-blue-600"
                                    ></div>
                                </label>
                            </td>
                            <td class="whitespace-nowrap">
                                {{ dateFormat(account.updated_at) }}
                            </td>

                            <td class="whitespace-nowrap text-center">
                                <button
                                    class="mr-3 px-2 py-4"
                                    type="button"
                                    data-twe-toggle="modal"
                                    data-twe-target="#createModal"
                                    data-twe-ripple-init
                                    data-twe-ripple-color="light"
                                    @click="
                                        (new_edit_account.id = account.id),
                                            (new_edit_account.account_type =
                                                account.account_type),
                                            (new_edit_account.phone_number =
                                                account.phone_number),
                                            (new_edit_account.name =
                                                account.name),
                                            (new_edit_account.color_code =
                                                account.color_code)
                                    "
                                >
                                    <i class="fal fa-edit"></i>
                                </button>
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
                        {{ new_edit_account.id ? "Edit" : "New" }} Payment
                        Provider
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
                            v-model="new_edit_account.name"
                        />
                    </div>
                    <div class="mb-6">
                        <label for="Name" class="text-sm mb-3 relative block"
                            >Payment Type</label
                        >
                        <select
                            v-model="new_edit_account.account_type"
                            name=""
                            id=""
                            class="select-form"
                        >
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
                            v-model="new_edit_account.phone_number"
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            for="color_code"
                            class="text-sm mb-3 relative block"
                            >Pick Color</label
                        >
                        <div class="flex">
                            <input
                                type="text"
                                id="color_code"
                                placeholder="Color"
                                class="input-form"
                                v-model="new_edit_account.color_code"
                            />
                            <div class="mx-6 mt-1">
                                <color-picker
                                    v-model:pureColor="
                                        new_edit_account.color_code
                                    "
                                    format="hex"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-box">
                    <button
                        type="button"
                        class="cancel-btn-form"
                        data-twe-modal-dismiss
                        id="modal_close"
                    >
                        Close
                    </button>
                    <button
                        @click="createUpdateAccount"
                        type="button"
                        class="add-btn-form"
                    >
                        Add
                    </button>
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
import webPagination from "../Common/webPagination.vue";
import SearchBox from "../Common/SearchBox.vue";
import { ColorPicker } from "vue3-colorpicker";
import "vue3-colorpicker/style.css";

export default {
    components: {
        SearchBox,
        webPagination,
        ColorPicker,
    },
    data() {
        return {
            accounts: "",
            new_edit_account: {
                id: "",
                account_type: "",
                phone_number: "",
                name: "",
                color_code: "",
            },
            search_input: "",
            colorr: "#4cb050",
        };
    },
    computed: {
        ...mapGetters(["getToken"]),
    },
    methods: {
        async getPayments(reset_page) {
            if (this.reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/accounts?search_input=${this.search_input}`;

            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.accounts = response.data;
            }
        },
        dateFormat(date) {
            return moment(date).format("DD-MM-YYYY hh:mm A");
        },
        async createUpdateAccount() {
            let url = "/api/accounts";
            let formData = new FormData();
            if (this.new_edit_account.id) {
                formData.append("id", this.new_edit_account.id);
            }
            formData.append("account_type", this.new_edit_account.account_type);
            formData.append("phone_number", this.new_edit_account.phone_number);
            formData.append("name", this.new_edit_account.name);
            formData.append("color_code", this.new_edit_account.color_code);
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.data) {
                this.getPayments(false);
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });
                this.modal_close_btn();
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
        async accountToggle(id, name, phone_number, account_type, value) {
            // Store the previous state of the toggle
            const previousState = this.accounts.find(
                (account) => account.id === id
            ).is_active;

            // Update the UI immediately to reflect the new state
            this.accounts.find((account) => account.id === id).is_active =
                value;

            let url = "/api/accounts/toggle_is_active";
            let formData = new FormData();
            formData.append("id", id);
            formData.append("name", name);
            formData.append("phone_number", phone_number);
            formData.append("account_type", account_type);
            formData.append("is_active", value ? 1 : 0);

            try {
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
                    this.accounts.find(
                        (account) => account.id === id
                    ).is_active = previousState;
                    this.$notify({
                        title: "Error!",
                        text: response.message,
                        type: "error",
                    });
                }
            } catch (error) {
                this.accounts.find((account) => account.id === id).is_active =
                    previousState;
                this.$notify({
                    title: "Error!",
                    text: "An error occurred while updating the status.",
                    type: "error",
                });
            }
        },
        modal_close_btn() {
            const button = document.getElementById("modal_close");
            if (button) {
                button.click();
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getPayments(true);
        },
    },

    created() {},

    mounted() {
        this.getPayments(false);
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
