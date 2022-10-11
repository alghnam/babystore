import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    baseURL: "https://www.baby-store.sharmhostxyz.xyz",
    token: null,
    user: {},
    snakbar: false,
    text: ""
  }, 
  mutations: {},
  actions: {},
  modules: {},
})
