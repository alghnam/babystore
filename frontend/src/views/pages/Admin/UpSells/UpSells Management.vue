<template>
  <div>
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="showTrash()">
      سلة المحذوفات
      <v-icon class="mr-3">mdi-delete</v-icon>
    </v-btn>
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" outlined @click="createItem()">
      انشاء
      <v-icon class="mr-3">mdi-plus-circle</v-icon>
    </v-btn>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">title</th>
              <th class="text-right text-uppercase">description</th>
              <th class="text-right text-uppercase">footer</th>
              <th class="text-right text-uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in upsells" :key="item.id">
              <td class="text-right">{{ item.name }}</td>
              <td v-html="item.description"></td>
              <td class="text-right">{{ item.footer }}</td>

              <td class="text-right">
                <div>
                  <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="editUpsellProdItem(item)">
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
            ادارة المبيعات
            <v-dialog v-model="dialog">
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.user_id }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon dark large>mdi-account-circle</v-icon> ادارة البانرز
                      </v-alert>
                    </v-card-title>
                    <v-card-text>
                      <div class="row">
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="العنوان"
                          v-model="editedItem.name"
                        ></v-text-field>
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="الفوتر"
                          v-model="editedItem.footer"
                        ></v-text-field>

                        <vue-editor
                          class="col-sm-12 mx-auto"
                          v-model="editedItem.description"
                          :editorToolbar="customToolbar"
                        ></vue-editor>

                        <div class="col-sm-5 mx-auto row" style="margin-top: 40px">
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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getupsells()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import { VueEditor } from 'vue2-editor'

export default {
  components: {
    VueEditor,
  },
  computed: {},
  data() {
    return {
      dialog: false,
      upsells: [],
      products: [],
      users: [],

      usersupsells: [],
      usersnotifications: [],
      product_id: null,
      //description data
      customToolbar: [
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
        ['link', 'code-block'],
      ],
      editedIndex: -1,
      editedItem: {
        product: {
          name: null,
        },
        user_id: null,
        user: {
          first_name: null,
        },
        product_id: null,
        name: null,
        description: null,
        footer: null,
      },
      defaultItem: {},
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
      status: 0,
      usersupsells: [],
      productsupsells: [],
      imgs: [],
      img: null,
      base_imgs: [],

      snackbar: false,
      text: null,
      color: null,

      total: 0,
      pageInfo: null,
      page: 1,
      statusInput: null,
    }
  },

  watch: {
    similars(val) {
      if (val) {
        this.$http.get(`admin/products/search-for-similar/${val}`).then(res => {
          res.data.data.forEach(product => {
            this.productsSim = []
            this.productsSim.push({
              text: product.name,
              value: product.id,
            })
          })
        })
      } else {
        this.productsSim = []
      }
    },

    'editedItem.user.first_name': {
      handler: function (val) {
        if (val) {
          this.$http.get(`admin/users/search/${val}`).then(res => {
            res.data.data.forEach(user => {
              this.usersupsells = []
              this.usersupsells.push({
                text: user.name,
                value: user.id,
              })
            })
          })
        } else {
          this.usersupsells = []
        }
      },
    },
    dialog(val) {
      val || this.close()
    },

    'editedItem.user.first_name': {
      handler: function (val) {
        if (val) {
          this.$http.get(`admin/users/search/${val}`).then(res => {
            res.data.data.forEach(user => {
              this.usersupsells = []
              this.usersupsells.push({
                text: user.first_name,
                value: user.id,
              })
            })
          })
        } else {
          this.usersupsells = []
        }
      },
    },
  },
  created() {
    this.getupsells()
    this.getusers()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-upsells-management')
    },
    createItem() {
      this.$router.push('/create-upsell')
    },

    editUpsellProdItem(item) {
      this.$router.push(`/edit-upsells-product/${item.id}/${item.product_id}`)
    },

    getSimilar(item) {
      let similarsIds = []
      this.productsSimilar.forEach(el => {
        similarsIds.push(el.id)
      })

      if (!similarsIds.includes(item.value)) {
        this.productsSimilar.push(item)
        this.similars = item.name
      }
    },
    getuser(item) {
      let usersIds = []
      this.userspushnotification.forEach(el => {
        usersIds.push(el.id)
      })

      if (!usersIds.includes(item.value)) {
        this.userspushnotification.push(item)
        this.users = item.name
      }
    },

    getuser(item) {
      this.user_id = item.value
      //    this.usersSimilar.push(item)
      this.editedItem.user.first_name = item.text
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
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    getupsells() {
      this.editedItem.product.name = null
      this.editedItem.user.first_name = null
      this.status = 'inactive'
      this.product_id = this.$route.params.product_id
      this.$http
        .get(`admin/upsells/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.upsells = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    async save() {
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/upsells/update/${this.editedItem.id}`, {
            name: this.editedItem.name,
            description: this.editedItem.description,
            footer: this.editedItem.footer,
          })

          .then(res => {
            Object.assign(this.upsells[this.editedIndex], {
              name: this.editedItem.name,
              description: this.editedItem.description,
              footer: this.editedItem.footer,
            })
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
            this.dialog = false
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            }
          })
      }
    },
    editItem(item) {
      this.editedIndex = this.upsells.indexOf(item)
      Object.assign(this.editedItem, {
        ...item,
      })

      this.dialog = true
    },
    // createItem() {
    //   this.status = 'active'
    //   this.editedItem.product.name=null
    //   this.dialog = true
    // },

    deleteImage(img) {
      this.$http
        .get(`admin/upsells/delete-image/${img.id}`)

        .then(res => {
          const index = this.imgs.indexOf(img)
          this.imgs.splice(index, 1)
          this.base_imgs.splice(index, 1)
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    deleteImageNotStore(index) {
      //this.deleteO = true
      this.imgs.splice(index, 1)
      this.base_imgs.splice(index, 1)
    },
    deleteItem(item) {
      const index = this.upsells.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/upsells/destroy/${item.id}`)

          .then(res => {
            this.upsells.splice(index, 1)
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
      // this.editedItem.product.name=null
      // this.editedItem.user.first_name=null
      // this.status='inactive'

      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
  },
}
</script>
