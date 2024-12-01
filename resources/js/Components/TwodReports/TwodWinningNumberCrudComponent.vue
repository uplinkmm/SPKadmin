<template>
    <div class="frame-container min-h-[100vh]">
        <div class="flex flex-col lg:flex-row gap-y-4 justify-between px-4 mb-4">
            <div class="flex">
                <VueDatePicker
                    v-model="from_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="From"
                    @update:model-value="getNumberList(true)"
                    format="dd/MM/yyyy"

                ></VueDatePicker>
                <VueDatePicker
                    v-model="to_date"
                    :enable-time-picker="false"
                    auto-apply
                    class="mr-3"
                    placeholder="To"
                    @update:model-value="getNumberList(true)"
                    format="dd/MM/yyyy"
                ></VueDatePicker>
            </div>
            <div class="flex gap-x-4 justify-between lg:justify-end">
                <SearchBox class="mr-3 " :search-handler="searchHandler" />
                <button type="button"
                    class="add-btn !bg-[#303030] focus:outline-none focus:ring-0 mr-3"
                    data-twe-toggle="modal"
                    data-twe-target="#handleModal"
                    data-twe-ripple-init
                    data-twe-ripple-color="light">
                    Add 2D Result
                </button>
            </div>

        </div>
        <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
            <div class="">
                <div class="flex items-center mb-4">
                    <label for="itemsPerPage" class="mr-2 text-gray-700">Show</label>
                    <select id="itemsPerPage" @change="getNumberList(true)" v-model="per_page" class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500">
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="50000">All</option>
                    </select>
                </div>
                <div class="">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">2D</th>
                                    <th scope="col">Date</th>
                                    <th scope="col" class="">Time</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(num,index) in numberList">
                                    <td class="whitespace-nowrap font-medium">
                                        {{ per_page * (currentPage - 1) + (++index) }}
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <p class="text-lg">
                                            {{ num.number }}
                                        </p>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        {{  formatDate(num.date_time) }}
                                    </td>
                                

                                     <td class="whitespace-nowrap">
                                        <button :class="num.time_status == 'evening' ? 'bg-[#f3b01a]' : 'bg-[#2cb12c]'" class="rounded  px-4 pb-1 pt-1 text-xs text-white w-fit mx-auto">
                                            {{ formatTime(num.game_setting.lottery_time) }}
                                        </button>
                                      </td>
                                      <td class="whitespace-nowrap text-left"> 
                                        <div v-if="num.is_approved ==0" class="">
                                            <button type="button"
                                                class="approve-btn bg-yellow-500" @click="getApprovement(num.id)"
                                                data-twe-toggle="modal"
                                                data-twe-target="#approvingModal"
                                                data-twe-ripple-init
                                                data-twe-ripple-color="light"
                                                >
                                                Pending
                                            </button>
                                        </div>
                                        <div v-else>
                                            <button type="button"
                                                class="approve-btn px-1 py-1">
                                                Approved
                                            </button>
                                        </div>
                                   
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <button @click="btnClickEditNumber(num)" :disabled="num.is_approved == 1" :class="num.is_approved == 1 ? 'cursor-not-allowed' : 'opacity-100 cursor-pointer'"
                                            class="mr-2 px-3 py-1"
                                            type="button"
                                            data-twe-toggle="modal"
                                            data-twe-target="#editModal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light">
                                            <i class="fal fa-edit"></i>
                                        </button>
                                        <!-- <button :class="num.is_approved == 1 ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer'" :disabled="num.is_approved == 1"
                                            type="button" @click="getApprovement(num.id)"
                                            class="px-3 py-1"
                                            data-twe-toggle="modal"
                                            data-twe-target="#approvingModal"
                                            data-twe-ripple-init
                                            data-twe-ripple-color="light">
                                            <i class="fas fa-check-circle"></i>
                                        </button> -->
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6"  v-if="getTotalCount>per_page">
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
                                getNumberList(false);
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="handleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none">
                <div
                    class="modal-header-box ">
                    <h5 class="modal-header-title" id="exampleModalLabel">
                        Add 2D Result
                    </h5>
                    <button type="button" id="closeTwodResult"
                        class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-twe-modal-dismiss aria-label="Close">
                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="mb-6">
                        <label for="2d" class="text-sm mb-3 relative block">2d</label>
                        <input type="text" v-model="number" id="2d" placeholder="2d"
                        class=" block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none">
                    </div>
                    <div>
                        <label class="label-form mb-3">Lottery Time</label>
                        <select name="" id="" class="w-full text-sm py-2.5 px-3 bg-white border-gray-400 border rounded-md" v-model="gameSetting">
                            <option :value="gameSetting" v-for="(gameSetting) in gameSettings">
                                {{ formatTime(gameSetting.lottery_time) }}
                            </option>
                        </select>
                    </div>


                </div>

                <!-- Modal footer -->
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4">
                    <button type="button"
                        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black  focus:outline-none focus:ring-00  "
                        data-twe-modal-dismiss data-twe-ripple-init data-twe-ripple-color="light">
                        Close
                    </button>
                    <button type="button" @click="addWinningNumber()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white
                        hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        data-twe-modal-dismiss>
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4 ">
                    <h5 class="text-xl font-medium leading-normal text-surface " id="editModalLabel">
                        Edit 2D Result
                    </h5>
                    <button type="button" id="closeEditModal"
                        class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-twe-modal-dismiss aria-label="Close">
                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="relative flex-auto p-4" data-twe-modal-body-ref>
                    <div class="relative pb-6">
                        <label for="edit_2d" class="text-sm mb-3 relative block">2D Number</label>
                        <input type="number" id="edit_2d" placeholder="2D Number" v-model="newNumber" max="99"
                        class=" block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none">
                        <span class="text-xs text-red-600 absolute bottom-0" v-if="isEditNumber">Please Add 2D Number</span>
                    </div>

                </div>
                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4">
                    <button type="button"
                        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black  focus:outline-none focus:ring-00  "
                        data-twe-modal-dismiss data-twe-ripple-init data-twe-ripple-color="light">
                        Close
                    </button>
                    <button type="button" @click="confirmEditNumber()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white
                        hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        data-twe-modal-dismiss>
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="approvingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-twe-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none">
                <div
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4 ">
                    <h5 class="text-xl font-medium leading-normal text-surface " id="exampleModalLabel">
                        Confirm Result
                    </h5>
                    <button type="button" id="close"
                        class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-twe-modal-dismiss aria-label="Close">
                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>

                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4">
                    <button type="button"
                        class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black  focus:outline-none focus:ring-00  "
                        data-twe-modal-dismiss data-twe-ripple-init data-twe-ripple-color="light">
                        Close
                    </button>
                    <button type="button" @click="confirmApprovement()"
                        class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white
                        hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                        data-twe-modal-dismiss>
                        Approve
                    </button>
                </div>
            </div>
        </div>
    </div>



</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters,mapMutations } from 'vuex';
import { getApiData, postApiData } from '../../utilities/ajax-helpers';
import { convertToFriendlyDateTime, getCurrentDate } from '../../utilities/datetime-helpers';
import moment from "moment";
import WebPagination from "../Common/webPagination.vue";
import SearchBox from "../Common/SearchBox.vue";

export default {
    components: {
        WebPagination,
        SearchBox
    },
    data() {
        return {
            numberList:null,
            gameSetting:null,
            number:null,
            approveId:null,
            editNumber:null,
            newNumber:null,
            isEditNumber:false,

            gameSettings: [],
            from_date: moment(),
            to_date:moment(),
            per_page: 50,
            search_input:""

        }
    },
    computed: {
        ...mapGetters(["getToken","getTotalCount", "currentPage"]),

        fromDate() {
            if (this.from_date != "") {
                return moment(this.from_date).format("YYYY-MM-DD");
            } else {
                return "";
            }
        },
        toDate() {
            if (this.to_date != "") {
                return moment(this.to_date).format("YYYY-MM-DD");
            } else {
                return "";
            }
        },
    },
    methods: {
        ...mapMutations(["setTotalCount", "setCurrentPage"]),

        async getGameSettings(){
            let url = '/api/2d/game_settings';
            let response = await getApiData({url: url, token: this.getToken});
            if(response.data){
                this.gameSettings = response.data;
            }
        },

        async getNumberList(reset_page){
            if(reset_page){
                this.setCurrentPage(1);
            }
            let url = `/api/2d/betting_wins?from_date=${this.fromDate}&to_date=${this.toDate}&page=${this.currentPage}${this.per_page ? `&per_page=${this.per_page}` : ""}&search_input=${this.search_input}`;

            let response = await getApiData({url: url, token: this.getToken});
            if(response.data){
                this.numberList = response.data.data;
                this.setTotalCount(response.data.last_page * response.data.per_page);
            }
        },
        async addWinningNumber(){
            let formData = new FormData();
            formData.append('number', this.number);
            formData.append('game_setting_id', this.gameSetting.id);
            let url = '/api/2d/betting_wins';
            let response = await postApiData({url: url, form_data: formData, token: this.getToken});
            if(response.success){
                console.log('number added')
                this.getNumberList(false);
                document.getElementById("closeTwodResult").click();
                this.number = null;
                this.gameSetting = null;
            }
            else{
                this.$notify({
                    text: response.message,
                    type: "error"
                });
            }
        },
        btnClickEditNumber(num){
            this.editNumber = num;
            this.newNumber = num.number;
        },
        async confirmEditNumber(){
            let formData = new FormData();
            formData.append('number', this.newNumber);
            let url = '/api/2d/betting_wins/'+this.editNumber.id;
            if(this.newNumber){
                let response = await postApiData({url: url,form_data: formData, token: this.getToken});
                if(response.success){
                    this.getNumberList(false);
                    this.newNumber = null;
                    document.getElementById("closeEditModal").click();
                }
                else{
                    this.$notify({
                        text: response.message,
                        type: "error"
                    });
                }
            }
            else{
                this.isEditNumber = true;
                setTimeout(() => {
                    this.isEditNumber = false;
                }, 5000);
            }
        },
        getApprovement(id){
            this.approveId = id;
        },
        async confirmApprovement(){
            // let formData = new FormData();
            // formData.append('number', this.number);
            // formData.append('time_status', this.lottery_time);
            let url = '/api/2d/betting_wins/'+ this.approveId +'/approve';
            let response = await postApiData({url: url, token: this.getToken});
            if(response.success){
                console.log('approved')
                document.getElementById("close").click();
                // window.location.reload()
                this.getNumberList(false);
            }
            else{
                this.$notify({
                    text: response.message,
                    type: "error"
                });
                console.log(response.error)
            }
        },
        getCurrentDate(){
            this.currentDate = getCurrentDate()
        },
        formatDate(date){
            if(date){
                return moment(date).format("DD/MM/YYYY");
            }
        },
        formatTime(time){
            if(time){
                return moment(time,'HH:mm:ss').format("hh:mm A");
            }
        },
        searchHandler(search_input) {
            this.search_input = search_input;
            this.getNumberList(true);
        },
    },

    created() {
        this.getGameSettings();
        this.getNumberList(false);
    },

    mounted() {
        initTWE({Modal, Ripple, Dropdown});
    },
}
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
