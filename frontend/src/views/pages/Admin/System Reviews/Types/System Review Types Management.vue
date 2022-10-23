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
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in reviewtypes" :key="item.id">
              <td class="text-right">
                {{ item.name ? item.name : null }}
              </td>

              <td class="text-right">
                <div>
                  <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="editItem(item)">
                    <v-icon color="black" class="white--text">mdi-pencil</v-icon>
                  </v-btn>
                  <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                    <v-icon color="black" class="">mdi-delete</v-icon>
                  </v-btn>
                </div>
              </td>
            </tr>
          </tbody>
        </template>

        <template v-slot:top>
          <v-toolbar flat color="white">
            ادارة انواع تقييمات النظام

            <v-dialog v-model="dialog">
              <template v-slot:activator="{ on, attrs }">
                <v-btn color="primary" dark class="rounded-lg mr-auto" v-bind="attrs" v-on="on" fab tile x-small
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
                        <v-icon dark large>mdi-account-circle</v-icon> ادارة انواع تقييمات النظام
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
        @input="getreviewtypes()"
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
      reviewtypes: [],
      editedIndex: -1,
      editedItem: {
        first_name: null,
        name: null,
        body: null,
        status: null,
        email: null,
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
    this.getreviewtypes()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
        showTrash() {
      this.$router.push('/trash-system-review-types-management')
    },

    getproduct(item) {
      this.product_id = item.value
      this.editedItem.product.name = item.text
    },

    getreviewtypes() {
      this.$http
        .get(`admin/system-review-types/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.reviewtypes = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    async save() {
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/system-review-types/update/${this.editedItem.id}`, {
            name: this.editedItem.name,

            status: 1,
          })

          .then(res => {
            this.dialog = false

            Object.assign(this.reviewtypes[this.editedIndex], res.data.data)

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
          .post('admin/system-review-types/store', {
            name: this.editedItem.name,

            status: 1,
          })

          .then(res => {
            this.dialog = false
            this.reviewtypes.push(res.data.data)

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
      this.editedIndex = this.reviewtypes.indexOf(item)
      Object.assign(this.editedItem, {
        ...item,
      })

      this.dialog = true
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.reviewtypes.indexOf(item)
      confirm('Are You Sure To Delete This Item ?') &&
        this.$http
          .get(`admin/system-review-types/destroy/${item.id}`)

          .then(res => {
            this.reviewtypes.splice(index, 1)
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
