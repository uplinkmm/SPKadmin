<template>
    <notifications position="top center" @click="showNotiDropDown" />
    <div class="contents">
        <div class="relative flex items-center">
            <div
                class="relative"
                data-twe-dropdown-ref
                data-twe-dropdown-alignment="end"
                ref="dropdownWrapper"
            >
                <a
                    class="me-4 flex items-center text-neutral-600"
                    href="#"
                    id="cash_withdrawl_transaction_bell"
                    role="button"
                    ref="cashWithdrawlBell"
                    data-twe-dropdown-toggle-ref
                    aria-expanded="false"
                    @click="handlerClickBell('cash_withdrawl_transaction')"
                >
                    <i class="fas fa-bell"></i>
                    <span
                        v-if="cash_withdrawl_transaction.count > 0"
                        class="absolute -mt-4 ms-2.5 rounded-full bg-danger px-[0.35em] py-[0.15em] text-[0.6rem] font-bold leading-none text-white"
                    >
                        {{ cash_withdrawl_transaction.count }}</span
                    >
                </a>
                <div
                    class="absolute z-[1000] pt-4 float-left m-0 hidden min-w-max overflow-y-auto list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg data-[twe-dropdown-show]:block"
                    aria-labelledby="cash_withdrawl_transaction_bell"
                    data-twe-dropdown-menu-ref
                    ref="dropdownMenu"
                >
                    <div
                        class="flex justify-between mb-2 px-8 text-sm text-neutral-700 gap-x-8 items-center"
                    >
                        <p>
                            You have {{ cash_withdrawl_transaction.count }} new
                            notifications
                        </p>
                        <button
                            @click="
                                readNotification(
                                    0,
                                    'cash_withdrawl_transaction'
                                )
                            "
                            class="bg-black text-white px-3 py-1 rounded-md text-xs"
                        >
                            Mark All as Read
                        </button>
                    </div>
                    <ul
                        class="mt-0 px-4 pt-0 mb-8 overflow-y-auto max-h-[50vh] small-scrollbar"
                        @scroll="
                            onNotificationScroll('cash_withdrawl_transaction')
                        "
                    >
                        <li
                            v-for="(
                                noti, index
                            ) in cash_withdrawl_transaction_data"
                            :key="index"
                        >
                            <a
                                class="flex gap-x-4 w-full whitespace-nowrap bg-white px-4 py-2 rounded-md hover:bg-zinc-100 focus:bg-zinc-200/60 focus:outline-none active:bg-zinc-200/60 active:no-underline"
                                href="#"
                                data-twe-dropdown-item-ref
                                @click="
                                    readNotification(
                                        noti.id,
                                        'cash_withdrawl_transaction'
                                    )
                                "
                            >
                                <div class="w-12 h-12 flex-shrink-0">
                                    <img
                                        class="rounded-full w-12 h-12 shadow-md"
                                        src="../../../../public/img/pngtree-profile-picture-vector-png-image_11063301 1.png"
                                        alt=""
                                    />
                                </div>
                                <div
                                    class="max-w-[400px] whitespace-normal pr-6"
                                >
                                    <p class="text-sm mb-1">
                                        {{ noti.customer_name }}
                                    </p>
                                    <p class="text-sm mb-3 relative">
                                        has just withdrawal from
                                        <span class="text-green-600">
                                            {{ noti.account_name }}</span
                                        >
                                        <span
                                            v-if="noti.is_read == 0"
                                            style="
                                                right: -1.5rem;
                                                top: calc(50% - 8px);
                                            "
                                            class="bg-red-600 w-3 h-3 rounded-full absolute ml-2"
                                        >
                                        </span>
                                    </p>

                                    <p class="text-xs text-gray-500 mb-3">
                                        {{ formatTime(noti.date_time) }}
                                    </p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="relative flex items-center">
            <div
                class="relative"
                data-twe-dropdown-ref
                data-twe-dropdown-alignment="end"
            >
                <a
                    class="me-4 flex items-center text-neutral-600"
                    href="#"
                    id="topup_transaction_bell"
                    ref="topupTransactionBell"
                    role="button"
                    data-twe-dropdown-toggle-ref
                    aria-expanded="false"
                    @click="handlerClickBell('topup_transaction')"
                >
                    <i class="fas fa-bell"></i>
                    <span
                        v-if="topup_transaction.count > 0"
                        class="absolute -mt-4 ms-2.5 rounded-full bg-danger px-[0.35em] py-[0.15em] text-[0.6rem] font-bold leading-none text-white"
                        >{{ topup_transaction.count }}</span
                    >
                </a>
                <div
                    class="absolute z-[1000] pt-6 float-left m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg data-[twe-dropdown-show]:block"
                    aria-labelledby="topup_transaction_bell"
                    data-twe-dropdown-menu-ref
                >
                    <div
                        class="flex justify-between mb-2 px-8 text-sm text-neutral-700 gap-x-8 items-center"
                    >
                        <p>
                            You have {{ topup_transaction.count }} new
                            notifications
                        </p>
                        <button
                            @click="readNotification(0, 'topup_transaction')"
                            class="bg-black text-white px-3 py-1 rounded-md text-xs"
                        >
                            Mark All as Read
                        </button>
                    </div>
                    <ul
                        class="mt-0 px-4 pt-0 mb-8 overflow-y-auto max-h-[50vh] small-scrollbar"
                        @scroll="onNotificationScroll('topup_transaction')"
                    >
                        <li
                            v-for="(noti, index) in topup_transaction_data"
                            :key="index"
                        >
                            <a
                                class="flex gap-x-4 w-full whitespace-nowrap bg-white px-4 py-2 rounded-md hover:bg-zinc-100 focus:bg-zinc-200/60 focus:outline-none active:bg-zinc-200/60 active:no-underline"
                                href="#"
                                data-twe-dropdown-item-ref
                                @click="
                                    readNotification(
                                        noti.id,
                                        'topup_transaction'
                                    )
                                "
                            >
                                <div class="w-12 h-12 flex-shrink-0">
                                    <img
                                        class="rounded-full w-12 h-12 shadow-md"
                                        src="../../../../public/img/pngtree-profile-picture-vector-png-image_11063301 1.png"
                                        alt=""
                                    />
                                </div>
                                <div
                                    class="max-w-[400px] whitespace-normal pr-6"
                                >
                                    <p class="text-sm mb-1">
                                        {{ noti.customer_name }}
                                    </p>
                                    <p class="text-sm mb-3 relative">
                                        has just deposit from
                                        <span class="text-green-600">
                                            {{ noti.account_name }}</span
                                        >
                                        <span
                                            v-if="noti.is_read == 0"
                                            style="
                                                right: -1.5rem;
                                                top: calc(50% - 8px);
                                            "
                                            class="bg-red-600 w-3 h-3 rounded-full absolute ml-2"
                                        >
                                        </span>
                                    </p>
                                    <p class="text-xs text-gray-500 mb-3">
                                        {{ formatTime(noti.date_time) }}
                                    </p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <button
        data-twe-toggle="modal"
        data-twe-target="#error_modal"
        id="error_modal_btn"
    ></button>
    <!--Error Modal Box -->
    <div
        data-twe-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="error_modal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
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
                    class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 py-4 px-6"
                >
                    <h4
                        class="text-xl text-red-600 font-medium leading-normal"
                        id="exampleModalLabel"
                    >
                        Audio Error
                    </h4>
                    <button
                        type="button"
                        id="close"
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
                    class="relative flex-auto py-6 px-6"
                    data-twe-modal-body-ref
                >
                    <p class="text-lg">Audo is muted for notifications</p>
                </div>

                <div
                    class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 py-4 px-6 gap-x-4"
                >
                    <!-- <button
                      type="button"
                      id="modalClose"
                      class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black focus:outline-none focus:ring-00"
                      data-twe-modal-dismiss
                      data-twe-ripple-init
                      data-twe-ripple-color="light"
                  >
                      Close
                  </button> -->
                    <button
                        data-twe-modal-dismiss
                        data-twe-ripple-init
                        data-twe-ripple-color="light"
                        type="button"
                        class="rounded bg-red-600 px-8 pb-2 pt-2.5 text-xs text-white focus:outline-none focus:ring-0"
                    >
                        Unmuted
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import firebase from "firebase/compat/app";
import "firebase/messaging";
import { mapGetters, mapMutations } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import moment from "moment";

export default {
    data() {
        return {
            user: null,
            cash_withdrawl_transaction: {},
            cash_withdrawl_transaction_data: [],
            topup_transaction: {},
            topup_transaction_data: [],
            type: "cash_withdrawl_transaction",
            notiType: null, //topup_transaction , cash_withdrawl_transaction
            notificationTimeout: null,
            notificationAudio: null,
        };
    },
    props: {
        getNoti: {
            type: Function,
        },
    },
    computed: {
        ...mapGetters(["getUser", "getToken", "getNotiPermissionShow"]),
    },
    methods: {
        ...mapMutations(["setCurrentPage", "setNotiPermissionShow"]),
        handlerClickBell(type) {
            if (this.notiType == type) {
                if (this.notificationTimeout) {
                    clearTimeout(this.notificationTimeout);
                }
                if (this.notificationAudio) {
                    this.notificationAudio.pause();
                    this.notificationAudio.currentTime = 0;
                }
                if(type=='topup_transaction'){
                    const notiBell = this.$refs.topupTransactionBell;
                    notiBell.classList.remove("animate-bounce");
                }
                if(type=='cash_withdrawl_transaction'){
                    const notiBell = this.$refs.cashWithdrawlBell;
                    notiBell.classList.remove("animate-bounce");
                }
            }
            if (type == "topup_transaction") {
                this.topup_transaction = "";
                this.topup_transaction_data = "";
                this.type = "topup_transaction";
                this.getNotifications("topup_transaction");
            } else {
                this.cash_withdrawl_transaction = "";
                this.cash_withdrawl_transaction_data = "";
                this.type = "cash_withdrawl_transaction";
                this.getNotifications("cash_withdrawl_transaction");
            }
            this.notiType = null;
        },
        async getNotifications(type) {
            this.showSpinner = true;
            if (
                type == "cash_withdrawl_transaction" &&
                this.cash_withdrawl_transaction == ""
            ) {
                var current_page = 1;
            }
            if (type == "topup_transaction" && this.topup_transaction == "") {
                var current_page = 1;
            }
            if (
                type == "cash_withdrawl_transaction" &&
                this.cash_withdrawl_transaction
            ) {
                var current_page = this.cash_withdrawl_transaction.notifications
                    ?.current_page
                    ? this.cash_withdrawl_transaction.notifications
                          ?.current_page
                    : 1;
            }
            if (type == "topup_transaction" && this.topup_transaction) {
                var current_page = this.topup_transaction.notifications
                    ?.current_page
                    ? this.cash_withdrawl_transaction.notifications
                          ?.current_page
                    : 1;
            }
            let url = `/api/notifications?type=${type}&page=${current_page}&per_page=10`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            //   console.log(this.getToken);
            this.showSpinner = false;

            if (response.data) {
                if (type == "cash_withdrawl_transaction") {
                    this.cash_withdrawl_transaction = response.data;
                    this.cash_withdrawl_transaction_data = [
                        ...this.cash_withdrawl_transaction_data,
                        ...response.data.notifications.data,
                    ];
                } else {
                    this.topup_transaction = response.data;
                    this.topup_transaction_data = [
                        ...this.topup_transaction_data,
                        ...response.data.notifications.data,
                    ];
                }
            }
            if (response.message == "Please login to continue") {
                window.location.href = "/login";
            }
        },
        async readNotification(id, type) {
            let formData = new FormData();

            formData.append("id", id);
            formData.append("type", type);

            let url = "/api/read_notification";
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
                this.getNotifications(type);
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.error,
                    type: "error",
                });
            }
        },
        async startOnMessageListener() {
            console.log(`im running`);
            try {
                await this.firebaseMessaging.onMessage((payload) => {
                    console.log("message received: ", payload);
                    let title = payload.notification.title;
                    let body = payload.notification.body;
                    if (body.includes("withdrawal")) {
                        this.notiType = "cash_withdrawl_transaction";
                        const notiBell = this.$refs.cashWithdrawlBell;
                        notiBell.classList.add("animate-bounce");
                        //remove older class
                        const notiBell_animate_remove = this.$refs.topupTransactionBell;
                        notiBell_animate_remove.classList.remove("animate-bounce");
                    } else {
                        this.notiType = "topup_transaction";
                        const notiBell = this.$refs.topupTransactionBell;
                        notiBell.classList.add("animate-bounce");
                        //remove animate another bell
                        const notiBell_animate_remove = this.$refs.cashWithdrawlBell;
                        notiBell_animate_remove.classList.remove("animate-bounce");
                 
                    }
                    let notiOptions = { body: body };
                    this.playNotificationSound();
                    new Notification(title, notiOptions);
                    this.$notify({
                        title: payload.notification.title,
                        text: payload.notification.body,
                        type: "info",
                        duration: 10000,
                        closeOnClick: true,
                    });
                    this.getNotifications("cash_withdrawl_transaction");
                    this.getNotifications("topup_transaction");
                });
            } catch (error) {
                console.log("error", error);
            }
        },

        async requestPermission() {
            try {
                const permission = await Notification.requestPermission();
                if (permission == "denied" && !this.getNotiPermissionShow) {
                    this.$notify({
                        text: `Notification permission ${permission}`,
                        type: "warn",
                    });
                    this.setNotiPermissionShow(true);
                }
                if (permission == "granted") {
                    console.log(`permission granted`);
                    this.firebaseMessaging = firebase.messaging();
                    this.fcmToken = await this.firebaseMessaging.getToken();
                    console.log(this.fcmToken);
                    this.startOnMessageListener();
                }
            } catch (error) {
                this.$notify({
                    text: "Firebase error",
                    type: "error",
                });
            }
        },
        formatTime(date) {
            return moment(date).fromNow();
        },
        handleScroll(type) {
            if (type == "cash_withdrawl_transaction") {
                if (
                    this.cash_withdrawl_transaction.notifications
                        .current_page >= 1 &&
                    this.cash_withdrawl_transaction.notifications.current_page <
                        this.cash_withdrawl_transaction.notifications
                            .last_page &&
                    !this.showSpinner
                ) {
                    this.cash_withdrawl_transaction.notifications.current_page += 1;
                    this.getNotifications("cash_withdrawl_transaction");
                }
            }
            if (this.type == "topup_transaction") {
                if (
                    this.topup_transaction.notifications.current_page >= 1 &&
                    this.topup_transaction.notifications.current_page <
                        this.topup_transaction.notifications.last_page &&
                    !this.showSpinner
                ) {
                    this.topup_transaction.notifications.current_page += 1;
                    this.getNotifications("topup_transaction");
                }
            }
        },
        onNotificationScroll(type) {
            const scrollContainer = event.target;
            const isAtBottom =
                scrollContainer.scrollHeight - scrollContainer.scrollTop <
                scrollContainer.clientHeight + 200;
            if (isAtBottom) {
                this.handleScroll(type);
            } else {
            }
        },
        playNotificationSound() {
            if (this.notificationAudio) {
                this.notificationAudio.pause();
                this.notificationAudio.currentTime = 0;
            }
            this.notificationAudio = new Audio("/noti_sound.wav");
            this.notificationAudio.play().catch((error) => {
                console.log("Audio playback failed:", error);
                this.showErrorModal();
            });
            if (this.notificationTimeout) {
                clearTimeout(this.notificationTimeout);
            }
            this.notificationTimeout = setTimeout(
                () => this.playNotificationSound(),
                3500
            );
        },
        showErrorModal() {
            const button = document.getElementById("error_modal_btn");
            if (button) {
                button.click();
            }
        },
        showNotiDropDown() {
            if (!this.notiType) {
                return;
            }
            if (this.notiType == "cash_withdrawl_transaction") {
                setTimeout(() => {
                    this.$refs.cashWithdrawlBell.click();
                }, 100);
                topupTransactionBell;
            } else {
                setTimeout(() => {
                    this.$refs.topupTransactionBell.click();
                }, 100);
            }
        },
    },
    mounted() {
    },
    created() {
        this.user = this.getUser;
        this.requestPermission();
        this.getNotifications("cash_withdrawl_transaction");
        this.getNotifications("topup_transaction");
    },
};
</script>
