<template>
    <div class="flex items-center mb-4">
        <label for="itemsPerPage" class="mr-2 text-gray-700">Show</label>
        <select
            id="itemsPerPage"
            @change="handleChange"
            v-model="selectedValue"
            class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500"
        >
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
            <option value="50000">All</option>
        </select>
    </div>
</template>

<script>
export default {
    name: "SelectionPaginationCount",
    props: {
        handleChange: {
            type: Function,
            required: true
        },
        initialValue: {
            type: [String, Number],
            default: "50"
        }
    },
    data() {
        return {
            selectedValue: this.initialValue,
        };
    },
    methods: {
        handleChange() {
            this.handleChange(this.selectedValue);
        }
    },
    watch: {
        initialValue(newValue) {
            this.selectedValue = newValue;
        }
    }
};
</script>
