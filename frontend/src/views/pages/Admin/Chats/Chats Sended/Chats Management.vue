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
              <th class="text-right text-uppercase">اسم المستقبل</th>
              <th class="text-right text-uppercase">الرسالة</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in chats" :key="item.id">
              <td class="text-right">{{ item.client ? item.client.first_name : null }}</td>

              <td class="text-center" v-html="item.message"></td>

              <td class="text-right">
                <v-btn
                  color="primary"
                  class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl"
                  outlined
                  @click="editItem(item)"
                >
                  <v-icon color="black" class="white--text">mdi-pencil</v-icon>
                  رسالة أخرى
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
            ادارة الرسائل التي ارسلتها الى الاعضاء (المرسلة مني)

            <v-dialog v-model="dialog">
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.name }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon large>mdi-account-circle</v-icon> ادارة الرسائل التي ارسلتها الى الاعضاء (المرسلة مني)
                      </v-alert>
                    </v-card-title>
                    <v-card-text>
                      <vue-editor v-model="editedItem.message" :editorToolbar="customToolbar"></vue-editor>

                      <div class="col-sm-5 mx-auto row">
                        <v-btn color="blue lighten-1" class="col-sm-5 mx-auto" @click="save()" dark
                          >حفظ <i class="fas fa-file mr-3"></i
                        ></v-btn>
                        <v-btn color="amber darken-3" class="col-sm-5 mx-auto" @click="close()" dark
                          >رجوع<i class="fas fa-share mr-3"></i
                        ></v-btn>
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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getchats()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
import { VueEditor } from 'vue2-editor'

export default {
  components: {
    VueEditor,
  },
  data() {
    return {
      dialog: false,
      chats: [],
      users: [],
      userschats: [],

      statuses: [
        {
          text: 'Active',
          value: 1,
        },
        {
          text: 'InActive',
          value: 0,
        },
      ],
      //description data
      customToolbar: [
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
        ['link', 'code-block'],
      ],

      client_id: null,
      editedIndex: -1,
      editedItem: {
        message: null,
        client: {
          id: null,
          first_name: null,
        },
      },
      defaultItem: {
        message: null,
        client: {
          id: null,
          first_name: null,
        },
      },
      snackbar: false,
      text: null,
      color: null,

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
    this.getchats()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-chats-sended-management')
    },

    getchats() {
      this.$http
        .get(`admin/chats/get-all-chats-sended-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.chats = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },

    save() {
      this.$http
        .post('admin/chats/store', {
          message: this.editedItem.message,
          user_id: 1,
          client_id: this.editedItem.client.id,
          chat_room_id: 1,
        })

        .then(res => {
          this.dialog = false
          this.chats.push(res.data.data)

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
    editItem(item) {
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })
      this.editedItem.message = null

      this.editedIndex = this.chats.indexOf(item)
    },

    deleteItem(item) {
      const index = this.chats.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/chats/destroy/${item.id}`)

          .then(res => {
            if (res.data.message != null) {
              this.chats.splice(index, 1)
              this.$store.state.snackbar = true
              this.$store.state.text = res.data.message
            }
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
