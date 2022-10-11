<template>
  <div>
 
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="showTrash()">
        سلة المحذوفات
        <v-icon class="mr-3">mdi-delete</v-icon>
      </v-btn>
      <v-col cols="12" class="pb-3">

    <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-right text-uppercase">كلمة البحث</th>
            <th class="text-right text-uppercase">المستخدم</th>
            <th class="text-right text-uppercase">حالة الظهور</th>
            <th class="text-right text-uppercase">الاحداث</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in searches" :key="item.id">
            <td class="text-right">{{ item.word }}</td>

            <td class="text-right">
              {{ item.user ? item.user.first_name : '-' }} .' '. {{ item.user ? item.user.last_name : '-' }}
            </td>

            <td class="text-right">
              {{ item.original_status }}
            </td>

            <td>
                  <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="editItem(item)">
                    <v-icon color="black" class="white--text">mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                    <v-icon color="black" class="">mdi-delete</v-icon>
                  </v-btn>
                </td>
          </tr>
        </tbody>
      </template>

      <template v-slot:top>
        <v-toolbar flat color="white">
          ادارة قوائم البحث
          <v-dialog v-model="dialog">
            <template v-slot:expanded-item="{ headers, item }">
              <td :colspan="headers.length">More info about {{ item.name }}</td>
            </template>
            <div class="container">
              <div class="row">
                <v-card class="col-sm-7 mx-auto">
                  <v-card-title>
                    <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                      <v-icon large>mdi-account-circle</v-icon> ادارة قوائم البحث
                    </v-alert>
                  </v-card-title>
                  <v-card-text>
                    <div class="row">
                      <v-select
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="حالة الظهور"
                        :items="statuses"
                        v-model="editedItem.status"
                      ></v-select>

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
    </v-col>
    <template>
      <v-pagination
        v-model="page"
        :length="pageInfo && pageInfo.last_page"
        @input="getsearches()"
        circle
      ></v-pagination>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      searches: [],

      statuses: [
        {
          text: 'Active',
          value: "1",
        },
        {
          text: 'InActive',
          value: "0",
        },
      ],
      userName: null,
      user_id: null,
      editedIndex: -1,
      editedItem: {
        word: null,
        status: null,
        user: {
          id: null,
          first_name: null,
          last_name: null,
        },
      },
      defaultItem: {
        word: null,
        status: null,
        user: {
          id: null,
          first_name: null,
          last_name: null,
        },
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
  computed: {},
  watch: {
    dialog(val) {
      val || this.close()
    },
  },
  created() {
    this.getsearches()
    this.getusers()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-searches-management')
    },
    getsearches() {
      this.$http
        .get(`admin/searches/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.searches = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.callMessage(error.response.data.message)
          }
        })
    },
    getusers() {
      this.$http
        .get('admin/users')
        .then(res => {
          res.data.data.forEach(user => {
            this.users.push({
              text: user.first_name,
              value: user.id,
            })
          })
        })
        .catch(error => {
          if (error && error.response) {
            this.callMessage(error.response.data.message)
          }
        })
    },

    save() {
      //edit route
      this.$http
        .post(`admin/searches/update/${this.editedItem.id}`, {
          status: this.editedItem.status,
        })
        .then(res => {
            this.dialog = false
              Object.assign(this.searches[this.editedIndex], {
                original_status: res.data.data.original_status,
              })
            
            console.log('this.editedItem.status.text', this.editedItem.status.text)

            this.callMessage(res.data.message)
         
        })
        .catch(error => {
          if (error && error.response) {
            this.callMessage(error.response.data.message)
          }
        })
    },
    editItem(item) {
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })
      this.editedIndex = this.searches.indexOf(item)

    },

    deleteItem(item) {
      const index = this.searches.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/searches/destroy/${item.id}`)

          .then(res => {
              this.searches.splice(index, 1)
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
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
  },
}
</script>
