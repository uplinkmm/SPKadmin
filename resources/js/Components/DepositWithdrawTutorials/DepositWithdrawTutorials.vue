<template>
    <div class="frame-container min-h-[100vh]">
        <div class="px-4 mb-4">
            <div>
                <p class="font-semibold font-inter text-black mb-3">
                    Deposit & Withdraw Tutorials Links
                </p>
            </div>
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="overflow-hidden">
                    <div class="table-container">
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No.</th>
                                    <th scope="col" class="">Title</th>
                                    <th scope="col" class="">Youtube Link</th>
                                    <th scope="col" class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(tutorial, index) in tutorials"
                                    :key="tutorial.id"
                                    class="border-b border-l border-neutral-200"
                                >
                                    <td class="whitespace-nowrap">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ tutorial.title || "-" }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <a
                                            :href="tutorial.youtube_link"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-blue-600 underline"
                                        >
                                            {{ tutorial.youtube_link }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button
                                            class="mr-3 px-2 py-4"
                                            type="button"
                                            data-twe-toggle="modal"
                                            data-twe-target="#tutorial_modal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                            @click="setEditTutorial(tutorial)"
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
        id="tutorial_modal"
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
                        {{
                            edit_tutorial.id ? "Edit Tutorial" : "Add Tutorial"
                        }}
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
                    <div class="mb-6">
                        <label class="text-sm mb-3 relative block">Title</label>
                        <input
                            type="text"
                            placeholder="Title"
                            v-model="edit_tutorial.title"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>

                    <div class="mb-6">
                        <label class="text-sm mb-3 relative block"
                            >Youtube Link</label
                        >
                        <input
                            type="text"
                            placeholder="https://www.youtube.com/..."
                            v-model="edit_tutorial.youtube_link"
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
                        id="tutorialModalClose"
                        data-twe-ripple-color="light"
                    >
                        Close
                    </button>
                    <button
                        type="button"
                        :disabled="loading"
                        @click="updateOrCreateTutorial"
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
            tutorials: [],
            edit_tutorial: {
                id: "",
                title: "",
                youtube_link: "",
            },
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getToken"]),
    },
    methods: {
        resetForm() {
            this.edit_tutorial.id = "";
            this.edit_tutorial.title = "";
            this.edit_tutorial.youtube_link = "";
        },
        setEditTutorial(tutorial) {
            this.edit_tutorial.id = tutorial.id;
            this.edit_tutorial.title = tutorial.title || "";
            this.edit_tutorial.youtube_link = tutorial.youtube_link || "";
        },
        async getTutorials() {
            let url = `/api/deposit_withdraw_tutorials`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });

            if (response.data) {
                this.tutorials = response.data || [];
            }
        },
        modalClose() {
            const button = document.getElementById("tutorialModalClose");
            if (button) {
                button.click();
            }
        },
        async updateOrCreateTutorial() {
            if (!this.edit_tutorial.youtube_link) {
                return;
            }

            let formData = new FormData();
            if (this.edit_tutorial.id) {
                formData.append("id", this.edit_tutorial.id);
            }
            formData.append("title", this.edit_tutorial.title || "");
            formData.append("youtube_link", this.edit_tutorial.youtube_link);

            let url = "/api/deposit_withdraw_tutorials";
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
                await this.getTutorials();
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
        this.getTutorials();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
