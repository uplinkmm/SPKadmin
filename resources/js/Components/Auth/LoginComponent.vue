<template>
    <notifications position="top center" />
    <main class="w-full block relative">
        <div class="w-[100vw] h-[100vh] overflow-hidden">
            <!-- <img class="h-auto w-full" src="../../../public/img/loginbackground.jpg" alt=""> -->
            <div
                class="mx-auto rounded-md"
                style="
                    width: 30vw;
                    left: calc(50% - 15vw);
                    top: 24%;
                    position: absolute;
                    padding: 3rem;
                "
            >
                <div class="mb-3">
                    <p
                        class="text-4xl text-black primary-font relative dash-under"
                    >
                        {{ userType == "admin" ? "Admin" : "Agent" }} Login
                    </p>
                </div>
                <div class="bg-white" @keyup.enter="login">
                    <div v-if="userType == 'admin'" class="mb-3 w-full">
                        <label for="username" class="block mb-2 text-sm">
                            User Name
                        </label>
                        <input
                            type="text"
                            id="username"
                            v-model="userName"
                            class="block w-full h-14 rounded-lg py-1 px-4 border text-sm bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div v-if="userType == 'agent'" class="mb-3 w-full">
                        <label for="username" class="block mb-2 text-sm">
                            Phone Number
                        </label>
                        <input
                            type="text"
                            id="phone_number"
                            v-model="phone_number"
                            class="block w-full h-14 rounded-lg py-1 px-4 border text-sm bg-white focus:ring-0 focus:shadow-none"
                        />
                    </div>
                    <div class="mb-3 w-full">
                        <label for="password" class="block mb-2 text-sm">
                            Password
                        </label>
                        <div class="col">
                            <input
                                type="password"
                                id="password"
                                v-model="password"
                                class="block w-full h-14 rounded-lg py-1 px-4 border text-sm bg-white focus:ring-0 focus:shadow-none"
                            />
                        </div>

                        <button class="text-xs pt-2 border-0 bg-transparent">
                            Forget Password?
                        </button>
                    </div>
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                v-model="remember"
                                checked
                                class="form-checkbox mr-2"
                            />
                            <span class="text-sm">Remember me next time</span>
                        </label>
                    </div>
                    <div class="mb-0 flex justify-center">
                        <button
                            @click="login"
                            class="bg-[#FF4300] px-6 py-2 rounded-full text-sm text-white"
                        >
                            Login
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
            if (!this.userName || !this.password) {
                this.$notify({
                    text: "Fill all required fields!",
                    type: "error",
                });
                return;
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
            let response = await postApiData({ url: url, form_data: formData });
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
