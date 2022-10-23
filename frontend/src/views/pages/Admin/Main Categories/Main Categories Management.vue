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
              <th class="text-right text-uppercase">الاسم</th>
              <th class="text-right text-uppercase">حالة الظهور</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="item in categories" :key="item.id">
              <td class="text-right">{{ item.name }}</td>

              <td class="text-right">
                {{ item.original_status }}
              </td>

              <td class="text-right">
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
            ادارة الفئات الرئيسية
            <v-dialog v-model="dialog">
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
                      <v-alert
                        class="col-sm-12 mx-auto font-2 text-center pr-0 pl-0 rounded-tr-xl rounded-bl-xl"
                        dark
                        color="primary"
                      >
                        <v-icon large>mdi-account-circle</v-icon> ادارة الفئات الرئيسية
                      </v-alert>
                    </v-card-title>
                    <v-card-text>
                      <v-row>
                        <v-col cols="12" md="6" lg="6" xl="6" class="mx-auto">
                          <v-text-field outlined dense label="الاسم" v-model="editedItem.name"></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" lg="6" xl="6" class="mx-auto">
                          <v-select
                            outlined
                            dense
                            label="حالة الظهور"
                            :items="statuses"
                            v-model="editedItem.status"
                          ></v-select>
                        </v-col>

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
        @input="getcategories()"
        circle
        class="mx-auto"
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
      categories: [],
      categoriesCol: [],
      mainCategorieso: [],
      roles: [],
      send_roles: [],
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
      features: [
        {
          text: 'featured',
          value: '1',
        },
        {
          text: 'not featured',
          value: '0',
        },
      ],
      editedIndex: -1,
      editedItem: {
        main_category: {
          id: null,
        },
        status: null,
      },
      defaultItem: {
        name: null,
        status: null,
        photo: null,
        maincategory: null,
        category_id: null,
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
    'editedItem.main_category.name': {
      handler: function (val) {
        this.editedItem.main_category.id = val
      },
    },
  },
  created() {
    this.getcategories()
    this.getMainCategories()
    // this.getcategoriesCol()
    // this.status = 'inactive'
    // this.featured = 'not featured'
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-main-categories-management')
    },
    getcategories() {
      this.$http
        .get(`admin/categories/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.categories = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
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
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    save() {
      let dum_categories = []
      if (this.editedItem.categories) {
        this.editedItem.categories.forEach(category => {
          dum_categories.push(category.value)
        })
      }

      this.editedItem.categories = dum_categories
 

      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/categories/update/${this.editedItem.id}`, {
            name: this.editedItem.name,
            status: this.editedItem.status,
          })
          .then(res => {
            this.dialog = false
            Object.assign(this.categories[this.editedIndex], res.data.data)

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          })
      } else {
        this.$http
          .post('admin/categories/store', {
            name: this.editedItem.name,
            status: this.editedItem.status,
          })

          .then(res => {
            this.dialog = false
            this.categories.push(res.data.data)

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
    showCategoriesProduct(item) {
      this.$router.push(`/products-management/${item.id}`)
    },
    editItem(item) {
      this.dialog = true
      this.editedIndex = this.categories.indexOf(item)
      Object.assign(this.editedItem, {
        ...item,
      })
    },
    showSubCategories(item) {
      this.$router.push(`/sub-categories-management/${item.id}`)
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.categories.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/categories/destroy/${item.id}`)

          .then(res => {
            this.categories.splice(index, 1)
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
