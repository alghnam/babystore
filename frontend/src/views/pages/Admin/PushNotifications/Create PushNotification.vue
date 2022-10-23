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
                  <v-icon large dark color="white">mdi-account-circle</v-icon> انشاء اشعار
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
                            <v-text-field
                              class="col-sm-5 mx-auto"
                              outlined
                              dense
                              label="العنوان"
                              v-model="editedItem.title"
                            ></v-text-field>
                            <vue-editor v-model="editedItem.body" :editorToolbar="customToolbar"></vue-editor>
                          </v-col>

                          <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                            <v-card>
                              <v-list-item class="pr-0">
                                <v-list-item-action class="pa-0">
                                  <v-switch
                                    class="mt-0 pa-0"
                                    v-model="data_similar"
                                    color="red"
                                    hide-details
                                  ></v-switch>
                                </v-list-item-action>
                                <v-list-item-content>
                                  <p class="text-right pr-3 pl-3" style="margin: auto">المستخدمين</p>
                                </v-list-item-content>
                                <v-list-item-action class="pa-0 ma-0" style="border-left: 0.3px solid #808080">
                                  <v-btn fab tile @click="show_similars = !show_similars">
                                    <v-icon>
                                      {{
                                        show_similars == true
                                          ? 'mdi-arrow-up-bold-hexagon-outline'
                                          : 'mdi-arrow-down-bold-hexagon-outline'
                                      }}
                                    </v-icon>
                                  </v-btn>
                                </v-list-item-action>
                              </v-list-item>
                              <v-divider></v-divider>
                              <v-card-text v-show="show_similars" class="mt-3 pa-2" v-if="data_similar == true">
                                <v-row>
                                  <v-col sm="12" md="12" lg="12" xl="12">
                                    <v-row> </v-row>

                                    <v-col sm="12" md="12" lg="12" xl="12">
                                      <v-row>
                                        <v-col sm="12" md="12" lg="12" xl="12">
                                          <v-menu rounded offset-y>
                                            <template v-slot:activator="{ attrs, on }">
                                              <v-text-field
                                                class="white--text ma-5"
                                                v-bind="attrs"
                                                v-on="on"
                                                outlined
                                                dense
                                                label="المستخدمين"
                                                v-model="users"
                                              >
                                              </v-text-field>
                                            </template>

                                            <v-list>
                                              <v-list-item v-for="item in usersNotif" :key="item" link>
                                                <v-list-item-title
                                                  v-text="item.text"
                                                  @click="getUser(item)"
                                                ></v-list-item-title>
                                              </v-list-item>
                                            </v-list>
                                          </v-menu>

                                          <div v-for="userNotif in usersNotification" :key="userNotif.id">
                                            <!--   <h4>{{ userNotif.name }}</h4> -->
                                            <h4>{{ userNotif.text }}</h4>

                                            <v-btn color="default" class="mt-6" @click="deleteUserNotif(userNotif)">
                                              حذف
                                            </v-btn>
                                          </div>

                                          <v-btn
                                            color="blue lighten-1 mt-3 mr-2 ml-2"
                                            @click="saveUserNotification()"
                                            dark
                                            >حفظ <v-icon>mdi-file</v-icon></v-btn
                                          >
                                        </v-col>
                                      </v-row>
                                    </v-col>
                                  </v-col>
                                </v-row>
                              </v-card-text>
                              <v-card-text v-show="show_similars" class="mt-3 pa-2" v-if="data_similar == false">
                                <v-alert color="red" class="text-center">لا يوجد بيانات لعرضها</v-alert>
                              </v-card-text>
                            </v-card>
                          </v-col>
                        </v-row>
                      </v-card-text></v-card
                    >
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
      usersNotification: [],
      usersNotif: [],
      userNotifsIds: [],
      is_deleted: false,

      //sections data
      show_similars: false,
      data_similar: true,

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
      dialog: false,
      product_id: null,
      products: [],
      users: null,

      editedIndex: -1,
      editedItem: {
        title: null,
        body: null,
        status: null,
      },
      //
      item: {},
      defaultItem: {},
      status: 0,
      snackbar: false,
      text: null,
      color: null,

      total: 0,
      pageInfo: null,
      page: 1,
      content: '<h2>I am Example</h2>',
      editorOption: {
        debug: 'info',
        placeholder: 'type your description ...',
        readOnly: true,
        theme: 'snow',
      },
      showVariants: false,
      showSimilars: false,
    }
  },

  watch: {
    search(word) {
      this.$http
        .get(`admin/users/search/${word}`)
        .then(res => {
          this.usersNotification.push(res.data.data.name)
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    // },

    users(val) {
      if (val) {
        this.$http
          .get(`admin/users/search/${val}`)
          .then(res => {
            this.usersNotif = []

            res.data.data.forEach(user => {
              // this.usersNotif = []
              this.usersNotif.push({
                text: user.phone_no,
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
      } else {
        this.usersNotif = []
      }
    },
  },
  created() {},
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    getUser(item) {
      let usersIds = []
      this.usersNotification.forEach(el => {
        usersIds.push(el.id)
      })

      if (!usersIds.includes(item.value)) {
        this.usersNotification.push(item)
        this.users = item.phone_no
      }
    },

    showTrash() {
      this.$router.push('/trash-products-managment')
    },

    deleteUserNotif(userNotif) {
      const index = this.usersNotification.indexOf(userNotif)
      this.usersNotification.splice(index, 1)
      this.is_deleted = true
    },

    async saveUserNotification() {
      if (this.is_deleted == true) {
        this.userNotifsIds = []
      }
      let userId
      this.usersNotification.forEach(el => {
        userId = el.value
        this.userNotifsIds.push(userId)
      })

      this.$http
        .post('admin/pushnotifications/store', {
          title: this.editedItem.title,
          body: this.editedItem.body,
          users: this.userNotifsIds,
        })

        .then(res => {
          this.editedItem = {
            title: null,
            body: null,
            status: null,
          }
          this.usersNotification = []
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
      this.editedIndex = this.products.indexOf(item)
      item.category.id = item.category_id

      Object.assign(this.editedItem, {
        ...item,
      })
    },
  },
}
</script>
