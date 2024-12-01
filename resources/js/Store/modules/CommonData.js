export default {
    state() {
        return {
            totalCount: 0,
            current_page: 1,
        };
    },
    getters: {
        getTotalCount(state) {
            return state.totalCount;
        },
        currentPage(state) {
            return state.current_page;
        },
    },
    mutations: {
        setTotalCount(state, page) {
            state.totalCount = page;
        },
        setCurrentPage(state, page) {
            state.current_page = page;
        },
    },

    actions: {},
};
