<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4"></div>

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
                                v-for="(term, index) in terms"
                                :key="index"
                                class="border-b border-l border-neutral-200"
                            >
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ index + 1 }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                    v-html="term.name"
                                ></td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <button
                                        class="mr-3 px-2 py-4"
                                        @click="editTerm(term)"
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
        v-if="showModal"
        class="fixed left-0 top-0 z-50 h-full w-full bg-black bg-opacity-50 flex items-center justify-center"
    >
        <div class="bg-white rounded-md w-1/2 p-6">
            <div class="flex justify-between border-b pb-2">
                <h5 class="text-xl font-medium">Terms & Conditions</h5>
                <button
                    @click="closeModal"
                    class="text-neutral-500 hover:text-neutral-800"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <label class="text-sm mb-3 block">Text</label>
                <Editor
                    api-key="mqk2hs4swmih6nep3v1lx11z6fqd90px19viek3qlfbe07vz"
                    v-model="edit_term.name"
                    :init="editorConfig"
                />
            </div>
            <div class="flex justify-end border-t pt-2 gap-4">
                <button
                    @click="closeModal"
                    class="px-6 py-2 bg-gray-200 rounded"
                >
                    Close
                </button>
                <button
                    :disabled="loading"
                    @click="updateTerms"
                    class="px-8 py-2 bg-blue-600 text-white rounded"
                >
                    {{ loading ? "Loading..." : "Done" }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from "@tinymce/tinymce-vue";
import { mapGetters } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";

export default {
    components: {
        Editor,
    },
    data() {
        return {
            terms: [],
            edit_term: { id: "", name: "" },
            showModal: false,
            editorConfig: {
                menubar: false,
                plugins: "lists link image table code",
                toolbar:
                    "undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image",
            },
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getToken"]),
    },
    methods: {
        async getTerms() {
            let response = await getApiData({
                url: "/api/term_and_conditions",
                token: this.getToken,
            });
            if (response.data) this.terms = response.data;
        },
        editTerm(term) {
            this.edit_term = { ...term };
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        async updateTerms() {
            if (!this.edit_term.name) return;
            let formData = new FormData();
            if (this.edit_term.id) formData.append("id", this.edit_term.id);
            formData.append("name", this.edit_term.name);
            this.loading = true;
            let response = await postApiData({
                url: "/api/term_and_conditions",
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
                this.getTerms();
                this.closeModal();
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
    },
};
</script>

<style>
.table-container {
    overflow-x: auto;
}
</style>
