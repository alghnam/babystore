<template>
  <div class="auth-wrapper auth-v1">
 
    <div class="auth-inner">
      <v-card class="auth-card">
        <!-- logo -->
        <v-card-title class="d-flex align-center justify-center py-7">
          <router-link to="/" class="d-flex align-center">
            <v-img
              :src="require('@/assets/images/logos/logo.svg')"
              max-height="30px"
              max-width="30px"
              alt="logo"
              contain
              class="me-3"
            ></v-img>

            <h2 class="text-2xl font-weight-semibold">BABY STORE APP</h2>
          </router-link>
        </v-card-title>

        <!-- title -->
        <v-card-text>
          <p class="text-2xl font-weight-semibold text--primary mb-2">Welcome to BABY STORE APP! 👋🏻</p>
          <p class="mb-2">أكتب رقم موبايلك للبحث عنه</p>
        </v-card-text>

        <!-- login form -->
        <v-card-text>
          <v-form>
            <v-text-field
              v-model="phone_no"
              outlined
              label="رقم الموبايل"
              placeholder="+9621547211"
              hide-details
              class="mb-3"
            ></v-text-field>

            <v-btn block color="primary" class="mt-6" @click="sendCodeMsg()"> ابحث </v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </div>

    <!-- background triangle shape  -->
    <img
      class="auth-mask-bg"
      height="173"
      :src="require(`@/assets/images/misc/mask-${$vuetify.theme.dark ? 'dark' : 'light'}.png`)"
    />

    <!-- tree -->
    <v-img class="auth-tree" width="247" height="185" src="@/assets/images/misc/tree.png"></v-img>

    <!-- tree  -->
    <v-img class="auth-tree-3" width="377" height="289" src="@/assets/images/misc/tree-3.png"></v-img>
  </div>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import { mdiFacebook, mdiTwitter, mdiGithub, mdiGoogle, mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import { ref } from '@vue/composition-api'
import axios from 'axios'
export default {
  data() {
    return {
      phone_no: null,
      password: null,
      snackbar: false,
      text: null,
      color: null,
     
    }
  },
  watch: {

  },
  methods: {
    //methods for handle errors
    callMessage(message) {
      this.snackbar=true
      this.text=message
     
    },

    //for sendCodeMsg
    sendCodeMsg() {
      this.$http
        .post('password/forgot', {
          phone_no: this.phone_no,
        })
        .then(res => {
          this.$store.state.snackbar=true
          this.$store.state.text = res.data.message
          localStorage.setItem('phone_no', this.phone_no)
          localStorage.setItem('rand', res.data.data.rand)
              //go into page write a code
              this.$router.push('/code-confimation')
           
        })
      .catch(error => {
            this.$store.state.snackbar=true
          this.$store.state.text = error.response.data.message
        })
    },
  },
  created() {
    // this.login()
  },
}
</script>

<style lang="scss">
@import '~@/plugins/vuetify/default-preset/preset/pages/auth.scss';
</style>
