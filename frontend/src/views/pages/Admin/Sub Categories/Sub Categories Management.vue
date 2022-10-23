<template>
  <div>
    <v-row>
      <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="showTrash()">
        سلة المحذوفات
        <v-icon class="mr-3">mdi-delete</v-icon>
      </v-btn>
      <v-col cols="12" class="pb-3">
        <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-right text-uppercase">الاسم</th>
                <th class="text-right text-uppercase">الفئة الرئيسية</th>
                <th class="text-right text-uppercase">الصورة</th>
                <th class="text-right text-uppercase">حالة الظهور</th>
                <th class="text-right text-uppercase">الاحداث</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="item in sub_categories" :key="item.id">
                <td class="text-right">{{ item.name }}</td>
                <td class="text-right" v-if="item.parent_id !== null">
                  {{
                    item.main_category
                      ? item.main_category.name.text
                        ? item.main_category.name.text
                        : item.main_category.name
                      : null
                  }}
                </td>
                <td class="text-right">
                  <div v-if="item.image">
                    <img
                      style="width: 114px"
                      :src="$store.state.baseURL + '/storage/' + item.image.url"
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
            <v-toolbar flat color="white" class="rounded-xl">
              ادارة الفئات
              <v-dialog v-model="dialog" @click:outside="close()">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="primary" dark class="rounded-lg mr-auto" v-bind="attrs" v-on="on" fab tile x-small
                    ><v-icon>mdi-plus-circle</v-icon></v-btn
                  >
                </template>

                <template v-slot:expanded-item="{ headers, item }">
                  <td :colspan="headers.length">More info about {{ item.name }}</td>
                </template>

                <div class="container">
                  <div class="row">
                    <v-card class="col-sm-7 mx-auto">
                      <v-card-title>
                        <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                          <v-icon dark large>mdi-account-circle</v-icon> ادارة الفئات الفرعية
                        </v-alert>
                      </v-card-title>
                      <v-card-text>
                        <v-row>
                          <v-col xs="12" sm="12" md="3" lg="3" xl="3" class="mx-auto"> </v-col>
                          <v-col xs="12" sm="12" md="6" lg="6" xl="6" class="mx-auto">
                            <v-img
                              :src="
                                editedItem.image
                                  ? $store.state.baseURL + '/storage/' + trimAttribute(editedItem.image.url, '(S)')
                                  : ''
                              "
                              v-if="!photo"
                              height="200px"
                              width="200px"
                              class="mx-auto"
                            ></v-img>
                            <v-img :src="img" v-else-if="photo" height="200px" width="200px" class="mx-auto"></v-img>
                          </v-col>
                          <v-col xs="12" sm="12" md="3" lg="3" xl="3" class="mx-auto"> </v-col>
                          <v-col cols="12" md="6" lg="6" xl="6" class="mx-auto">
                            <v-text-field outlined dense label="الاسم" v-model="editedItem.name"></v-text-field>
                          </v-col>

                          <v-col xs="12" sm="12" md="6" lg="6" xl="6">
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
                            <v-text-field outlined dense label="الاسم" v-model="editedItem.name"></v-text-field>
                          </v-col>
                          <v-col cols="12" md="6" lg="6" xl="6" class="">
                            <v-select
                              v-if="editedItem.main_category"
                              outlined
                              dense
                              label="الفئة الرئيسية"
                              :items="mainCategorieso"
                              v-model="editedItem.main_category.id"
                            ></v-select>
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
        <v-pagination
          v-model="page"
          :length="pageInfo && pageInfo.last_page"
          @input="getsubcategories()"
          circle
          class="mx-auto"
        ></v-pagination>
      </template>
    </v-row>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      categories: [],
      nameCategory: null,
      sub_categories: [],
      categoriesCol: [],
      mainCategorieso: [],
      main_catgory_id: null,
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
      photo: null,
      editedIndex: -1,
      editedItem: {
        id: null,
        main_category: {
          id: 0,
          name: null,
        },
        status: null,
        photo_url: null,
        // photo: null,
        image: null,
      },
      img: null,
      defaultItem: {
        id: null,
        main_category: {
          id: 0,
          name: null,
        },
        status: null,
        photo_url: null,
        // photo: null,
        image: null,
      },
      status: 0,
      snackbar: false,
      text: null,
      color: null,
      total: 0,
      pageInfo: null,
      page: 1,
    }
  },
  watch: {
    photo(val) {
      if (val) {
        let reader = new FileReader()
        reader.addEventListener('load', e => {
          setTimeout(() => {
            this.editedItem.photo_url = e.target.result
            this.img = e.target.result
          }, 1000)
        })
        reader.readAsDataURL(val)
      } else {
        this.editedItem.photo_url = null
      }
    },
    'editedItem.main_category.name': {
      handler: function (val) {
        if (val !== undefined || val !== null) {
          this.editedItem.main_category.id = val
        }
      },
    },
  },
  created() {
    this.getsubcategories()
    this.getMainCategories()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-sub-categories-management')
    },
    trimAttribute(value, size) {
      if (value !== null) {
        let new_url = value.slice(0, 21) + '/thumbnail/' + value.slice(22)
        let index = new_url.length - 4
        let url = new_url.slice(0, index) + size + new_url.slice(index)
        return url.substr(0, url.length)
      }
    },
    getsubcategories() {
      this.$http
        .get(`admin/categories/get-first-sub-categories-paginate?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.sub_categories = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    getMainCategories() {
      this.$http
        .get('admin/categories/get-main-categories')
        .then(res => {
          res.data.data.forEach(mainCategory => {
            this.mainCategorieso.push({
              text: mainCategory.name,
              value: mainCategory.id,
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

    save() {
      let formData = []
      formData = new FormData()

      formData.append('status', this.editedItem.status)

      formData.append('name', this.editedItem.name)
      if (this.editedItem.main_category == undefined || this.editedItem.main_category.id == undefined) {
        this.editedItem.main_category.id = this.editedItem.parent_id
      }
      formData.append('parent_id', this.editedItem.main_category.id)
      formData.append('image', this.photo)
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/categories/update/${this.editedItem.id}`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              'X-Requested-With': 'XMLHttpRequest',
            },
          })
          .then(res => {
            this.close()
            Object.assign(this.sub_categories[this.editedIndex], res.data.data)
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
          .post('admin/categories/store', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              'X-Requested-With': 'XMLHttpRequest',
            },
          })
          .then(res => {
            this.close()
            this.sub_categories.push(res.data.data)

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
      this.editedIndex = this.sub_categories.indexOf(item)
      let id = Number(item.main_category.id)

      this.editedItem = {}
      this.editedItem = Object.assign(
        {},
        {
          ...item,
        },
      )
      setTimeout(() => {
        this.editedItem.main_category.id = id
      }, 1000)
      this.dialog = true
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.sub_categories.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/categories/destroy/${item.id}`)

          .then(res => {
            this.sub_categories.splice(index, 1)
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
        this.img = null
        this.photo = null
        this.editedIndex = -1
      })
    },
  },
}
</script>
