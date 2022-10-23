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
              <th class="text-right text-uppercase">النوع</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in payments" :key="item.id">
              <td class="text-right">{{ item.name }}</td>
              <td class="text-right">
                {{ item.original_type }}
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
            ادارة وسائل الدفع
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
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon large>mdi-account-circle</v-icon> ادارة وسائل الدفع
                      </v-alert>
                    </v-card-title>
                    <v-card-text>
                      <div class="row">
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="الاسم"
                          v-model="editedItem.name"
                        ></v-text-field>
                        <v-select
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="النوع"
                          :items="types"
                          v-model="editedItem.type"
                        ></v-select>
                        <!-- <v-select
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="حالة الظهور"
                          :items="statuses"
                          v-model="editedItem.status"
                        ></v-select> -->

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
        @input="getpayments()"
        circle
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
      payments: [],
      types: [
        {
          text: 'Private',
          value: '1',
        },
        {
          text: 'Public',
          value: '0',
        },
      ],
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

      order_id: null,
      editedIndex: -1,
      editedItem: {
        name: null,
        type: null,
        status: null,
      },
      defaultItem: {
        name: null,
        type: null,
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
    this.getpayments()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-payments-management')
    },
    getpayments() {
      this.$http
        .get(`admin/payments/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.payments = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },

    save() {
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/payments/update/${this.editedItem.id}`, {
            name: this.editedItem.name,
            type: this.editedItem.type,
            status: 1,
          })
          .then(res => {
            this.dialog = false

            Object.assign(this.payments[this.editedIndex], res.data.data)

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            this.dialog = false

            }
          })
      } else {
        this.$http
          .post('admin/payments/store', {
            name: this.editedItem.name,
            type: this.editedItem.type,
            status: 1,
          })

          .then(res => {
            this.dialog = false
            this.payments.push(res.data.data)

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

      this.editedIndex = this.payments.indexOf(item)
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.payments.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/payments/destroy/${item.id}`)

          .then(res => {
            this.payments.splice(index, 1)
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
