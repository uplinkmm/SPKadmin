<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <!-- <button
                type="button"
                data-twe-toggle="modal"
                data-twe-target="#game_modal"
                data-twe-ripple-init
                data-twe-ripple-color="light"
                class="rounded bg-[#303030] px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:shadow-primary-2 focus:outline-none focus:ring-0"
            >
                Create
            </button> -->
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div>
                <p class="font-semibold font-inter text-black mb-3">
                    Terms & Conditions
                </p>
            </div>
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table
                        class="min-w-full text-left text-sm font-inter text-black"
                    >
                        <thead
                            class="border-b border-t border-l border-neutral-200 font-medium"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-4 border-r">
                                    No.
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Text
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(game, index) in terms"
                                :key="index"
                                class="border-b border-l border-neutral-200"
                            >
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ ++index }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r" v-html="game.name"
                                >
                                </td>

                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        type="button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#game_modal"
                                        data-twe-ripple-init
                                        data-twe-ripple-color="light"
                                        @click="
                                            edit_term.id = game.id;
                                            edit_term.name = game.name;
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
    </div>

    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="game_modal"
        tabindex="-1"
        aria-labelledby="ModalLabel"
        aria-hidden="true"
    >
        <div
            data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[800px]"
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
                        Terms & Conditions
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
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6">
                        <label for="" class="text-sm mb-3 relative block"
                            >Text</label
                        >
                        <ckeditor
                            v-model="edit_term.name"
                            :editor="editor"
                            :config="editorConfig"
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
                        @click="updateTerms"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        Done
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
import {
    ClassicEditor,
    Essentials,
    Heading,
    Mention,
    Paragraph,
    Undo,
    BlockQuote,
    Bold,
    Italic,
    Font,
    Link,
    List,
} from "ckeditor5";

import "ckeditor5/ckeditor5.css";
export default {
    data() {
        return {
            terms: [],
            edit_term: {
                id: "",
                name:  "",
            },
            editor: ClassicEditor,
            editorConfig: {
                plugins: [
                    Bold,
                    Heading,
                    BlockQuote,
                    Font,
                    Link,
                    List,
                    Essentials,
                    Italic,
                    Mention,
                    Paragraph,
                    Undo,
                ],
                menuBar: {
                    isVisible: true,
                },

                toolbar: {
                    items: [
                        "undo",
                        "redo",
                        "|",
                        "heading",
                        "|",
                        "fontfamily",
                        "fontsize",
                        "fontColor",
                        "fontBackgroundColor",
                        "|",
                        "bold",
                        "italic",
                        "strikethrough",
                        "subscript",
                        "superscript",
                        "code",
                        "|",
                        "link",
                        "uploadImage",
                        "blockQuote",
                        "codeBlock",
                        "|",
                        "bulletedList",
                        "numberedList",
                        "todoList",
                        "outdent",
                        "indent",
                    ],
                    shouldNotGroupWhenFull: false,
                },
            },
        };
    },
    computed: {
        ...mapGetters(["getToken"]),
    },
    methods: {
        async getTerms() {
            let url = `/api/term_and_conditions`;

            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.terms = response.data;
            }
        },
        modalClose() {
            const button = document.getElementById("modalClose");
            if (button) {
                button.click();
            }
        },
        async updateTerms() {
            if (!this.edit_term.name) {
                return;
            }
            let formData = new FormData();
            if (this.edit_term.id) {
                formData.append("id", this.edit_term.id);
            }
            formData.append("name", this.edit_term.name);

            let url = "/api/term_and_conditions";
            let response = await postApiData({
                url: url,
                form_data: formData,
                token: this.getToken,
            });
            if (response.success) {
                this.$notify({
                    title: "Success!",
                    text: response.message,
                    type: "info",
                });
                this.getTerms();
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
        this.getTerms();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
