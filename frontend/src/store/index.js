import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    baseURL: "https://www.babystore-backend.almoswaq.com",
    token: null,
    user: {},
    snackbar: false,
    text: ""
  }, 
  mutations: {},
  actions: {},
  modules: {},
})
