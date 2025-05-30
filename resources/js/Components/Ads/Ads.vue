<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <div>
                <p class="font-semibold font-inter text-black mb-3">
                    Ads & Promotion
                </p>
            </div>
            <div>
                <button
                    @click="
                        edit_ads.id = '';
                        edit_ads.name = '';
                        edit_ads.body = '';
                        edit_ads.photo = {};
                        icon_preview = '';
                        this.$refs.iconFile.value = null;
                    "
                    type="button"
                    data-twe-toggle="modal"
                    data-twe-target="#game_modal"
                    data-twe-ripple-init
                    data-twe-ripple-color="light"
                    class="rounded bg-[#303030] px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:shadow-primary-2 focus:outline-none focus:ring-0"
                >
                    Create
                </button>
                <SearchBox class="m-6" :search-handler="searchHandler" />
            </div>
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="overflow-x-auto">
                <div class="overflow-hidden">
                    <SelectionPaginationCount
                        :handleChange="(value) => (per_page=value, getAds(true))"
                        :initialValue="per_page"
                    />
                    <div class="table-container">
                        <table class="">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="">No.</th>
                                    <th scope="col" class="">Photo</th>
                                    <th scope="col" class="">Name</th>
                                    <th scope="col" class="">Type</th>
                                    <th scope="col" class="">Body</th>
                                    <th scope="col" class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(ads, index) in adses"
                                    :key="index"
                                    class="border-b border-l border-neutral-200"
                                >
                                    <td class="whitespace-nowrap">
                                        {{ ++index + (currentPage - 1) * per_page }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div>
                                            <img
                                                class="h-14"
                                                :src="ads.photo"
                                                alt=""
                                            />
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ads.name }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ads.type }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{ ads?.body }}
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <button
                                            class="mr-3 px-2 py-4"
                                            type="button"
                                            data-twe-toggle="modal"
                                            data-twe-target="#game_modal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
                                            @click="
                                                edit_ads.id = ads.id;
                                                edit_ads.name = ads.name;
                                                edit_ads.body = ads.body
                                                    ? ads.body
                                                    : '';
                                                icon_preview = ads.photo;
                                            "
                                        >
                                            <i class="fal fa-edit"></i>
                                        </button>
                                        <button
                                            @click="delete_ads.id = ads.id;delete_ads.type = ads.type"
                                            data-twe-toggle="modal"
                                            data-twe-target="#ads_delete_confirm"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light"
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
                                getAds(false);
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ads Create -->
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
                        {{ edit_ads.id ? `Edit ${type}` : `Add ${type}` }}
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
                            >Type</label
                        >
                        <select
                            id="2d_games"
                            v-model="type"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                        >
                            <option value="ads">Ads</option>
                            <option value="promotion">Promotion</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="" class="text-sm mb-3 relative block"
                            >Name</label
                        >
                        <input
                            type="text"
                            placeholder="Name"
                            v-model="edit_ads.name"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div v-if="type == 'promotion'" class="mb-6">
                        <label for="" class="text-sm mb-3 relative block"
                            >Body</label
                        >
                        <input
                            type="text"
                            placeholder="Body"
                            v-model="edit_ads.body"
                            class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-6">
                        <label for="" class="text-sm mb-3 relative block"
                            >Photo</label
                        >
                        <div>
                            <input
                                @change="onChange"
                                type="file"
                                class="form-control mt-3"
                                placeholder=""
                                ref="iconFile"
                                accept=".png, .gif, .jpeg, .jpg, .webp, .PNG, .JPG"
                                value=""
                            />
                            <img-preview
                                class="relative"
                                :img-preview="icon_preview"
                                :type="'icon'"
                                :delete-image="deleteImage"
                            />
                        </div>
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
                        @click="updateOrCreateAds"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                    >
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
      <!-- Ads Delete Confirm Modal -->
      <div
            data-twe-modal-init
            class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
            id="ads_delete_confirm"
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
                            <p
                                class="text-lg font-bold relative block"
                            >Are you sure you want to delete this {{ delete_ads.type }}?</p>
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
                            id="modalCloseConfirm"
                            data-twe-ripple-color="light"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            @click="deleteAds"
                            class="rounded bg-red-600 px-8 pb-2 pt-2.5 text-xs text-white hover:bg-red-700 focus:outline-none focus:ring-0 active:bg-red-800"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters, mapMutations } from "vuex";
import { getApiData, postApiData,deleteApiData } from "../../utilities/ajax-helpers";
import SearchBox from "../Common/SearchBox.vue";
import ImgPreview from "./imgPreview.vue";
import WebPagination from "../Common/webPagination.vue";
import SelectionPaginationCount from "../Common/SelectionPaginationCount.vue";

export default {
    components: {
        SearchBox,
        ImgPreview,
        WebPagination,
        SelectionPaginationCount,
    },
    data() {
        return {
            adses: [],
            edit_ads: {
                id: "",
                name: "",
                photo: {},
                body: "",
            },
            search_input: "",
            icon_preview: "",
            per_page: 50,
            type: "ads",
            delete_ads:{
                id:null,
                type:"ads"
            }
        };
    },
    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),

        onChange(event) {
            this.edit_ads.photo = {
                name: event.target.files[0].name,
                data: event.target.files[0],
            };
            this.icon_preview = URL.createObjectURL(event.target.files[0]);
            console.log("icon", this.icon_preview);
        },
        deleteImage() {
            this.edit_ads.photo = {};
            this.icon_preview = null;
            this.$refs.iconFile.value = null;
        },
        async getAds(reset_page) {
            if (reset_page) {
                this.setCurrentPage(1);
            }
            let url = `/api/ads?search_input=${this.search_input}&page=${
                this.currentPage
            }${this.per_page ? `&per_page=${this.per_page}` : ""}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.adses = response.data.data;
                this.setTotalCount(response.data.total);
            }
        },
        modalClose(id) {
            const button = document.getElementById(id);
            if (button) {
                button.click();
            }
        },
        async updateOrCreateAds() {
            if (!this.edit_ads.name) {
                return;
            }
            let formData = new FormData();
            if (this.edit_ads.id) {
                formData.append("id", this.edit_ads.id);
            }
            if (this.type == "promotion") {
                formData.append("body", this.edit_ads.body);
            }
            formData.append("type", this.type);
            formData.append("name", this.edit_ads.name);
            if (Object.keys(this.edit_ads.photo).length > 0) {
                formData.append(
                    "photo",
                    this.edit_ads.photo.data,
                    this.edit_ads.photo.name
                );
            }

            let url = "/api/ads";
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
                this.getAds();
                this.modalClose("modalClose");
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.error,
                    type: "error",
                });
            }
        },
        async deleteAds() {
            let url = `/api/ads/${this.delete_ads.id}`;
            let response = await deleteApiData({
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
                this.delete_ads.id = null;
                this.delete_ads.type = "ads";
                this.getAds();
                this.modalClose("modalCloseConfirm");
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.error,
                    type: "error",
                });
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getAds(true);
        },
    },

    mounted() {
        this.getAds();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
