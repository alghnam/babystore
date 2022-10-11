import '@/plugins/vue-composition-api'
import '@/styles/styles.scss'
import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'
import store from './store'
import axios from "axios"

Vue.config.productionTip = false
let api = axios.create({
  baseURL: "https://www.baby-store.sharmhostxyz.xyz/api"
})

Vue.prototype.$http = api

new Vue({
  router,
  store,
  vuetify,
  data:() =>({
    rating:4.5
  }),
  created(){
    //setting the token to the default $http instance (to become for all pages)
    let token = localStorage.getItem("token");
  //  let userId = JSON.parse(localStorage.getItem("userId"))
    let userId = localStorage.getItem("userId")
   // console.log('JSON.parse(localStorage.getItem("userId"))',  localStorage.getItem("userId"))
    if(token){
      this.$http.defaults.headers.authorization = `Bearer ${token}` 
      this.$store.state.token = token
    //  console.log('this.$store.state.user.id', this.$store.state)
      // this.$http
      // .get(`profile/show/${userId}`)
      // .then(res => {
      //   console.log('res.data.user', res.data.data)
      //   localStorage.setItem("user", JSON.stringify(res.data.data))        

      // })
      // this.$router.push("/dashboard")
      //check last page 
    let last_page = localStorage.getItem("last_page");
    if(last_page){
      this.$router.push(last_page)
    }
    }
    
  },
  render: h => h(App),
}).$mount('#app')
