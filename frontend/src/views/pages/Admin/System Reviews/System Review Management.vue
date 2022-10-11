<template>
  <div>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">اسم المستخدم</th>
              <th class="text-right text-uppercase">نوع التقييم</th>
              <th class="text-right text-uppercase">الاسم</th>
              <th class="text-right text-uppercase">المحتوى</th>
              <th class="text-right text-uppercase">الايميل</th>
              <th class="text-right text-uppercase">حالة الظهور</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in reviews" :key="item.id">
              <td class="text-right">{{ item.user ? item.user.first_name : '-' }}</td>
              <td class="text-right">{{ item.system_review_type ? item.system_review_type.name : '-' }}</td>

              <td class="text-right">
                {{ item.name ? item.name : null }}
              </td>

              <td class="text-right">
                {{ item.body ? item.body : null }}
              </td>

              <td class="text-right">
                {{ item.email ? item.email : null }}
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
            ادارة تقييمات على النظام
            <v-dialog v-model="dialog">
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.user_id }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon dark large>mdi-account-circle</v-icon> ادارة تقييمات على النظام
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
      editedIndex: -1,
      editedItem: {
        first_name: null,
        name: null,
        body: null,
        email: null,
        status: null,
      },
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
      //    this.productsSimilar.push(item)
      this.editedItem.product.name = item.text
    },

    getreviews() {
      this.$http
        .get(`admin/system-reviews/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.reviews = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },

    async save() {
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/system-reviews/update/${this.editedItem.id}`, {
            status: this.editedItem.status,
          })

          .then(res => {
            this.dialog = false
            Object.assign(this.reviews[this.editedIndex], {
              original_status: res.data.data.original_status,
            })

            this.callMessage(res.data.message)
          })
          .catch(error => {
            this.callMessage(error.response.data.message)
          })
      }
    },
    editItem(item) {
      Object.assign(this.editedItem, {
        ...item,
      })
      this.dialog = true
      this.editedIndex = this.reviews.indexOf(item)
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.reviews.indexOf(item)
      confirm('Are You Sure To Delete This Item ?') &&
        this.$http
          .get(`admin/system-reviews/destroy/${item.id}`)

          .then(res => {
            this.reviews.splice(index, 1)
            this.callMessage(res.data.message)
          })
          .catch(error => {
            this.callMessage(error.response.data.message)
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
