<template>
    <label
        for="search"
        class="border border-gray-600 text-sm font-inter rounded-lg"
    >
        <input
            v-model="search_input"
            @keyup.enter="search"
            type="text"
            id="search"
            placeholder="Search"
            class="px-3 py-2 rounded-md"
        />
    </label>
</template>

<script>
import { mapActions, mapMutations } from "vuex";
export default {
    name: "Searchbox",
    props: {
        searchHandler: {
            type: Function,
        },
    },

    data() {
        return {
            search_input: null,
            debounceTimeout: null,
        };
    },
    methods: {
        search() {
            this.searchHandler(this.search_input);
        },
    },
    watch: {
        search_input(newValue) {
            if (this.debounceTimeout) clearTimeout(this.debounceTimeout);

            this.debounceTimeout = setTimeout(() => {
                this.searchHandler(newValue);
            }, 800); // debounce time = 1000ms
        },
    },
    beforeUnmount() {
        if (this.debounceTimeout) clearTimeout(this.debounceTimeout);
    },
    mounted() {},
};
</script>

<style lang="scss" scoped></style>
