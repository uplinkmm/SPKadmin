<template>
    <notifications position="top center" @click="showNotiDropDown" />
    <div class="contents">
        <div class="relative flex items-center">
            <div
                class="relative flex flex-col items-center"
                data-twe-dropdown-ref
                data-twe-dropdown-alignment="end"
                ref="dropdownWrapper"
            >
                <a
                    class="flex items-center justify-center text-neutral-600"
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
                <p
                    class="mt-1 text-[9px] leading-none text-gray-600 text-center"
                >
                    Withdrawal
                </p>
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
                class="relative flex flex-col items-center"
                data-twe-dropdown-ref
                data-twe-dropdown-alignment="end"
            >
                <a
                    class="flex items-center justify-center text-neutral-600"
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
                <p
                    class="mt-1 text-[9px] leading-none text-gray-600 text-center"
                >
                    Deposit
                </p>
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
        <div class="relative flex items-center">
            <div
                class="relative"
                data-twe-dropdown-ref
                data-twe-dropdown-alignment="end"
            >
                <a
                    class="me-4 flex items-center text-neutral-600"
                    href="#"
                    id="customer_bell"
                    ref="customerBell"
                    role="button"
                    data-twe-dropdown-toggle-ref
                    aria-expanded="false"
                    @click="handlerClickBell('customer')"
                >
                    <i class="fas fa-bell pl-5"></i>
                    <span
                        v-if="customer_notification.count > 0"
                        class="absolute -mt-4 ms-2.5 rounded-full bg-danger px-[0.35em] py-[0.15em] text-[0.6rem] font-bold leading-none text-white"
                        >{{ customer_notification.count }}</span
                    >
                </a>
                <p
                    class="mt-1 text-[9px] leading-none text-gray-600 text-center"
                >
                    Register
                </p>
                <div
                    class="absolute z-[1000] pt-6 float-left m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg data-[twe-dropdown-show]:block"
                    aria-labelledby="customer_bell"
                    data-twe-dropdown-menu-ref
                >
                    <div
                        class="flex justify-between mb-2 px-8 text-sm text-neutral-700 gap-x-8 items-center"
                    >
                        <p>
                            You have {{ customer_notification.count }} new
                            notifications
                        </p>
                        <button
                            @click="readNotification(0, 'customer')"
                            class="bg-black text-white px-3 py-1 rounded-md text-xs"
                        >
                            Mark All as Read
                        </button>
                    </div>
                    <ul
                        class="mt-0 px-4 pt-0 mb-8 overflow-y-auto max-h-[50vh] small-scrollbar"
                        @scroll="onNotificationScroll('customer')"
                    >
                        <li
                            v-for="(noti, index) in customer_notification_data"
                            :key="index"
                        >
                            <a
                                class="flex gap-x-4 w-full whitespace-nowrap bg-white px-4 py-2 rounded-md hover:bg-zinc-100 focus:bg-zinc-200/60 focus:outline-none active:bg-zinc-200/60 active:no-underline"
                                href="#"
                                data-twe-dropdown-item-ref
                                @click="readNotification(noti.id, 'customer')"
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
                                        {{ noti.customer_name || noti.title }}
                                    </p>
                                    <p class="text-xs mb-3 relative">
                                        {{ noti.preview }}
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
                                        {{
                                            formatTime(
                                                noti.date_time ||
                                                    noti.created_at
                                            )
                                        }}
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

const NOTIFICATION_TYPES = [
    "cash_withdrawl_transaction",
    "topup_transaction",
    "customer",
];

const NOTIFICATION_CONFIG = {
    cash_withdrawl_transaction: {
        stateKey: "cash_withdrawl_transaction",
        dataKey: "cash_withdrawl_transaction_data",
        bellRef: "cashWithdrawlBell",
        fallbackTitle: "Withdrawal Notification",
    },
    topup_transaction: {
        stateKey: "topup_transaction",
        dataKey: "topup_transaction_data",
        bellRef: "topupTransactionBell",
        fallbackTitle: "Deposit Notification",
    },
    customer: {
        stateKey: "customer_notification",
        dataKey: "customer_notification_data",
        bellRef: "customerBell",
        fallbackTitle: "Register Notification",
    },
};

export default {
    data() {
        return {
            user: null,
            cash_withdrawl_transaction: {},
            cash_withdrawl_transaction_data: [],
            topup_transaction: {},
            topup_transaction_data: [],
            customer_notification: {},
            customer_notification_data: [],
            type: "cash_withdrawl_transaction",
            notiType: null, //topup_transaction , cash_withdrawl_transaction
            notificationTimeout: null,
            notificationAudio: null,
            showSpinner: false,
            unreadNotificationIds: {
                cash_withdrawl_transaction: [],
                topup_transaction: [],
                customer: [],
            },
            alertedNotificationIds: {
                cash_withdrawl_transaction: [],
                topup_transaction: [],
                customer: [],
            },
            refreshOnFocusTimeout: null,
            visibilityChangeHandler: null,
            focusHandler: null,
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
        getNotificationConfig(type) {
            return NOTIFICATION_CONFIG[type] || NOTIFICATION_CONFIG.topup_transaction;
        },
        getNotificationState(type) {
            return this[this.getNotificationConfig(type).stateKey];
        },
        setNotificationState(type, value) {
            this[this.getNotificationConfig(type).stateKey] = value;
        },
        getNotificationItems(type) {
            return this[this.getNotificationConfig(type).dataKey];
        },
        setNotificationItems(type, value) {
            this[this.getNotificationConfig(type).dataKey] = value;
        },
        getBellRef(type) {
            return this.$refs[this.getNotificationConfig(type).bellRef];
        },
        getUnreadNotifications(payload) {
            return (payload?.notifications?.data || []).filter(
                (notification) => Number(notification.is_read) === 0
            );
        },
        rememberUnreadNotifications(type, payload) {
            this.unreadNotificationIds[type] = this.getUnreadNotifications(
                payload
            ).map((notification) => String(notification.id));
        },
        rememberAlertedNotifications(type, notifications) {
            if (!notifications.length) {
                return;
            }

            const alertedIds = new Set(this.alertedNotificationIds[type] || []);
            notifications.forEach((notification) => {
                alertedIds.add(String(notification.id));
            });
            this.alertedNotificationIds[type] = Array.from(alertedIds).slice(
                -100
            );
        },
        getNewUnreadNotifications(type, payload) {
            const previousUnreadIds = new Set(
                this.unreadNotificationIds[type] || []
            );
            const alertedIds = new Set(this.alertedNotificationIds[type] || []);

            return this.getUnreadNotifications(payload).filter(
                (notification) => {
                    const id = String(notification.id);
                    return !previousUnreadIds.has(id) && !alertedIds.has(id);
                }
            );
        },
        resetBellAnimations() {
            NOTIFICATION_TYPES.forEach((type) => {
                const bell = this.getBellRef(type);
                if (bell) {
                    bell.classList.remove("animate-bounce");
                }
            });
        },
        highlightNotification(type) {
            this.resetBellAnimations();
            this.notiType = type;

            const bell = this.getBellRef(type);
            if (bell) {
                bell.classList.add("animate-bounce");
            }
        },
        buildNotificationText(notification, type) {
            if (notification?.preview) {
                return notification.preview;
            }

            if (type == "cash_withdrawl_transaction") {
                return `${notification?.customer_name || "Customer"} has just withdrawal from ${notification?.account_name || "account"}`;
            }

            if (type == "topup_transaction") {
                return `${notification?.customer_name || "Customer"} has just deposit from ${notification?.account_name || "account"}`;
            }

            if (type == "customer") {
                return notification?.title || "New register notification";
            }

            return "You have a new notification";
        },
        showInAppNotification(type, notification, extraCount = 0) {
            const title =
                notification?.title ||
                this.getNotificationConfig(type).fallbackTitle;
            const extraText = extraCount > 0 ? ` (+${extraCount} more)` : "";

            this.$notify({
                title: title,
                text: `${this.buildNotificationText(
                    notification,
                    type
                )}${extraText}`,
                type: "info",
                duration: 10000,
                closeOnClick: true,
            });
        },
        showBrowserNotification(title, body) {
            if (
                typeof Notification === "undefined" ||
                Notification.permission !== "granted"
            ) {
                return;
            }

            new Notification(title, { body: body });
        },
        announceMissedNotifications(type, notifications) {
            if (!notifications.length) {
                return;
            }

            this.highlightNotification(type);
            this.playNotificationSound();
            this.showInAppNotification(
                type,
                notifications[0],
                notifications.length - 1
            );
            this.rememberAlertedNotifications(type, notifications);
        },
        resolveNotificationTypeFromBody(body = "") {
            const normalizedBody = body.toLowerCase();

            if (normalizedBody.includes("withdrawal")) {
                return "cash_withdrawl_transaction";
            }
            if (normalizedBody.includes("deposit")) {
                return "topup_transaction";
            }
            if (normalizedBody.includes("register")) {
                return "customer";
            }

            return "topup_transaction";
        },
        getCurrentNotificationPage(type, reset = false) {
            if (reset) {
                return 1;
            }

            const currentNotification = this.getNotificationState(type);
            if (
                !currentNotification ||
                currentNotification === "" ||
                !currentNotification.notifications
            ) {
                return 1;
            }

            return currentNotification.notifications.current_page || 1;
        },
        applyNotificationResponse(type, payload, reset = false) {
            this.setNotificationState(type, payload);
            this.setNotificationItems(type, [
                ...(reset ? [] : this.getNotificationItems(type)),
                ...(payload?.notifications?.data || []),
            ]);
        },
        async refreshAllNotifications(options = {}) {
            for (const notificationType of NOTIFICATION_TYPES) {
                await this.getNotifications(notificationType, {
                    reset: true,
                    ...options,
                });
            }
        },
        scheduleNotificationRefresh() {
            if (document.hidden) {
                return;
            }

            if (this.refreshOnFocusTimeout) {
                clearTimeout(this.refreshOnFocusTimeout);
            }

            this.refreshOnFocusTimeout = setTimeout(() => {
                this.refreshAllNotifications({
                    announceNewUnread: true,
                });
            }, 150);
        },
        handleVisibilityChange() {
            if (!document.hidden) {
                this.scheduleNotificationRefresh();
            }
        },
        handleWindowFocus() {
            this.scheduleNotificationRefresh();
        },
        stopNotificationSound() {
            if (this.notificationTimeout) {
                clearTimeout(this.notificationTimeout);
                this.notificationTimeout = null;
            }

            if (this.notificationAudio) {
                this.notificationAudio.pause();
                this.notificationAudio.currentTime = 0;
            }
        },
        handlerClickBell(type) {
            if (this.notiType == type) {
                this.stopNotificationSound();

                const notiBell = this.getBellRef(type);
                if (notiBell) {
                    notiBell.classList.remove("animate-bounce");
                }
            }

            this.setNotificationState(type, "");
            this.setNotificationItems(type, []);
            this.type = type;
            this.getNotifications(type, { reset: true });
            this.notiType = null;
        },
        async getNotifications(type, options = {}) {
            const { reset = false, announceNewUnread = false } = options;
            this.showSpinner = true;

            const current_page = this.getCurrentNotificationPage(type, reset);
            let url = `/api/notifications?type=${type}&page=${current_page}&per_page=10`;
            let response = await getApiData({
                url: url,
                token: this.getToken,
            });
            this.showSpinner = false;

            if (response.data) {
                const newUnreadNotifications = announceNewUnread
                    ? this.getNewUnreadNotifications(type, response.data)
                    : [];

                this.applyNotificationResponse(type, response.data, reset);
                this.rememberUnreadNotifications(type, response.data);

                if (announceNewUnread) {
                    this.announceMissedNotifications(
                        type,
                        newUnreadNotifications
                    );
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
                this.getNotifications(type, { reset: true });
            } else {
                this.$notify({
                    title: "Error!",
                    text: response.message,
                    type: "error",
                });
            }
        },
        async startOnMessageListener() {
            console.log(`im running`);
            try {
                this.firebaseMessaging.onMessage(async (payload) => {
                    console.log("message received: ", payload);
                    const title = payload.notification?.title || "Notification";
                    const body = payload.notification?.body || "";
                    const notificationType =
                        this.resolveNotificationTypeFromBody(body);

                    this.highlightNotification(notificationType);
                    this.playNotificationSound();
                    this.showBrowserNotification(title, body);
                    this.showInAppNotification(notificationType, {
                        title: title,
                        preview: body,
                    });
                    await this.refreshAllNotifications();
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
            if (type == "topup_transaction") {
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
            if (type == "customer") {
                if (
                    this.customer_notification.notifications.current_page >=
                        1 &&
                    this.customer_notification.notifications.current_page <
                        this.customer_notification.notifications.last_page &&
                    !this.showSpinner
                ) {
                    this.customer_notification.notifications.current_page += 1;
                    this.getNotifications("customer");
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
            this.stopNotificationSound();
            this.notificationAudio = new Audio("/noti_sound.wav");
            this.notificationAudio.play().catch((error) => {
                console.log("Audio playback failed:", error);
                this.showErrorModal();
            });

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
            const bell = this.getBellRef(this.notiType);

            if (bell) {
                setTimeout(() => {
                    bell.click();
                }, 100);
            }
        },
    },
    mounted() {
        this.visibilityChangeHandler = () => this.handleVisibilityChange();
        this.focusHandler = () => this.handleWindowFocus();

        document.addEventListener(
            "visibilitychange",
            this.visibilityChangeHandler
        );
        window.addEventListener("focus", this.focusHandler);
    },
    beforeUnmount() {
        this.stopNotificationSound();

        if (this.refreshOnFocusTimeout) {
            clearTimeout(this.refreshOnFocusTimeout);
            this.refreshOnFocusTimeout = null;
        }

        if (this.visibilityChangeHandler) {
            document.removeEventListener(
                "visibilitychange",
                this.visibilityChangeHandler
            );
        }

        if (this.focusHandler) {
            window.removeEventListener("focus", this.focusHandler);
        }
    },
    created() {
        this.user = this.getUser;
        this.requestPermission();
        this.refreshAllNotifications();
    },
};
</script>
