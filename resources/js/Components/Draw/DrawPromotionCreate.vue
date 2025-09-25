<template lang="">
    <div class="frame-container min-h-[100vh] px-12">
        <h1 class="text-xl font-bold mb-6">Draw Promotion Create</h1>
        <div class="flex-auto mb-5">
            <div class="mb-6">
                <label for="opening" class="text-sm mb-3 relative block"
                    >Game Name</label
                >
                <select
                    id="game_types"
                    v-model="game_setting_id"
                    class="w-80 py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none relative"
                >
                    <option value="">Select Game</option>
                    <option
                        v-for="draw in draws"
                        :key="draw.id"
                        :value="draw.id"
                    >
                        {{ draw.name }}
                    </option>
                </select>
            </div>

            <div class="grid grid-cols-4 gap-2">
                <div class="mb-6">
                    <label for="start_date" class="text-sm mb-3 relative block"
                        >From</label
                    >
                    <VueDatePicker
                        v-model="start_date"
                        :is-24="false"
                        auto-apply
                        format="dd/MM/yyyy"
                        placeholder="Start Date"
                    />
                </div>
                <div class="mb-6">
                    <label for="end_date" class="text-sm mb-3 relative block"
                        >To</label
                    >
                    <VueDatePicker
                        v-model="end_date"
                        :is-24="false"
                        auto-apply
                        format="dd/MM/yyyy"
                        placeholder="End Date"
                    />
                </div>
            </div>

            <div class="">
                <span class="text-xl mb-12">Add Promtions</span>

                <div class="flex flex-wrap gap-6 mb-8 mt-8">
                    <div class="flex flex-col">
                        <label class="mb-2 text-sm font-medium"
                            >Ticket Qty</label
                        >
                        <input
                            v-model="new_promotion.qty"
                            type="number"
                            placeholder="Qty"
                            class="border rounded px-4 py-2 w-60"
                        />
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-2 text-sm font-medium"
                            >Additional Ticket</label
                        >
                        <input
                            v-model="new_promotion.additional_qty"
                            type="number"
                            placeholder="Additional Qty"
                            class="border rounded px-4 py-2 w-60"
                        />
                    </div>

                    <div class="flex items-end">
                        <button
                            @click="addPromotion"
                            class="bg-blue-900 text-white px-6 py-2 hover:bg-blue-800"
                        >
                            Add New
                        </button>
                    </div>
                </div>

                <table class="table-auto w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="pb-2">Ticket Qty</th>
                            <th class="pb-2">Additional Tickets</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in lottery_promotion_tickets"
                            :key="index"
                            class="border-b"
                        >
                            <td class="py-3 w-1/3">{{ item.qty }}</td>
                            <td class="py-3 w-1/3">
                                {{ item.additional_qty }}
                            </td>
                            <td class="py-3 w-1/3">
                                <button
                                    @click="removePromotion(item)"
                                    class="text-xl text-gray-600 hover:text-red-500"
                                >
                                    ✖
                                </button>
                                <!-- <button
                                @click="editPrize(item)"
                                class="text-md text-gray-600 hover:text-blue-500 ml-2"
                            >
                                <i class="fal fa-edit"></i>
                            </button> -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button
                @click="updateOrCreateDraw"
                class="bg-blue-900 text-white px-6 py-2 mt-6 hover:bg-blue-800"
            >
                Publish
            </button>
        </div>
    </div>
</template>
<script>
import moment from "moment";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import { mapGetters, mapMutations } from "vuex";
export default {
    data() {
        return {
            start_date: "",
            end_date: "",
            new_promotion: {
                id: "",
                qty: "",
                additional_qty: "",
            },
            lottery_promotion_tickets: [],
            draws: [],
            game_setting_id: "",
        };
    },

    computed: {
        ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
    },

    methods: {
        addPromotion() {
            if (this.new_promotion.qty && this.new_promotion.additional_qty) {
                this.lottery_promotion_tickets.push({
                    id: "",
                    qty: this.new_promotion.qty,
                    additional_qty: this.new_promotion.additional_qty,
                });

                this.new_promotion = {
                    id: "",
                    qty: "",
                    additional_qty: "",
                };
            }
        },
        removePromotion(item) {
            const index = this.lottery_promotion_tickets.findIndex(
                (ticket) => ticket.name === item.name
            );
            //create
            if (index > -1) {
                this.lottery_promotion_tickets.splice(index, 1);
            }
        },
        async getDraws() {
            let url = `/api/draws`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            if (response.data) {
                this.draws = response.data;
            }
        },
        validateForm() {
            if (!this.game_setting_id) return "Game Name is required.";
            if (!this.start_date) return "Start date is required.";
            if (!this.end_date) return "End date is required.";
            if (!this.lottery_promotion_tickets.length)
                return "At least one promotion must be added.";
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
            // if (this.id) {
            //     formData.append("id", this.id);
            // }
            formData.append("game_setting_id", this.game_setting_id);
            formData.append(
                "start_date",
                moment(this.start_date).format("YYYY-MM-DD")
            );
            formData.append(
                "end_date",
                moment(this.end_date).format("YYYY-MM-DD")
            );
            formData.append(
                "lottery_promotion_tickets",
                JSON.stringify(this.lottery_promotion_tickets)
            );

            let url = "/api/lottery_promotions";
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
                setTimeout(() => {
                    window.location.href = "/draw/promotion_lists";
                }, 1000);
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
        this.getDraws();
    },
};
</script>
<style lang=""></style>
