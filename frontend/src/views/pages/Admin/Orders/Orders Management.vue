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
              <th class="text-right text-uppercase">رقم الطلب</th>
              <th class="text-right text-uppercase">المستحدم</th>
              <th class="text-right text-uppercase">الخدمة</th>
              <th class="text-right text-uppercase">وسيلة الدفع</th>
              <th class="text-right text-uppercase">الدولة</th>
              <th class="text-right text-uppercase">المدينة</th>
              <th class="text-right text-uppercase">المنطقة</th>
              <th class="text-right text-uppercase">الكوبون</th>
              <th class="text-right text-uppercase">السعر</th>
              <th class="text-right text-uppercase">حالة الطلب</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in orders" :key="item.id">
              <td class="text-right">{{ item.order_num }}</td>
              <td class="text-right">
                {{ item.user ? item.user.first_name : null }}
              </td>

              <td class="text-right">
                {{ item.service ? item.service.period : null }}
              </td>
              <td class="text-right">
                {{ item.payment ? item.payment.name : null }}
              </td>
              <td class="text-right">
                {{ item.address ? item.address.country.name : null }}
              </td>
                            <td class="text-right">
                {{ item.address ? item.address.city.name : null }}
              </td>
                            <td class="text-right">
                {{ item.address ? item.address.town.name : null }}
              </td>
              <td class="text-right">
                {{ item.couponcode ? item.couponcode.name : null }}
              </td>
              <td class="text-right">{{ item.price }}</td>

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
            ادارة الاوردرات
            <v-dialog v-model="dialog">
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.name }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon large>mdi-account-circle</v-icon> ادارة الاوردرات
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
                          :disabled="editedItem.status == 'Shipping' ? true : false"
                          hide-selected
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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getorders()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      orders: [],
      orderNum: null,
      orders: [],

      statuses: [
        {
          text: 'hanging',
          value: '0',
        },
        {
          text: 'Being processed',
          value: '1',
          disabled: true,
        },
        {
          text: 'Shipping',
          value: '2',
          disabled: true,
        },
        {
          text: 'sent delivered handed',
          value: '3',
        },
        {
          text: 'canceled',
          value: '-1',
        },
      ],

      order_id: null,
      editedIndex: -1,
      editedItem: {
        id: null,
        name: null,
        value: null,
        order: {
          id: null,
          order_num: null,
        },
        status: null,
      },
      defaultItem: {},
      status: 0,
      snackbar: false,
      text: null,
      color: null,

      total: 0,
      pageInfo: null,
      page: 1,
    }
  },
  computed: {},
  watch: {
    dialog(val) {
      val || this.close()
    },
  },
  created() {
    this.getorders()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-orders-management')
    },
    getorders() {
      this.$http
        .get(`admin/orders/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.orders = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar=true
          this.$store.state.text = error.response.data.message
          }
        })
    },

    save() {
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/orders/update/${this.editedItem.id}`, {
            status: this.editedItem.status,
          })
          .then(res => {
            if (res.data.message != null) {
              this.dialog = false
              Object.assign(this.orders[this.editedIndex], {
                original_status: res.data.data.original_status,
              })

              this.$store.state.snackbar=true
          this.$store.state.text = res.data.message
            } else {
              this.noDataInYourEntering()
            }
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar=true
          this.$store.state.text = error.response.data.message
            }
          })
      }
    },
    editItem(item) {
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })

      this.editedIndex = this.orders.indexOf(item)
    },

    deleteItem(item) {
      const index = this.orders.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/orders/destroy/${item.id}`)

          .then(res => {
            if (res.data.message != null) {
              this.orders.splice(index, 1)
              this.$store.state.snackbar=true
          this.$store.state.text = res.data.message
            }
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar=true
          this.$store.state.text = error.response.data.message
            }
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
