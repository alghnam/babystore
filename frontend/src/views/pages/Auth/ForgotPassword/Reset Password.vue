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
          <p class="mb-2">من فضلك قم بكتابة كلمة المرور وتاكيدها هنا</p>
        </v-card-text>

        <!-- resetPassword form -->
        <v-card-text>
          <v-form>
          
            <v-text-field
              v-model="password"
              outlined
              :type="isPasswordVisible ? 'text' : 'password'"
              label="Password"
              placeholder="············"
              :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              hide-details
              @click:append="isPasswordVisible = !isPasswordVisible"
            ></v-text-field>

            <v-text-field
              v-model="password_confirmation"
              outlined
              :type="isPasswordVisible ? 'text' : 'password'"
              label="Confirmation Password"
              placeholder="············"
              :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              hide-details
              @click:append="isPasswordVisible = !isPasswordVisible"
            ></v-text-field>

            <v-btn block color="primary" class="mt-6" @click="resetPassword()"> resetPassword </v-btn>
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
  setup() {
    const isPasswordVisible = ref(false)
    const passport_num = ref('')
    const password = ref('')
    const socialLink = [
      {
        icon: mdiFacebook,
        color: '#4267b2',
        colorInDark: '#4267b2',
      },
      {
        icon: mdiTwitter,
        color: '#1da1f2',
        colorInDark: '#1da1f2',
      },
      {
        icon: mdiGithub,
        color: '#272727',
        colorInDark: '#fff',
      },
      {
        icon: mdiGoogle,
        color: '#db4437',
        colorInDark: '#db4437',
      },
    ]

    return {
      isPasswordVisible,
      passport_num,
      password,
      socialLink,

      icons: {
        mdiEyeOutline,
        mdiEyeOffOutline,
      },
    }
  },
  data() {
    return {
      passport_num: null,
      password: null,
      password_confirmation: null,
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

    //for resetPassword
    resetPassword() {
               let rand= localStorage.getItem('rand')

      this.$http
        .post(`password/reset/${rand}`, {
          password: this.password,
          password_confirmation: this.password_confirmation,
        })
        .then(res => {
         
          this.$store.state.snackbar=true
          this.$store.state.text = res.data.message
          this.$router.push('/login')

        })
      .catch(error => {
            this.$store.state.snackbar=true
          this.$store.state.text = error.response.data.message
        })
    },
  },
  created() {},
}
</script>

<style lang="scss">
@import '~@/plugins/vuetify/default-preset/preset/pages/auth.scss';
</style>
