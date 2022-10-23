<template>
  <div>
    <v-btn color="primary" class="mt-6" @click="restoreAll()"> Restore All </v-btn>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">title</th>
              <th class="text-right text-uppercase">description</th>
              <th class="text-right text-uppercase">product name</th>
              <th class="text-right text-uppercase">Status</th>
              <th class="text-right text-uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in banners" :key="item.id">
              <td class="text-right">{{ item.title }}</td>
              <td class="text-right">{{ item.description }}</td>
              <td class="text-right">
                {{ item.product ? item.product.name : null }}
                {{ item.image ? item.image.url : null }}
              </td>
              <td class="text-right">
                {{ item.original_status }}
              </td>
              <td class="text-right">
                <div>
                  <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                    <v-icon color="black" class="">mdi-delete</v-icon>
                  </v-btn>

                  <!-- <v-btn color="default" class="mt-6" @click="deleteItem(item)"> Delete </v-btn> -->
                </div>
              </td>
            </tr>
          </tbody>
        </template>

        <template v-slot:top>
          <v-toolbar flat color="white">
            سلة محذوفات ادارة البانرز
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
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="black">
                        <v-icon dark large>mdi-account-circle</v-icon> سلة محذوفات الاشعارات
                      </v-alert>
                    </v-card-title>
                    <v-card-text>
                      <div class="row">
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="user_id"
                          v-model="editedItem.user_id"
                        ></v-text-field>
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="product_attribute_id"
                          v-model="editedItem.product_attribute_id"
                        ></v-text-field>
                        <v-select
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="status"
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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getbanners()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  setup() {
    const statusColor = {
      /* eslint-disable key-spacing */
      Current: 'primary',
      Professional: 'success',
      Rejected: 'error',
      Resigned: 'warning',
      Applied: 'info',
      /* eslint-enable key-spacing */
    }
    return {
      statusColor,
    }
  },
  data() {
    return {
      dialog: null,
      banners: [],

      editedIndex: -1,
      editedItem: {},
      defaultItem: {},
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
    this.getbanners()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    getbanners() {
      this.$http
        .get(`admin/banners/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.banners = res.data.data.data
          this.pageInfo = res.data.data
          this.callMessage(res.data.message)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },

    restoreItem(item) {
      this.editedIndex = this.banners.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/banners/restore/${this.editedItem.id}`)
        .then(res => {
          const index = this.banners.indexOf(item)
          this.banners.splice(index, 1)
          this.callMessage(res.data.message)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },
    restoreAll() {
      this.$http
        .get('admin/banners/restore-all')
        .then(res => {
          this.banners = []
          this.callMessage(res.data.message)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.banners.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/banners/force-delete/${item.id}`)
          .then(res => {
            this.banners.splice(index, 1)
            this.callMessage(res.data.message)
          })
          .catch(error => {
            this.callMessage(error.response.data.message)
          })
    },
  },
}
</script>
