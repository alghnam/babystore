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
              <th class="text-right text-uppercase">اسم العميل</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in carts" :key="item.id">
              <td class="text-right">
                {{ item.user ? item.user.first_name : null }}
              </td>

              <td class="text-right">
                <v-btn color="default" class="mt-6" @click="showProducts(item)"> عرض المنتجات </v-btn>

                <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </template>

        <template v-slot:top>
          <v-toolbar flat color="white">
            ادارة السلات
            <v-dialog v-model="dialog">
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.name }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon large>mdi-account-circle</v-icon> ادارة السلات
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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getcarts()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      carts: [],
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
      editedItem: {
        status: null,
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
    dialog(val) {
      val || this.close()
    },
  },
  created() {
    this.getcarts()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    editItem(item) {
      this.dialog = true

      this.editedIndex = this.carts.indexOf(item)
      Object.assign(this.editedItem, {
        ...item,
      })
    },

    showTrash() {
      this.$router.push('/trash-carts-management')
    },
    showProducts(item) {
      this.$router.push(`products-cart/${item.id}`)
    },

    getcarts() {
      this.$http
        .get(`admin/carts/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.carts = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    save() {
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/carts/update/${this.editedItem.id}`, {
            status: this.editedItem.status,
          })
          .then(res => {
            this.dialog = false

            Object.assign(this.carts[this.editedIndex], {
              original_status: res.data.data.original_status,
            })
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

    deleteItem(item) {
      const index = this.carts.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/carts/destroy/${item.id}`)

          .then(res => {
            this.carts.splice(index, 1)
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
