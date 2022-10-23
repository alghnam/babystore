import Vue from 'vue'
import Vuetify from 'vuetify/lib/framework'
import preset from './default-preset/preset'
import '@mdi/font/css/materialdesignicons.css'
import "vuetify/dist/vuetify.min.css";
Vue.use(Vuetify)

export default new Vuetify({
  rtl: true,
  // preset,
  icons: {
    // iconfont: 'mdiSvg',
    iconfont: 'mdi',
  },
  // theme: {
  //   options: {
  //     customProperties: true,
  //     variations: true,
  //   },
  // },
})
