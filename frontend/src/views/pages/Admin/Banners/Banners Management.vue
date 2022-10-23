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
              <th class="text-right text-uppercase">العنوان</th>
              <th class="text-right text-uppercase">الوصف</th>
              <th class="text-right text-uppercase">اسم المنتج</th>
              <th class="text-right text-uppercase">الحالة</th>
              <th class="text-right text-uppercase">الأحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in banners" :key="item.id">
              <td class="text-right">{{ item.title }}</td>
              <td class="text-right">{{ item.description }}</td>
              <td class="text-right">
                {{ item.product.name.text ? item.product.name.text : item.product.name }}
                <div v-if="item.image">
                  <img style="width: 100px;"
                    :src="$store.state.baseURL + '/storage/' + trimAttribute(item.image.url, '(S)')"
                    alt="product image"
                  />
                </div>
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
            ادارة البانرز
            <v-dialog v-model="dialog">
              <template v-slot:activator="{ on, attrs }">
                <v-btn color="primary" dark class="rounded-lg mr-auto" v-bind="attrs" v-on="on" fab tile x-small
                  ><v-icon>mdi-plus-circle</v-icon></v-btn
                >
              </template>

              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about</td>
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
                      <v-row>
                        <v-col cols="12" xs="12" sm="12" md="3" lg="3" xl="3" class="mx-auto"></v-col>
                        <v-col cols="12" sm="12" md="6" lg="6" xl="6" class="mx-auto">
                          <v-img 
                            :src="
                              editedItem.image
                                ? $store.state.baseURL + '/storage/' + trimAttribute(editedItem.image.url, '(S)')
                                : ''
                            "
                            v-if="!editedItem.photo_url"
                            contain
                            width="200px"
                            class="mx-auto"
                          ></v-img>
                          <img
                            :src="editedItem.photo_url"
                            v-if="editedItem.photo_url"
                            contain
                            width="200px"
                            class="mx-auto"
                          />
                        </v-col>
                        <v-col cols="12" xs="12" sm="12" md="3" lg="3" xl="3" class="mx-auto"></v-col>
                        <v-col cols="12" xs="12" sm="12" md="6" lg="6" xl="6" class="">
                          <!-- image choose section -->
                          <v-file-input
                            truncate-length="15"
                            outlined
                            dense
                            prepend-icon=""
                            prepend-inner-icon="mdi-file"
                            label="صورة المنتج"
                            v-model="photo"
                          ></v-file-input>
                        </v-col>
                        <v-col cols="12" md="6" lg="6" xl="6" class="">
                          <v-text-field outlined dense label="العنوان" v-model="editedItem.title"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" lg="6" xl="6" class="">
                          <v-menu rounded offset-y>
                            <template v-slot:activator="{ attrs, on }">
                              <v-text-field
                                class="white--text"
                                v-bind="attrs"
                                v-on="on"
                                outlined
                                dense
                                label="اختر منتجا"
                                v-if="editedItem.product"
                                v-model="editedItem.product.name"
                              >
                              </v-text-field>
                              <v-text-field
                                class="white--text"
                                v-bind="attrs"
                                v-on="on"
                                outlined
                                dense
                                label="اختر منتجا"
                                v-else
                                v-model="editedItem.product.name"
                              >
                              </v-text-field>
                            </template>

                            <v-list>
                              <v-list-item v-for="item in productsbanners" :key="item" link>
                                <v-list-item-title v-text="item.text" @click="getproduct(item)"></v-list-item-title>
                              </v-list-item>
                            </v-list>
                          </v-menu>
                        </v-col>
                        <v-col cols="12" md="6" lg="6" xl="6" class="">
                          <v-select
                            outlined
                            dense
                            label="حالة الظهور"
                            :items="statuses"
                            v-model="editedItem.status"
                          ></v-select>
                        </v-col>
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
                      </v-row>
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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getbanners()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import { VueEditor } from 'vue2-editor'

export default {
  components: {
    VueEditor,
  },
  data() {
    return {
      photo: null,
      dialog: false,
      banners: [],
      products: [],
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

        product_id: null,
        title: null,
        description: null,
        status: null,
        photo_url: null,
      },
      defaultItem: {
        product: {
          name: null,
        },

        product_id: null,
        title: null,
        description: null,
        status: null,
        photo_url: null,
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
      productsbanners: [],
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
    photo(val) {
      if (val) {
        let reader = new FileReader()
        reader.addEventListener('load', e => {
          this.editedItem.photo_url = e.target.result
        })
        reader.readAsDataURL(val)
      } else {
        this.editedItem.photo_url = null
      }
    },
    'editedItem.product.name': {
      handler: function (val) {
        if (val) {
          this.$http.get(`admin/products/search/${val}`).then(res => {
            res.data.data.forEach(product => {
              //   this.productsbanners = []
              this.productsbanners.push({
                text: product.name,
                value: product.id,
              })
            })
          })
        } else {
          this.productsbanners = []
        }
      },
    },

    dialog(val) {
      val || this.close()
    },
  },
  created() {
    this.getbanners()
    this.getproducts()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    trimAttribute(value, size) {
      if (value !== null) {
        let new_url = value.slice(0, 13) + '/thumbnail/' + value.slice(14)
        let index = new_url.length - 4
        let url = new_url.slice(0, index) + size + new_url.slice(index)
        return url.substr(0, url.length)
      }
    },

    showTrash() {
      this.$router.push('/trash-banners-management')
    },
    getproduct(item) {
      this.product_id = item.value
      this.editedItem.product.name = item.text
    },

    getproducts() {
      this.$http
        .get('admin/products')
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

    getbanners() {
      this.product_id = this.$route.params.product_id
      this.$http
        .get(`admin/banners/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.banners = res.data.data.data
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
      if (this.product_id === null || this.product_id === undefined) {
        this.product_id = this.editedItem.product_id
      }
      let formData = []

      formData = new FormData()
      formData.append('image', this.photo)
      formData.append('product_id', this.product_id)
      formData.append('title', this.editedItem.title)
      formData.append('status', this.editedItem.status)
      formData.append('description', this.editedItem.description)

      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/banners/update/${this.editedItem.id}`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              'X-Requested-With': 'XMLHttpRequest',
            },
          })
          .then(res => {
            this.close()

            Object.assign(this.banners[this.editedIndex], res.data.data)
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            }
          })
      } else {
        this.$http
          .post(`admin/banners/store`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              'X-Requested-With': 'XMLHttpRequest',
            },
          })

          .then(res => {
            this.close()
            this.banners.push(res.data.data)

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
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
      this.editedIndex = this.banners.indexOf(item)
      Object.assign(this.editedItem, {
        ...item,
      })
      this.banners[this.editedIndex].image = this.editedItem.image

      this.dialog = true
    },
    createItem() {
      this.dialog = true
    },

    deleteImage(img) {
      this.$http
        .get(`admin/banners/delete-image/${img.id}`)

        .then(res => {
          const index = this.imgs.indexOf(img)
          this.imgs.splice(index, 1)
          this.base_imgs.splice(index, 1)
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
    deleteItem(item) {
      const index = this.banners.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/banners/destroy/${item.id}`)

          .then(res => {
            this.banners.splice(index, 1)
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

      // this.$nextTick(() => {
      this.editedItem = Object.assign({}, this.defaultItem)
      this.editedIndex = -1
      // })
    },
  },
}
</script>
