<template>
    <div class="frame-container min-h-[100vh]">
        <div class="px-4 mb-4">
            <p class="font-semibold font-inter text-black mb-3">Contact Us Links</p>
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="overflow-hidden">
                    <div class="table-container">
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No.</th>
                                    <th scope="col" class="">Type</th>
                                    <th scope="col" class="">Value</th>
                                    <th scope="col" class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(row, index) in contactRows"
                                    :key="row.key"
                                    class="border-b border-l border-neutral-200"
                                >
                                    <td class="whitespace-nowrap">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ row.label }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <template v-if="row.isLink">
                                            <a
                                                :href="row.value"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="text-blue-600 underline"
                                            >
                                                {{ row.value }}
                                            </a>
                                        </template>
                                        <template v-else>
                                            {{ row.value }}
                                        </template>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button
                                            class="mr-3 px-2 py-4"
                                            type="button"
                                            data-twe-toggle="modal"
                                            data-twe-target="#contact_us_modal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                            @click="setEditContact(row)"
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
        </div>
    </div>

    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="contact_us_modal"
        tabindex="-1"
        aria-labelledby="ModalLabel"
        aria-hidden="true"
    >
        <div
            data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
        >
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none"
            >
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4"
                >
                    <h5
                        class="text-xl font-medium leading-normal text-surface"
                        id="ModalLabel"
                    >
                        Edit {{ activeFieldLabel }}
                    </h5>
                    <button
                        type="button"
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
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6" v-if="activeField === 'facebook_link'">
                        <label class="text-sm mb-3 relative block">Facebook Link</label>
                        <input
                            type="text"
                            placeholder="https://..."
                            v-model="edit_contact.facebook_link"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6" v-if="activeField === 'viber_number'">
                        <label class="text-sm mb-3 relative block">Viber Number</label>
                        <input
                            type="text"
                            placeholder="Viber Number"
                            v-model="edit_contact.viber_number"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6" v-if="activeField === 'phone_number'">
                        <label class="text-sm mb-3 relative block">Phone Number</label>
                        <input
                            type="text"
                            placeholder="Phone Number"
                            v-model="edit_contact.phone_number"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-2" v-if="activeField === 'telegram_link'">
                        <label class="text-sm mb-3 relative block">Telegram Link</label>
                        <input
                            type="text"
                            placeholder="https://..."
                            v-model="edit_contact.telegram_link"
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
                        id="contactUsModalClose"
                        data-twe-ripple-color="light"
                    >
                        Close
                    </button>
                    <button
                        type="button"
                        :disabled="loading"
                        @click="updateContactUs"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        {{ loading ? "Loading..." : "Done" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";

export default {
    data() {
        return {
            contactUsLinks: [],
            edit_contact: {
                id: "",
                facebook_link: "",
                viber_number: "",
                phone_number: "",
                telegram_link: "",
            },
            activeField: "",
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getToken"]),
        activeFieldLabel() {
            const labels = {
                facebook_link: "Facebook Link",
                viber_number: "Viber Number",
                phone_number: "Phone Number",
                telegram_link: "Telegram Link",
            };

            return labels[this.activeField] || "Contact Us Link";
        },
        contactRows() {
            return this.contactUsLinks.flatMap((contact) => [
                {
                    key: `${contact.id}-facebook`,
                    label: "Facebook Link",
                    value: contact.facebook_link || "",
                    isLink: true,
                    field: "facebook_link",
                    contact,
                },
                {
                    key: `${contact.id}-viber`,
                    label: "Viber Number",
                    value: contact.viber_number || "",
                    isLink: false,
                    field: "viber_number",
                    contact,
                },
                {
                    key: `${contact.id}-phone`,
                    label: "Phone Number",
                    value: contact.phone_number || "",
                    isLink: false,
                    field: "phone_number",
                    contact,
                },
                {
                    key: `${contact.id}-telegram`,
                    label: "Telegram Link",
                    value: contact.telegram_link || "",
                    isLink: true,
                    field: "telegram_link",
                    contact,
                },
            ]);
        },
    },
    methods: {
        async getContactUsLinks() {
            let url = "/api/contact_us";
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.contactUsLinks = response.data || [];
            }
        },
        setEditContact(row) {
            const contact = row.contact;
            this.activeField = row.field;
            this.edit_contact.id = contact.id;
            this.edit_contact.facebook_link = contact.facebook_link || "";
            this.edit_contact.viber_number = contact.viber_number || "";
            this.edit_contact.phone_number = contact.phone_number || "";
            this.edit_contact.telegram_link = contact.telegram_link || "";
        },
        modalClose() {
            const button = document.getElementById("contactUsModalClose");
            if (button) {
                button.click();
            }
            this.activeField = "";
        },
        async updateContactUs() {
            if (
                !this.edit_contact.id ||
                !this.edit_contact.facebook_link ||
                !this.edit_contact.viber_number ||
                !this.edit_contact.phone_number ||
                !this.edit_contact.telegram_link
            ) {
                return;
            }

            let formData = new FormData();
            formData.append("id", this.edit_contact.id);
            formData.append("facebook_link", this.edit_contact.facebook_link);
            formData.append("viber_number", this.edit_contact.viber_number);
            formData.append("phone_number", this.edit_contact.phone_number);
            formData.append("telegram_link", this.edit_contact.telegram_link);

            let url = "/api/contact_us";
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
                await this.getContactUsLinks();
                this.modalClose();
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.error,
                    type: "error",
                });
            }
        },
    },

    mounted() {
        this.getContactUsLinks();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
