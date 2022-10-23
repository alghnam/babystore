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
              <th class="text-right text-uppercase">القيمة</th>
              <th class="text-right text-uppercase">رقم الطلب التابع له</th>
              <th class="text-right text-uppercase">تاريخ الانتهاء</th>
              <th class="text-right text-uppercase">حالة الاستخدام</th>
              <th class="text-right text-uppercase">حالة الظهور</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in coupons" :key="item.id">
              <td class="text-right">{{ item.name }}</td>
              <td class="text-right">
                {{ item.value }}
              </td>

              <td class="text-right">
                {{ item.order ? (item.order.order_num.text ? item.order.order_num.text : item.order.order_num) : null }}
              </td>
              <td class="text-right">
                {{ item.end_date }}
              </td>
              <td class="text-right">
                {{ item.original_is_used }}
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
            ادارة الكوبونات
            <v-dialog v-model="dialog">
              <template v-slot:activator="{ on, attrs }">
                <v-btn color="primary" dark class="rounded-lg mr-auto" v-bind="attrs" v-on="on" fab tile x-small
                  ><v-icon>mdi-plus-circle</v-icon></v-btn
                >
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon large>mdi-account-circle</v-icon> ادارة الكوبونات
                      </v-alert>
                    </v-card-title>
                    <v-card-text>
                      <div class="row">
                        <template>
                          <v-row>
                            <v-col cols="12" sm="6" md="4">
                              <v-menu
                                ref="menu"
                                v-model="menu"
                                :close-on-content-click="false"
                                :return-value.sync="date"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-text-field
                                    v-model="editedItem.end_date"
                                    label="Picker in menu"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                  ></v-text-field>
                                </template>
                                <v-date-picker v-model="editedItem.end_date" no-title scrollable>
                                  <v-spacer></v-spacer>
                                  <v-btn text color="primary" @click="menu = false"> Cancel </v-btn>
                                  <v-btn text color="primary" @click="$refs.menu.save(editedItem.end_date)"> OK </v-btn>
                                </v-date-picker>
                              </v-menu>
                            </v-col>
                          </v-row>
                        </template>
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="الاسم"
                          v-model="editedItem.name"
                        ></v-text-field>
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="القيمة"
                          v-model="editedItem.value"
                          type="number"
                        ></v-text-field>

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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getcoupons()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      coupons: [],

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

      editedIndex: -1,
      editedItem: {
        id: null,
        name: null,
        value: null,
        end_date: null,
        status: null,
      },
      defaultItem: {
        id: null,
        name: null,
        value: null,
        end_date: null,
        status: null,
      },
      snackbar: false,
      text: null,
      color: null,

      total: 0,
      pageInfo: null,
      page: 1,
      menu: false,
    }
  },
  computed: {},
  watch: {
    dialog(val) {
      val || this.close()
    },
  },
  created() {
    this.getcoupons()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-coupons-management')
    },
    getcoupons() {
      this.$http
        .get(`admin/coupons/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.coupons = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    save() {
      let formData = []
      formData = new FormData()

      formData.append('status', this.editedItem.status)

      formData.append('name', this.editedItem.name)
      formData.append('value', this.editedItem.value)
      formData.append('end_date', this.editedItem.end_date)

      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/coupons/update/${this.editedItem.id}`, formData, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
            },
          })
          .then(res => {
            this.dialog = false
            Object.assign(this.coupons[this.editedIndex], res.data.data)

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
          .post('admin/coupons/store', formData, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
            },
          })

          .then(res => {
            this.dialog = false

            this.coupons.push(res.data.data)

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
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })

      this.editedIndex = this.coupons.indexOf(item)
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.coupons.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/coupons/destroy/${item.id}`)

          .then(res => {
            this.coupons.splice(index, 1)
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
        this.editedIndex = -1
      })
    },
  },
}
</script>
