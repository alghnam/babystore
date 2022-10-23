<template>
  <div>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">الاسم الاول</th>
              <th class="text-right text-uppercase">الاسم الاخير</th>
              <th class="text-right text-uppercase">اسم المنتج</th>
              <th class="text-right text-uppercase">الوصف</th>
              <th class="text-right text-uppercase">التقييم</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in reviews" :key="item.id">
              <td class="text-right">{{ item.first_name }}</td>
              <td class="text-right">{{ item.last_name }}</td>

              <td class="text-right">
                {{ item.product ? item.product.name : null }}
              </td>

              <td class="text-right">
                {{ item.description }}
              </td>
              <td class="text-right">
                {{ item.rating }}
                <v-rating
                  v-model="item.rating"
                  color="yellow darken-3"
                  background-color="grey darken-1"
                  empty-icon="$ratingFull"
                  half-increments
                  large
                ></v-rating>
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
            ادارة الاراء
            <v-dialog v-model="dialog">
              <template v-slot:activator="{ on, attrs }">
                <v-btn color="primary" dark class="" @click="createItem(item)" v-bind="attrs" v-on="on" icon
                  ><v-icon>mdi-plus-circle</v-icon></v-btn
                >
              </template>

              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.user_id }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon dark large>mdi-account-circle</v-icon> ادارة الاراء
                      </v-alert>
                    </v-card-title>
                    <v-card-text>
                      <div class="row">
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="الاسم الاول"
                          v-model="editedItem.first_name"
                        ></v-text-field>
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="الاسم الاخير"
                          v-model="editedItem.last_name"
                        ></v-text-field>
                        <v-menu rounded offset-y>
                          <template v-slot:activator="{ attrs, on }">
                            <v-text-field
                              class="white--text ma-5"
                              v-bind="attrs"
                              v-on="on"
                              outlined
                              dense
                              label="اختر منتجا"
                              v-model="editedItem.product.name"
                            >
                            </v-text-field>
                          </template>

                          <v-list>
                            <v-list-item v-for="item in productsReviews" :key="item" link>
                              <v-list-item-title v-text="item.text" @click="getproduct(item)"></v-list-item-title>
                            </v-list-item>
                          </v-list>
                        </v-menu>
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="الوصف"
                          v-model="editedItem.description"
                        ></v-text-field>
                        التقييم:
                        <v-rating
                          v-model="editedItem.rating"
                          color="yellow darken-3"
                          background-color="grey darken-1"
                          empty-icon="$ratingFull"
                          half-increments
                          hover
                          large
                        ></v-rating>

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

        <template v-slot:top>
          <v-toolbar flat color="white">
            ادارة الاراء
            <v-dialog v-model="dialog">
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.name }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon large>mdi-account-circle</v-icon> ادارة الاراء
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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getreviews()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      reviews: [],
      products: [],
      product_id: null,
      rating: null,
      editedIndex: -1,
      editedItem: {
        first_name: null,
        last_name: null,
        rating: null,
        description: null,
        product: {
          name: null,
        },
      },
      productsReviews: [],
      defaultItem: {},
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
    dialog(val) {
      val || this.close()
    },

    'editedItem.product.name': {
      handler: function (val) {
        if (val) {
          this.$http.get(`admin/products/search/${val}`).then(res => {
            res.data.data.forEach(product => {
              this.productsReviews = []
              this.productsReviews.push({
                text: product.name,
                value: product.id,
              })
            })
          })
        } else {
          this.productsReviews = []
        }
      },
    },
    'editedItem.rating': {
      handler: function (val) {
        this.editedItem.rating = val
      },
    },
  },
  created() {
    this.getreviews()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    getproduct(item) {
      this.product_id = item.value
      this.editedItem.product.name = item.text
    },
    getproductReview(product_id) {
      this.$http
        .get('admin/products-review/{}')
        .then(res => {
          res.data.data.forEach(product => {
            this.products.push({
              text: product.name,
              value: product.id,
            })
          })
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    getreviews() {
      this.$http
        .get(`admin/reviews/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.reviews = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    async save() {
      if (this.product_id === null) {
        this.product_id = this.editedItem.product_id
      }
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/reviews/update/${this.editedItem.id}`, {
            first_name: this.editedItem.first_name,
            last_name: this.editedItem.last_name,
            description: this.editedItem.description,
            rating: this.editedItem.rating,
            product_id: this.product_id,
            status: this.editedItem.status,
          })

          .then(res => {
            this.dialog = false
            Object.assign(this.reviews[this.editedIndex], {
              first_name: this.editedItem.first_name,
              last_name: this.editedItem.last_name,
              description: this.editedItem.description,
              rating: this.editedItem.rating,
              product_id: this.product_id,
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
          .post(`admin/reviews/store`, {
            first_name: this.editedItem.first_name,
            last_name: this.editedItem.last_name,
            description: this.editedItem.description,
            rating: this.editedItem.rating,
            product_id: this.product_id,

            status: this.editedItem.status,
          })

          .then(res => {
            this.dialog = false
            Object.assign(this.reviews[this.editedIndex], {
              first_name: this.editedItem.first_name,
              last_name: this.editedItem.last_name,
              description: this.editedItem.description,
              rating: this.editedItem.rating,
              product_id: this.product_id,
              original_status: res.data.data.original_status,
            })

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
      this.editedIndex = this.reviews.indexOf(item)
      Object.assign(this.editedItem, {
        ...item,
      })

      this.dialog = true
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.reviews.indexOf(item)
      confirm('Are You Sure To Delete This Item ?') &&
        this.$http
          .get(`admin/reviews/destroy/${item.id}`)

          .then(res => {
            this.reviews.splice(index, 1)
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
