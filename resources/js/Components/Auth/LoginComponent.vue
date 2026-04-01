<template>
    <notifications position="top center" />
    <main class="w-full min-h-screen flex items-center justify-center bg-gray-50 px-4 py-10">
        <div class="w-full max-w-md">
            <div class="mx-auto rounded-2xl bg-white shadow-xl border border-slate-100 p-6 sm:p-8 space-y-6">
                <div class="text-center space-y-2">
                    <p
                        class="text-3xl sm:text-4xl text-slate-900 font-semibold tracking-tight"
                    >
                        {{ userType == "admin" ? "Admin" : "Agent" }} Login
                    </p>
                    <p class="text-sm text-slate-500">Sign in to manage your dashboard</p>
                </div>
                <div class="bg-white space-y-4" @keyup.enter="login">
                    <div v-if="userType == 'admin'" class="w-full space-y-2">
                        <label for="username" class="block text-sm text-slate-600">
                            User Name
                        </label>
                        <input
                            type="text"
                            id="username"
                            v-model="userName"
                            class="block w-full h-12 rounded-lg px-4 border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#FF4300]/40 focus:border-[#FF4300] outline-none transition"
                        />
                    </div>
                    <div v-if="userType == 'agent'" class="w-full space-y-2">
                        <label for="username" class="block text-sm text-slate-600">
                            Phone Number
                        </label>
                        <input
                            type="text"
                            id="phone_number"
                            v-model="phone_number"
                            class="block w-full h-12 rounded-lg px-4 border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#FF4300]/40 focus:border-[#FF4300] outline-none transition"
                        />
                    </div>
                    <div class="w-full space-y-2">
                        <label for="password" class="block text-sm text-slate-600">
                            Password
                        </label>
                        <div class="col">
                            <input
                                type="password"
                                id="password"
                                v-model="password"
                                class="block w-full h-12 rounded-lg px-4 border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-[#FF4300]/40 focus:border-[#FF4300] outline-none transition"
                            />
                        </div>
                    </div>
                    <div class="pt-2">
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input
                                type="checkbox"
                                v-model="remember"
                                checked
                                class="form-checkbox h-4 w-4 accent-[#FF4300]"
                            />
                            <span>Remember me next time</span>
                        </label>
                    </div>
                    <div class="pt-2">
                        <button
                            :disabled="loading"
                            @click="login"
                            class="bg-[#FF4300] hover:bg-[#e33c00] disabled:opacity-70 px-10 py-3 rounded-xl text-base font-semibold text-white w-full transition"
                        >
                            {{ loading ? "Loading..." : "Login" }}
                        </button>
                    </div>
                </div>
            </div>

            <form
                method="POST"
                id="signin-form"
                ref="signinForm"
                action="/login"
            >
                <input type="hidden" v-model="csrfToken" name="_token" />
                <input
                    v-if="userType == 'admin'"
                    type="hidden"
                    v-model="userName"
                    name="username"
                />
                <input
                    v-if="userType == 'agent'"
                    type="hidden"
                    v-model="phone_number"
                    name="phone_number"
                />
                <input type="hidden" v-model="password" name="password" />
                <input type="hidden" v-model="remember" name="remember" />
                <input type="hidden" :value="userType" name="credential_type" />
                <!-- <input type="hidden" v-model="fcmToken" name="fcm_token"> -->
            </form>
        </div>
    </main>
</template>

<script>
import { mapGetters, mapMutations } from "vuex";
import { postApiData } from "../../utilities/ajax-helpers";

import firebase from "firebase/compat/app";
import "firebase/messaging";

export default {
    name: "LoginComponent",
    props: {
        userType: {},
    },
    data() {
        return {
            token: null,
            csrfToken: null,
            userName: null,
            password: null,
            remember: true,
            fcmToken: null,
            phone_number: null,
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["getLoginCredentials"]),
    },
    methods: {
        ...mapMutations([
            "setUser",
            "setToken",
            "setCsrfToken",
            "setLoginCredentials",
        ]),

        async login() {
            console.log(this.userName, this.password);
            if (this.userType == "agent") {
                if (!this.phone_number || !this.password) {
                    this.$notify({
                        text: "Fill all required fields!",
                        type: "error",
                    });
                    return;
                }
            } else {
                if (!this.userName || !this.password) {
                    this.$notify({
                        text: "Fill all required fields!",
                        type: "error",
                    });
                    return;
                }
            }

            let url = "/api/login";
            let formData = new FormData();
            if (this.userType == "agent") {
                formData.append("phone_number", this.phone_number);
            } else {
                formData.append("username", this.userName);
            }
            formData.append("password", this.password);
            formData.append("credential_type", this.userType);
            if (this.fcmToken) {
                formData.append("fcm_token", this.fcmToken);
            }
            this.loading = true;
            let response = await postApiData({ url: url, form_data: formData });
            this.loading = false;
            if (response.data) {
                this.token = response.data.token;
                this.setToken(this.token);
                let user = response.data.user;
                this.setUser(user);

                if (this.remember) {
                    let credentials = {
                        userType: this.userType,
                        userName: this.userName,
                        phone_number: this.phone_number,
                        password: this.password,
                        remember: this.remember,
                    };
                    this.setLoginCredentials(credentials);
                }
                this.$refs.signinForm.submit();

                return true;
            } else {
                this.$notify({
                    text: response.message,
                    type: "error",
                });
                console.log(response.message);
                return false;
            }
        },
    },

    created() {
        this.csrfToken = $('meta[name="csrf-token"]').attr("content");
        this.setCsrfToken(this.csrfToken);
    },

    async mounted() {
        if (this.getLoginCredentials) {
            let credentials = this.getLoginCredentials;
            if (credentials.userType === this.userType) {
                this.userName = credentials.userName || "";
                this.phone_number = credentials.phone_number || "";
                this.password = credentials.password || "";
                this.remember = credentials.remember || false;
            }
        }
        try {
            const permission = await Notification.requestPermission();
            console.log("notification permission", permission);
            let notiType = "warn";
            if (permission == "denied") {
                notiType = "warn";
            }
            if (permission == "granted") {
                notiType = "info";
                this.firebaseMessaging = firebase.messaging();
                this.fcmToken = await this.firebaseMessaging.getToken();
                console.log("fcm token", this.fcmToken);
            }

            this.$notify({
                text: `Notification permission ${permission}`,
                type: notiType,
            });
        } catch (error) {
            console.log(error);
            this.$notify({
                text: "Firebase error",
                type: "error",
            });
        }
    },
};
</script>
