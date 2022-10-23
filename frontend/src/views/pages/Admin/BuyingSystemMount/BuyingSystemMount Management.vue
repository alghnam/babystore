<template>
  <div>
 

    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">المبلغ</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in buyingSystemMounts" :key="item.id">
              <td class="text-right">{{ item.mount }}</td>

              <td class="text-right">
                <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="editItem(item)">
                  <v-icon color="black" class="white--text">mdi-pencil</v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </template>

        <template v-slot:top>
          <v-toolbar flat color="white">
            ادارة محفظة النظام
            <v-dialog v-model="dialog">
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.name }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon large>mdi-account-circle</v-icon> ادارة محفظة النظام
                      </v-alert>
                    </v-card-title>
                    <v-card-text>
                      <div class="row">
                        <v-col cols="12" md="6" lg="6" xl="6" class="mx-auto">
                          <v-text-field
                            class="col-sm-5 mx-auto"
                            outlined
                            dense
                            label="المبلغ"
                            v-model="editedItem.mount"
                          ></v-text-field>
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
        @input="getbuyingSystemMounts()"
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
      buyingSystemMounts: [],

      editedIndex: -1,
      editedItem: {
        mount: null,
        status,
      },
      defaultItem: {
        mount: null,
        status,
      },
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
    this.getbuyingSystemMounts()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-wallet-system-management')
    },
    getbuyingSystemMounts() {
      this.$http
        .get(`admin/buying-system-mounts/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.buyingSystemMounts = res.data.data.data

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
          .post(`admin/buying-system-mounts/update/${this.editedItem.id}`, {
            mount: this.editedItem.mount,
          })

          .then(res => {
            this.dialog = false
            if (this.editedItem.status.text) {
              Object.assign(this.buyingSystemMounts[this.editedIndex], {
                mount: this.editedItem.mount,
              })
            } else {
              Object.assign(this.buyingSystemMounts[this.editedIndex], {
                mount: this.editedItem.mount,
              })
            }
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          })
      }
    },
    editItem(item) {
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })

      this.editedIndex = this.buyingSystemMounts.indexOf(item)
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
