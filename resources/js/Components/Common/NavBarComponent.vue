<template>
    <!-- <notifications position="top center" /> -->
    <div class="w-full flex justify-between pr-4 items-center">
        <p>{{ user.name }}</p>
    </div>
    <div>
        <div class="flex gap-x-4">
            <NotificationBells></NotificationBells>
            <button
                @click="logOut()"
                class="bg-black text-white px-6 py-2 rounded flex items-center text-sm gap-x-2 hover:bg-[#272727] hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 transition duration-150"
            >
                Logout <i class="fal fa-sign-in-alt"></i>
            </button>
        </div>
    </div>
</template>

<script>
import firebase from "firebase/compat/app";
import "firebase/messaging";
import { Dropdown, Modal, Ripple, initTWE } from "tw-elements";
import { mapGetters } from "vuex";

import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import { mapMutations } from "vuex/dist/vuex.cjs.js";
import NotificationBells from "./NotificationBells.vue";

export default {
    components: {
        NotificationBells,
    },
    data() {
        return {
            user: null,
        };
    },
    computed: {
        ...mapGetters(["getUser", "getToken"]),
    },
    methods: {
        ...mapMutations(["setUser", "setToken"]),
        async logOut() {
            this.setUser("");
            this.setToken("");
            window.location.href = "/logout";

            // let url = "/api/logout";
            // let response = await postApiData({
            //     url: url,
            //     token: this.getToken,
            // });
            // if (response.success) {
            //     this.$notify({
            //         title: "Success!",
            //         text: response.message,
            //         type: "info",
            //     });
            //     this.setUser("");
            //     this.setToken("");
            //     window.location.href = "/login";
            // } else {
            //     this.$notify({
            //         title: "Error!",
            //         text: response.error,
            //         type: "error",
            //     });
            // }
        },
    },

    created() {
        this.user = this.getUser;
    },

    mounted() {
        initTWE({ Dropdown, Modal, Ripple });
    },
};
</script>
