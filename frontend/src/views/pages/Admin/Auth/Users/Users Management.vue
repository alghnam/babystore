<template>
  <div>
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="showTrash()">
      سلة المحذوفات
      <v-icon class="mr-3">mdi-delete</v-icon>
    </v-btn>

    <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-right text-uppercase">الاسم الأول</th>
            <th class="text-right text-uppercase">الاسم الأخير</th>

            <th class="text-right text-uppercase">رقم الهاتف</th>

            <th class="text-right text-uppercase">الأحداث</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="item in users" :key="item.id">
            <td class="text-right">{{ item.first_name }}</td>

            <td class="text-right">
              {{ item.last_name }}
            </td>

            <td class="text-right">
              {{ item.phone_no }}
            </td>

            <td class="text-right">
              <div v-if="item.id !== 1">
                <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="editItem(item)">
                  <v-icon color="black" class="white--text">mdi-pencil</v-icon>
                </v-btn>
                <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
              </div>
            </td>
          </tr>
        </tbody>
      </template>

      <template v-slot:top>
        <v-toolbar flat color="white">
          ادارة المستخدمين
          <v-dialog v-model="dialog">
            <template v-slot:activator="{ on, attrs }">
              <v-btn color="primary" dark class="rounded-lg mr-auto" v-bind="attrs" v-on="on" fab tile x-small
                ><v-icon>mdi-plus-circle</v-icon></v-btn
              >
            </template>

            <template v-slot:expanded-item="{ headers, item }">
              <td :colspan="headers.length">More info about {{ item.first_name }}</td>
            </template>
            <div class="container">
              <div class="row">
                <v-card class="col-sm-7 mx-auto">
                  <v-card-title>
                    <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                      <v-icon large>mdi-account-circle</v-icon> ادارة المستخدمين
                    </v-alert>
                  </v-card-title>
                  <v-card-text>
                    <div class="row">
                      <v-text-field
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="الاسم الأول"
                        v-model="editedItem.first_name"
                      ></v-text-field>
                      <v-text-field
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="الاسم الأخير"
                        v-model="editedItem.last_name"
                      ></v-text-field>
                      <v-text-field
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="رقم الهاتف"
                        type="number"
                        v-model="editedItem.phone_no"
                      ></v-text-field>
                      <v-select
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="الأدوار"
                        :items="roles"
                        v-model="editedItem.roles"
                        multiple
                      ></v-select>
                      <!-- <v-select
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="الحالة"
                        :items="statuses"
                        v-model="editedItem.status"
                      ></v-select> -->

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
        </v-toolbar>
      </template>
    </v-simple-table>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getUsers()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      users: [],
      roles: [],
      send_roles: [],
      //  statuses: [
      //     {
      //       text: 'Active',
      //       value: '1',
      //     },
      //     {
      //       text: 'InActive',
      //       value: '0',
      //     },
      //   ],
      confirms: [
        {
          text: 'confirmed',
          value: 1,
        },
        {
          text: 'not confirm',
          value: 0,
        },
      ],
      agreements: [
        {
          text: 'agreed',
          value: 1,
        },
        {
          text: 'not agree',
          value: 0,
        },
      ],
      editedIndex: -1,
      editedItem: {
        first_name: null,
        last_name: null,
        phone_no: null,
        status: null,
        photo: null,
        roles: [],
      },
      defaultItem: {
        first_name: null,
        last_name: null,
        phone_no: null,
        status: null,
        photo: null,
        roles: [],
      },
      status: 0,
      snackbar: false,
      text: null,
      color: null,

      photos: [],

      total: 0,
      pageInfo: null,
      page: 1,
    }
  },

  watch: {
    dialog(val) {
      val || this.close()
    },
  },
  created() {
    this.getUsers()
    this.getRoles()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-users-management')
    },
    getUsers() {
      this.$http
        .get(`admin/users/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.users = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    getRoles() {
      this.$http
        .get('admin/roles')
        .then(res => {
          res.data.data.forEach(da => {
            if (da.id !== 1) {
              this.roles.push({
                value: da.id,
                text: da.name,
              })
            }
          })
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    save() {
      let formData = []

      formData = new FormData()

      let dum_roles = []
      if (this.editedItem.roles) {
        this.editedItem.roles.forEach(role => {
          if (role.value) {
            dum_roles.push(role.value)
          } else {
            dum_roles.push(role)
          }
        })

        this.editedItem.roles = JSON.stringify(dum_roles)
      }
      formData.append('first_name', this.editedItem.first_name)
      formData.append('last_name', this.editedItem.last_name)
      formData.append('phone_no', this.editedItem.phone_no)
      formData.append('roles', this.editedItem.roles)
      // formData.append('status', this.editedItem.status)
      formData.append('status', 1)

      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/users/update/${this.editedItem.id}`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              'X-Requested-With': 'XMLHttpRequest',
            },
          })
          .then(res => {
            this.dialog = false
            Object.assign(this.users[this.editedIndex], res.data.data)

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            this.dialog = false

            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            }
          })
      } else {
        this.$http
          .post('admin/users/store', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              'X-Requested-With': 'XMLHttpRequest',
            },
          })

          .then(res => {
            this.dialog = false
            this.users.push(res.data.data)

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            this.dialog = false

            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            }
          })
      }
    },
    editItem(item) {
      this.editedIndex = this.users.indexOf(item)

      this.editedItem = Object.assign({}, item)

      // //get roles for this user id
      let roleso = []
      this.$http
        .get(`admin/roles/roles-user-by-name/${item.id}`)
        .then(res => {
          res.data.forEach(role => {
            if (role.id !== 1) {
              roleso.push({
                text: role.name,
                value: role.id,
              })
            }
          })
          this.editedItem.roles = roleso
          this.dialog = true
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.users.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/users/destroy/${item.id}`)

          .then(res => {
            this.users.splice(index, 1)
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            }
          })
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
  },
}
</script>
