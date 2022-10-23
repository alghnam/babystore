<template>
  <div>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:activator="{ on, attrs }">
          <v-btn color="primary" dark class="" v-bind="attrs" v-on="on" icon>
            <v-icon>mdi-plus-circle</v-icon>
          </v-btn>
        </template>

        <template v-slot:expanded-item="{ headers, item }">
          <td :colspan="headers.length">More info about {{ item.name }}</td>
        </template>
        <!-- page layout -->
        <div class="container">
          <div class="row">
            <v-card class="col-sm-12 mx-auto">
              <!-- page title -->
              <v-card-title class="pa-2">
                <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" dark color="primary">
                  <v-icon large dark color="white">mdi-account-circle</v-icon> انشاء رسالة
                </v-alert>
              </v-card-title>
              <!-- page content -->
              <v-card-text>
                <div class="row">
                  <!-- left section -->
                  <v-col xs="12" sm="12" md="8" lg="8" xl="8" class="mx-auto">
                    <v-card outlined>
                      <v-card-text>
                        <v-row>
                          <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                            <v-menu rounded offset-y>
                              <template v-slot:activator="{ attrs, on }">
                                <v-text-field
                                  class="white--text ma-5"
                                  v-bind="attrs"
                                  v-on="on"
                                  outlined
                                  dense
                                  label="اختر اسم المستقبل"
                                  v-model="user"
                                >
                                </v-text-field>
                              </template>

                              <v-list>
                                <v-list-item v-for="item in userschats" :key="item" link>
                                  <v-list-item-title v-text="item.text" @click="getuser(item)"></v-list-item-title>
                                </v-list-item>
                              </v-list>
                            </v-menu>
                            <vue-editor
                              class="col-sm-12 mx-auto"
                              v-model="message"
                              :editorToolbar="customToolbar"
                            ></vue-editor>
                          </v-col>
                        </v-row>
                      </v-card-text>

                      <v-btn color="blue lighten-1" class="mx-auto" @click="save()" dark
                        >حفظ <i class="fas fa-file mr-3"></i
                      ></v-btn>
                    </v-card>
                  </v-col>
                </div>
              </v-card-text>
            </v-card>
          </div>
        </div>
      </v-simple-table>
    </v-col>
  </div>
</template>

<script>
import axios from 'axios'
import { VueEditor } from 'vue2-editor'

export default {
  components: {
    VueEditor,
  },
  computed: {},
  data() {
    return {
      message: null,
      user_id: null,
      user: null,
      userschats: [],
      //sections data
      show_similars: false,
      data_variant: true,
      items: [
        {
          action: 'mdi-ticket',
          items: [{ title: 'List Item' }],
          title: 'Attractions',
          show: false,
        },
        {
          action: 'mdi-silverware-fork-knife',
          active: true,
          items: [{ title: 'Breakfast & brunch' }, { title: 'New American' }, { title: 'Sushi' }],
          title: 'Dining',
          show: false,
        },
      ],
      //description data
      customToolbar: [
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
        ['link', 'code-block'],
      ],

      users: null,

      //
      item: {},
      snackbar: false,
      text: null,
      color: null,

      total: 0,
      pageInfo: null,
      page: 1,
    }
  },

  watch: {
    user: {
      handler: function (val) {
        if (val) {
          this.$http.get(`admin/users/search/${val}`).then(res => {
            res.data.data.forEach(user => {
              this.userschats.push({
                text: user.first_name,
                value: user.id,
              })
            })
          })
        } else {
          this.userschats = []
        }
      },
    },
  },
  created() {
    this.getusers()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
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
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    getuser(item) {
      this.user_id = item.value
      this.user = item.text
    },

    showTrash() {
      this.$router.push('/trash-products-managment')
    },

    save() {
      this.$http
        .post('admin/chats/store', {
          message: this.message,
          user_id: 1,
          client_id: this.user_id,
          chat_room_id: 1,
        })

        .then(res => {
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
  },
}
</script>
