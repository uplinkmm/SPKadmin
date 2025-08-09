<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex justify-between px-4 mb-4">
            <button
                type="button"
                data-twe-toggle="modal"
                data-twe-target="#game_modal"
                data-twe-ripple-init
                data-twe-ripple-color="light"
                class="rounded bg-[#303030] px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:shadow-primary-2 focus:outline-none focus:ring-0"
            >
                Create
            </button>
            <SearchBox class="mr-3" :search-handler="searchHandler" />
        </div>

        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div>
                <p class="font-semibold font-inter text-black mb-3">Games</p>
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
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Active
                                </th>
                                <th scope="col" class="px-6 py-4 border-r">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(game, index) in games"
                                :key="index"
                                class="border-b border-l border-neutral-200"
                            >
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ ++index }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ game.name }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    {{ game.type }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 border-r"
                                >
                                    <label
                                        :for="`toggle${game.id}`"
                                        class="big-checkbox-input"
                                    >
                                        <input
                                            type="checkbox"
                                            :checked="game.is_active"
                                            :id="`toggle${game.id}`"
                                            class="sr-only peer"
                                            @click="
                                                gameToggle(
                                                    game.id,
                                                    !game.is_active
                                                )
                                            "
                                        />
                                        <div
                                            class="checkbox-ui peer peer-focus:outline-none peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white peer-checked:bg-blue-600"
                                        ></div>
                                    </label>
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
                                            edit_game.id = game.id;
                                            edit_game.name = game.name;
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
                        {{ edit_game.id ? "Edit 2D Game" : "Add 2D Game" }}
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
                            >Name</label
                        >
                        <input
                            type="text"
                            placeholder="Amount"
                            v-model="edit_game.name"
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
                        @click="updateOrCreateGame"
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
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        SearchBox,
    },
    data() {
        return {
            games: [],
            edit_game: {
                id: "",
                name: "",
            },
            search_input: "",
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getToken"]),
    },
    methods: {
        async getGames() {
            let url = `/api/games?search_input=${this.search_input}`;

            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.games = response.data;
                console.log(response.data);
            }
        },
        async gameToggle(id, value) {
            const previousState = this.games.find(
                (game) => game.id === id
            ).is_active;
            this.games.find((game) => game.id === id).is_active = value;
            let url = "/api/games/toggle_is_active";
            let formData = new FormData();
            formData.append("id", id);
            formData.append("is_active", value ? 1 : 0);

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
                this.$notify({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                });
                this.games.find((game) => game.id === id).is_active =
                    previousState;
            }
        },
        modalClose() {
            const button = document.getElementById("modalClose");
            if (button) {
                button.click();
            }
        },
        async updateOrCreateGame() {
            if (!this.edit_game.name) {
                return;
            }
            let formData = new FormData();
            if (this.edit_game.id) {
                formData.append("id", this.edit_game.id);
            }
            formData.append("name", this.edit_game.name);
            formData.append("type", "2d");

            let url = "/api/games";
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
                this.getGames();
                this.modalClose();
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
            this.getGames(true);
        },
    },

    mounted() {
        this.getGames();
        initTWE({ Modal, Ripple, Dropdown });
    },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
