<template>
  <v-container fluid>
    <v-layout column>
      <v-card>
        5555555555user.image.url {{ user.image.url }}
        <v-avatar size="96" class="mr-4" v-if="user.image">
          jjjj
          <img :src="$store.state.baseURL + '/storage/' + trimAttribute(user.image.url, '(S)')" alt="product image" />
        </v-avatar>
        <v-avatar size="96" class="mr-4" v-else>
          <img
            src="https://e7.pngegg.com/pngimages/492/286/png-clipart-computer-icons-user-profile-avatar-avatar-heroes-monochrome.png"
            alt="Avatar"
          />
        </v-avatar>

        <v-card-text>
          <label for="">Phone No.: {{ user.phone_no }}</label>
          <label for="">First name : {{ user.first_name }}</label>
          <label for="">Last name : {{ user.last_name }}</label>
          <label for="">email : {{ user.email }}</label>
        </v-card-text>

        <v-card-actions>
          <v-btn color="primary" @click="editPassword">
            <v-icon left dark>check</v-icon>
            Edit Password
          </v-btn>
          <v-btn color="primary" @click="edit">
            <v-icon left dark>check</v-icon>
            Save Changes
          </v-btn>
        </v-card-actions>
      </v-card>
      <v-dialog v-model="dialog">
      

        <template v-slot:expanded-item="{ headers, item }">
          <td :colspan="headers.length">More info about {{ item.first_name }}</td>
        </template>
        <div class="container">
          <div class="row">
            <v-card class="col-sm-7 mx-auto">
              <v-card-title>
                <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                  <v-icon large>mdi-account-circle</v-icon> Edit Profile
                </v-alert>
              </v-card-title>
              <v-card-text>
                <div class="row">
                  {{ user }}
                  <v-text-field
                    style="width: 100%"
                    outlined
                    dense
                    label="الاسم الأول"
                    v-model="user.first_name"
                  ></v-text-field>
                  <v-text-field
                    style="width: 100%"
                    outlined
                    dense
                    label="الاسم الأخير"
                    v-model="user.last_name"
                  ></v-text-field>
                  <v-text-field
                    style="width: 100%"
                    outlined
                    dense
                    label="الايميل"
                    v-model="user.email"
                    type="email"
                  ></v-text-field>
                  <v-text-field
                    style="width: 100%"
                    outlined
                    dense
                    label="رقم الموبايل"
                    v-model="user.phone_no"
                    type="number"
                  ></v-text-field>
                  <v-img
                    :src="user.image ? $store.state.baseURL + '/storage/' + trimAttribute(user.image.url, '(S)') : ''"
                    v-if="!user.photo_url"
                  ></v-img>
                  <img :src="user.photo_url" v-if="user.photo_url" style="height: 118px; width: 84px" />
                  <v-file-input
                    truncate-length="15"
                    outlined
                    dense
                    label="image profile"
                    class="col-sm-5 mx-auto"
                    v-model="photo"
                  ></v-file-input>
                  <div class="col-sm-5 mx-auto row">
                    <v-btn
                      color="primary lighten-1 rounded-tr-xl rounded-bl-xl"
                      class="col-sm-5 mx-auto"
                      @click="save()"
                      dark
                      >حفظ <i class="fas fa-file mr-3"></i
                    ></v-btn>
                    <v-btn
                      color="white"
                      light
                      class="col-sm-5 mx-auto black--text rounded-tr-xl rounded-bl-xl"
                      @click="close()"
                      dark
                      >رجوع
                      <v-icon class="mr-3">mdi-reply-all</v-icon>
                    </v-btn>
                  </div>
                </div>
              </v-card-text>
            </v-card>
          </div>
        </div>
      </v-dialog>
      <v-dialog v-model="dialogPassword">
        
        <template v-slot:expanded-item="{ headers, item }">
          <td :colspan="headers.length">More info about {{ item.first_name }}</td>
        </template>
        <div class="container">
          <div class="row">
            <v-card class="col-sm-7 mx-auto">
              <v-card-title>
                <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                  <v-icon large>mdi-account-circle</v-icon> Edit Profile
                </v-alert>
              </v-card-title>
              <v-card-text>
                <div class="row">

                 
              <v-text-field
              v-model="user.old_password"
              outlined
              :type="isPasswordVisible1 ? 'text' : 'password'"
              label="كلمة المرور القديمة"
              placeholder="············"
              :append-icon="isPasswordVisible1 ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              hide-details
              @click:append="isPasswordVisible1 = !isPasswordVisible1"
            ></v-text-field>
              <v-text-field
              v-model="user.new_password"
              outlined
              :type="isPasswordVisible2 ? 'text' : 'password'"
              label="كلمة المرور الجديدة"
              placeholder="············"
              :append-icon="isPasswordVisible2 ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              hide-details
              @click:append="isPasswordVisible2 = !isPasswordVisible2"
            ></v-text-field>
                          <v-text-field
              v-model="user.confirmation_new_password"
              outlined
              :type="isPasswordVisible3 ? 'text' : 'password'"
              label="تاكيد كلمة المرور "
              placeholder="············"
              :append-icon="isPasswordVisible3 ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              hide-details
              @click:append="isPasswordVisible3 = !isPasswordVisible3"
            ></v-text-field>


                  <div class="col-sm-5 mx-auto row">
                    <v-btn
                      color="primary lighten-1 rounded-tr-xl rounded-bl-xl"
                      class="col-sm-5 mx-auto"
                      @click="savePassword()"
                      dark
                      >حفظ <i class="fas fa-file mr-3"></i
                    ></v-btn>
                    <v-btn
                      color="white"
                      light
                      class="col-sm-5 mx-auto black--text rounded-tr-xl rounded-bl-xl"
                      @click="closePassword()"
                      dark
                      >رجوع
                      <v-icon class="mr-3">mdi-reply-all</v-icon>
                    </v-btn>
                  </div>
                </div>
              </v-card-text>
            </v-card>
          </div>
        </div>
      </v-dialog>
    </v-layout>
  </v-container>
</template>

<script>
import {  mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import { ref } from '@vue/composition-api'

export default {
  pageTitle: 'My Profile',
    setup() {
    const isPasswordVisible1 = ref(false)
    const isPasswordVisible2 = ref(false)
    const isPasswordVisible3 = ref(false)
        return {
      isPasswordVisible1,
      isPasswordVisible2,
      isPasswordVisible3,

      icons: {
        mdiEyeOutline,
        mdiEyeOffOutline,
      },
    }
    },
  data() {
    return {
      dialog: false,
      dialogPassword: false,
      photo: null,
      user: {
        id: null,
        phone_no: '',
        first_name: '',
        last_name: '',
        email: '',
        image: {
          url: null,
        },
        photo_url: null,
        old_password: null,
        new_password: null,
        confirmation_new_password: null,
      },
      userId: null,
      loading: false,

      defaultUser: {
        id: null,
        phone_no: '',
        first_name: '',
        last_name: '',
        email: '',
        image: {
          url: null,
        },
        photo_url: null,
        old_password: null,
        new_password: null,
        confirmation_new_password: null,
      },
    }
  },
  watch: {
    photo(val) {
      if (val) {
        let reader = new FileReader()
        reader.addEventListener('load', e => {
          this.user.photo_url = e.target.result
        })
        reader.readAsDataURL(val)
      } else {
        this.user.photo_url = null
      }
    },
    dialog(val) {
      val
    },
    dialogPassword(val) {
      val
    },
  },
  created() {
    //  this.params= this.$route.params.id
    this.user.id = JSON.parse(localStorage.getItem('userId'))

    this.$http.get('profile/show').then(res => {
      this.user = res.data.data
    })
    // this.userId=this.user.id
    // this.phone_no=this.user.phone_no
    // this.first_name=this.user.first_name
    // this.last_name=this.user.last_name
    // this.image=this.user.image.url
    // alert(this.personal_id)
    // this.getDataProfile()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    // openAvatarPicker () {
    //     this.showAvatarPicker = true
    // },
    // selectAvatar (avatar) {
    //     this.form.avatar = avatar
    // }
    // getDataProfile(){
    //   alert(this.$store.state.user.id)

    // },
    trimAttribute(value, size) {
      if (value !== null) {
        // let urlBack = value.substr(22, value.length)
        let new_url = value.slice(0, 15) + '/thumbnail/' + value.slice(16)
        // return new_url
        let index = new_url.length - 4
        let url = new_url.slice(0, index) + size + new_url.slice(index)
        return url
      }
    },
    edit() {
      this.dialog = true
    },
    editPassword() {
      this.dialogPassword = true
    },
    save() {
      let formData = []
      formData = new FormData()

      formData.append('first_name', this.user.first_name)
      formData.append('last_name', this.user.last_name)
      formData.append('phone_no', this.user.phone_no)
      formData.append('email', this.user.email)
      formData.append('image', this.photo)
      //edit route
      this.$http
        .post('profile/update', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
        .then(res => {
          // this.user = Object.assign({}, this.res.data.data.user)
          Object.assign(this.user, res.data.data.user)

          console.log('4444', this.user)
          this.close()
          this.callMessage(res.data.message)
        })
        .catch(error => {
          if (error && error.response) {
            this.callMessage(error.response.data.message)
          }
        })
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.user = Object.assign({}, this.defaultUser)
      })
    },
  savePassword() {
    console.log('this.user.old_password', this.user)
        //edit route
        this.$http
          .post('profile/update-password', {
            old_password: this.user.old_password,
            new_password: this.user.new_password,
            confirmation_new_password: this.user.confirmation_new_password,
          })
          .then(res => {
              this.closePassword()
              this.$http.get("logout").then(()=>{
        localStorage.removeItem("token");
        this.$router.push("/login")
      })
          })
          },
  closePassword() {
    this.dialogPassword = false
  },
  },
}
</script>
