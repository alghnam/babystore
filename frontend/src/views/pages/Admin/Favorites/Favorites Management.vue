<template>
  <div>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">user name</th>
              <th class="text-right text-uppercase">product name</th>
              <th class="text-right text-uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in favorites" :key="item.id">
              <td class="text-right">{{ item.user ? item.user.first_name : null }}</td>
              <td class="text-right">
                {{ item.product ? item.product.name : null }}
              </td>

              <td>
                <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </template>

        <template v-slot:top>
          <v-toolbar flat color="white">
            ادارة المفضلات
            <v-dialog v-model="dialog">
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.user_id }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon dark large>mdi-account-circle</v-icon> ادارة المفضلات
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
        @input="getfavorites()"
        circle
      ></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      favorites: [],
      products: [],
      users: [],
      product_id: null,

      editedIndex: -1,
      editedItem: {
        product: {
          name: null,
        },
        user: {
          name: null,
        },
        product_id: null,
        user_id: null,
        status: null,
      },
      user_id: null,
      defaultItem: {
        product: {
          name: null,
        },
        user: {
          name: null,
        },
        product_id: null,
        user_id: null,
        status: null,
      },
      statuses: [
        {
          text: 'Active',
          value: '1',
        },
        {
          text: 'InActive',
          value: '0',
        },
      ],
      status: 0,
      usersFavorites: [],
      productsFavorites: [],

      snackbar: false,
      text: null,
      color: null,

      total: 0,
      pageInfo: null,
      page: 1,
      statusInput: null,
    }
  },

  watch: {},
  created() {
    this.getfavorites()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    getfavorites() {
      this.editedItem.product.name = null
      this.editedItem.user.first_name = null
      this.product_id = this.$route.params.product_id
      this.$http
        .get(`admin/favorites/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.favorites = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },

    async save() {
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/favorites/update/${this.editedItem.id}`, {
            status: this.editedItem.status,
          })

          .then(res => {
            this.dialog = false
            Object.assign(this.favorites[this.editedIndex], {
              original_status: res.data.data.original_status,
            })

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          })
      } else {
        this.$http
          .post(`admin/favorites/store`, {
            status: this.editedItem.status.value,
          })

          .then(res => {
            this.dialog = false
            this.favorites.push(res.data.data)

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          })
      }
    },
    editItem(item) {
      this.editedIndex = this.favorites.indexOf(item)
      Object.assign(this.editedItem, {
        ...item,
      })

      this.dialog = true
    },
    createItem() {
      this.editedItem.product.name = null
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.favorites.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/favorites/destroy/${item.id}`)

          .then(res => {
            this.favorites.splice(index, 1)
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
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
