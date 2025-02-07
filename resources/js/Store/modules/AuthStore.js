export default {
    state() {
        return {
            user: null,
            token: null,
            csrfToken: null,
            loginCredentials:null,
        }
    },

    mutations: {
        setUser(state, user){
            state.user = user;
        },

        setToken(state, token){
            state.token = token;
        },

        setCsrfToken(state, csrfToken){
            state.csrfToken = csrfToken;
        },
        setLoginCredentials(state, credentials){
            state.loginCredentials = credentials;
        }
    },

    actions: {},

    getters: {
        getUser(state){
            return state.user;
        },

        getToken(state){
            return state.token;
        },        

        getCsrfToken(state){
            return state.csrfToken;
        },
        getLoginCredentials(state){
            return state.loginCredentials;
        },
    }
};
