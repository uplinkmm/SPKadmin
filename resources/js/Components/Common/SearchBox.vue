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
        };
    },
    methods: {
        search() {
            this.searchHandler(this.search_input);
        },
    },
    mounted() {},
};
</script>

<style lang="scss" scoped></style>
