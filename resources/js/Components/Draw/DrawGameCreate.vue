<template>
    <div class="frame-container min-h-[100vh] px-12">
        <h1 class="text-xl font-bold mb-6">DrawGameCreate</h1>
        <div class="flex-auto mb-5">
            <div class="flex gap-6">
                <div class="mb-6">
                    <label for="opening" class="text-sm mb-3 relative block"
                        >Game Name</label
                    >
                    <input
                        type="text"
                        v-model="name"
                        placeholder="Game Name"
                        class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                    />
                </div>
                <div class="mb-6">
                    <label for="opening" class="text-sm mb-3 relative block"
                        >Price</label
                    >
                    <input
                        type="number"
                        v-model="price"
                        placeholder="Price"
                        class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                    />
                </div>
                <div class="mb-6">
                    <label for="" class="text-sm mb-1 relative block"
                        >Photo</label
                    >
                    <div class="grid grid-cols-2 items-start">
                        <input
                            @change="onChangePhoto"
                            type="file"
                            class="form-control mt-3"
                            placeholder=""
                            ref="photoFile"
                            accept=".png, .gif, .jpeg, .jpg, .webp, .PNG, .JPG"
                            value=""
                        />
                        <img-preview
                            class="relative -mt-6 pb-18"
                            :img-preview="photo_preview"
                            :type="'icon'"
                            :delete-image="deletePhoto"
                        />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-2">
                <div class="mb-6">
                    <label
                        for="opening_date_time"
                        class="text-sm mb-3 relative block"
                        >Opening Date & Time</label
                    >
                    <VueDatePicker
                        v-model="opening_date_time"
                        :is-24="false"
                        auto-apply
                        format="dd/MM/yyyy hh:mm a"
                        placeholder="Opening Date & Time"
                    />
                </div>
                <div class="mb-6">
                    <label
                        for="closing_date_time"
                        class="text-sm mb-3 relative block"
                        >Closing Date & Time</label
                    >
                    <VueDatePicker
                        v-model="closing_date_time"
                        :is-24="false"
                        auto-apply
                        format="dd/MM/yyyy hh:mm a"
                        placeholder="Closing Date & Time"
                    />
                </div>
                <div class="mb-6">
                    <label
                        for="lottery_date_time"
                        class="text-sm mb-3 relative block"
                        >Lottery Date Time</label
                    >
                    <VueDatePicker
                        v-model="lottery_date_time"
                        :is-24="false"
                        auto-apply
                        format="dd/MM/yyyy hh:mm a"
                        placeholder="Lottery Date Time"
                    />
                </div>
                <div class="mb-6">
                    <label
                        for="limitation_quantity"
                        class="text-sm mb-3 relative block"
                        >Ticket Qty</label
                    >
                    <input
                        type="text"
                        v-model="limitation_quantity"
                        id="limitation_quantity"
                        placeholder="Ticket Qty"
                        class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                    />
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="text-sm mb-3 relative block"
                    >Description</label
                >
                <textarea
                    v-model="description"
                    rows="4"
                    cols="50"
                    placeholder="Description"
                    class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                />
            </div>
            <div class="mb-6">
                <label
                    for="terms_and_condition"
                    class="text-sm mb-3 relative block"
                    >Terms and Conditions</label
                >
                <textarea
                    v-model="terms_and_condition"
                    rows="4"
                    cols="50"
                    placeholder="Terms and Conditions"
                    class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                />
            </div>
        </div>

        <div class="">
            <span class="text-xl mb-12">Add Prizes</span>

            <div class="flex flex-wrap gap-6 mb-8 mt-8">
                <div class="flex flex-col">
                    <label class="mb-2 text-sm font-medium">Prize Name</label>
                    <input
                        v-model="new_prize.name"
                        type="text"
                        placeholder="Prize Name"
                        class="border rounded px-4 py-2 w-60"
                    />
                </div>

                <div class="flex flex-col">
                    <label class="mb-2 text-sm font-medium">Prize</label>
                    <input
                        v-model="new_prize.prize"
                        type="text"
                        placeholder="Prize"
                        class="border rounded px-4 py-2 w-60"
                    />
                </div>

                <div class="flex flex-col">
                    <label class="mb-2 text-sm font-medium">Image</label>
                    <input
                        type="file"
                        multiple
                        @change="handleprizeImageUpload"
                        class="px-4 py-2 w-60"
                        ref="iconFile"
                    />
                </div>

                <div class="flex items-end">
                    <button
                        @click="addPrize"
                        class="bg-blue-900 text-white px-6 py-2 hover:bg-blue-800"
                    >
                        {{ new_prize.id ? "Update" : "Add New" }}
                    </button>
                </div>
            </div>

            <table class="table-auto w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="pb-2">Prize Name</th>
                        <th class="pb-2">Prize</th>
                        <th class="pb-2">Image</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item, index) in prizes.filter(
                            (prize) => prize.is_delete === 0
                        )"
                        :key="index"
                        class="border-b"
                    >
                        <td class="py-3">{{ item.name }}</td>
                        <td class="py-3">{{ item.prize }}</td>
                        <td class="py-3 flex items-center gap-2">
                            <img
                                v-for="(
                                    photo, photoIndex
                                ) in item.photo_previews"
                                :key="photoIndex"
                                alt="w-16"
                                :src="photo"
                                class="w-24"
                            />
                            <button
                                @click="removePrize(item)"
                                class="text-xl text-gray-600 hover:text-red-500"
                            >
                                ✖
                            </button>
                            <button
                                @click="editPrize(item)"
                                class="text-md text-gray-600 hover:text-blue-500 ml-2"
                            >
                                <i class="fal fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button
            @click="updateOrCreateDraw"
            :disabled="loading"
            class="bg-blue-900 disabled:bg-blue-400 text-white px-6 py-2 mt-6 hover:bg-blue-800"
        >
            {{ loading ? "Publishing..." : "Publish" }}
        </button>
    </div>
</template>

<script>
import ImgPreview from "../Ads/imgPreview.vue";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import { mapGetters } from "vuex";
import moment from "moment";
export default {
    components: {
        ImgPreview,
    },
    data() {
        return {
            id: "",
            name: "",
            photo: {},
            photo_preview: "",
            opening_date_time: "",
            closing_date_time: "",
            lottery_date_time: "",
            limitation_quantity: "",
            price: "",
            description: "",
            terms_and_condition: "",
            new_prize: {
                id: "",
                name: "",
                prize: "",
                photos: [],
                photo_previews: [],
                photo_names: [],
                is_delete: 0,
            },
            prizes: [],
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getToken"]),
    },
    methods: {
        onChangePhoto(event) {
            this.photo = {
                name: event.target.files[0].name,
                data: event.target.files[0],
            };
            this.photo_preview = URL.createObjectURL(event.target.files[0]);
            console.log("icon", this.photo_preview);
        },
        deletePhoto() {
            this.photo = {};
            this.photo_preview = null;
            this.$refs.photoFile.value = null;
        },
        editPrize(item) {
            this.new_prize = JSON.parse(
                JSON.stringify({
                    id: item.id,
                    name: item.name,
                    prize: item.prize,
                    photos: [],
                    photo_previews: [],
                    photo_names: [],
                    is_delete: 0,
                })
            );
        },
        handleprizeImageUpload(event) {
            const selectedFiles = event.target.files;
            for (let i = 0; i < selectedFiles.length; i++) {
                this.new_prize.photos.push({
                    name: selectedFiles[i].name,
                    data: selectedFiles[i],
                });
                this.new_prize.photo_names.push(selectedFiles[i].name);
                this.new_prize.photo_previews.push(
                    URL.createObjectURL(selectedFiles[i])
                );
            }
        },
        addPrize() {
            if (this.new_prize.name && this.new_prize.prize) {
                if (this.new_prize.id) {
                    const index = this.prizes.findIndex(
                        (prize) => prize.id === this.new_prize.id
                    );
                    if (index > -1) {
                        this.prizes[index] = {
                            id: this.new_prize.id,
                            name: this.new_prize.name,
                            prize: this.new_prize.prize,
                            photos: this.new_prize.photos.length
                                ? this.new_prize.photos
                                : this.prizes[index].photos,
                            photo_names: this.new_prize.photos.length
                                ? this.new_prize.photo_names
                                : this.prizes[index].photo_names,
                            photo_previews: this.new_prize.photos.length
                                ? this.new_prize.photo_previews
                                : this.prizes[index].photo_previews,
                            is_delete: 0,
                        };
                    }
                } else {
                    this.prizes.push({
                        id: this.new_prize.id,
                        name: this.new_prize.name,
                        prize: this.new_prize.prize,
                        photos: this.new_prize.photos,
                        photo_names: this.new_prize.photo_names,
                        photo_previews: this.new_prize.photo_previews,
                        is_delete: 0,
                    });
                }
                this.new_prize = {
                    id: "",
                    name: "",
                    prize: "",
                    photos: [],
                    photo_names: [],
                    photo_previews: [],
                    is_delete: 0,
                };
                this.$refs.iconFile.value = null;
            }
        },
        removePrize(item) {
            const index = this.prizes.findIndex(
                (prize) => prize.name === item.name
            );
            if (this.id) {
                //edit

                if (this.prizes[index].id) {
                    //old prize
                    this.prizes[index].is_delete = 1;
                } else {
                    this.prizes.splice(index, 1); //new prize
                }
            } else {
                //create
                if (index > -1) {
                    this.prizes.splice(index, 1);
                }
            }
        },
        validateForm() {
            if (!this.name.trim()) return "Game Name is required.";
            if (!this.price) return "Price is required.";
            if (!this.photo?.data && !this.id) return "Main photo is required.";
            if (!this.opening_date_time)
                return "Opening date/time is required.";
            if (!this.closing_date_time)
                return "Closing date/time is required.";
            if (!this.lottery_date_time)
                return "Lottery date/time is required.";
            if (!this.limitation_quantity)
                return "Ticket quantity is required.";
            if (!this.description.trim()) return "Description is required.";
            if (!this.terms_and_condition.trim())
                return "Terms & Conditions are required.";
            if (!this.prizes.length) return "At least one prize must be added.";

            return null;
        },
        async updateOrCreateDraw() {
            const error = this.validateForm();
            if (error) {
                this.$notify({
                    title: "Error!",
                    text: error,
                    type: "error",
                });
                return;
            }
            let formData = new FormData();
            if (this.id) {
                formData.append("id", this.id);
            }
            formData.append("name", this.name);
            formData.append("price", this.price);
            formData.append(
                "opening_date_time",
                moment(this.opening_date_time).format("YYYY-MM-DD HH:mm")
            );
            formData.append(
                "closing_date_time",
                moment(this.closing_date_time).format("YYYY-MM-DD HH:mm")
            );
            formData.append(
                "lottery_date_time",
                moment(this.lottery_date_time).format("YYYY-MM-DD HH:mm")
            );
            formData.append("limitation_quantity", this.limitation_quantity);
            formData.append("description", this.description);
            formData.append("terms_and_condition", this.terms_and_condition);
            let temp_prizes = this.prizes.map((prize) => ({
                id: prize.id,
                name: prize.name,
                prize: prize.prize,
                photo: prize.photo_names,
                is_delete: prize.is_delete,
            }));
            formData.append("prizes", JSON.stringify(temp_prizes));

            if (Object.keys(this.photo).length > 0) {
                formData.append("photo", this.photo.data, this.photo.name);
            }
            this.prizes.forEach((prize) => {
                prize.photos.forEach((photo) => {
                    if (Object.keys(photo).length > 0) {
                        formData.append(
                            "prize_photos[]",
                            photo.data,
                            photo.name
                        );
                    }
                });
            });

            let url = "/api/draws";
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
                setTimeout(() => {
                    window.location.href = "/draw/game_lists";
                }, 100);
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                });
            }
        },
        async getDrawDetail() {
            let url = `/api/draws/${this.id}`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                const res = response.data;
                this.name = res.name;
                this.price = res.price;
                this.photo_preview = res.photo;
                this.opening_date_time = res.opening_date_time;
                this.closing_date_time = res.closing_date_time;
                this.lottery_date_time = res.lottery_date_time;
                this.limitation_quantity = res.limitation_quantity;
                this.description = res.description;
                this.terms_and_condition = res.terms_and_condition;
                this.prizes = res.prizes.map((prize) => ({
                    id: prize.id,
                    name: prize.name,
                    prize: prize.prize,
                    photos: [],
                    photo_names: [],
                    photo_previews: prize.prizes_images.map(
                        (img) => window.location.origin + img.name
                    ),
                    is_delete: 0,
                }));
            }
        },
    },
    mounted() {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get("id");
        if (id) {
            this.id = id;
            this.getDrawDetail();
            console.log("Draw ID:", id);
        }
    },
};
</script>
