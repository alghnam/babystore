<template>
  <div>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">المبلغ</th>
              <th class="text-right text-uppercase">المستخدم</th>
              <!-- <th class="text-right text-uppercase">الاحداث</th> -->
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in wallets" :key="item.id">
              <td class="text-right">{{ item.amount }}</td>

              <td class="text-right">
                {{ item.user ? item.user.first_name : null }}
              </td>

              <!-- <td>
                <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
              </td> -->
            </tr>
          </tbody>
        </template>
           <template v-slot:top>
          <v-toolbar flat color="white"> ادارة المحافظ</v-toolbar>
        </template>
      </v-simple-table>
    </v-col>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getwallets()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      wallets: [],

      userName: null,
      user_id: null,
      editedIndex: -1,
      editedItem: {
        amount: null,
        user: {
          id: null,
          first_name: null,
          last_name: null,
        },
        status: null,
      },
      defaultItem: {},
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
  computed: {},
  watch: {
    dialog(val) {
      val || this.close()
    },
  },
  created() {
    this.getwallets()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-wallets-management')
    },
    getwallets() {
      this.$http
        .get(`admin/wallets/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.wallets = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    save() {
      //edit route
      this.$http
        .post(`admin/wallets/update/${this.editedItem.id}`, {
          status: this.editedItem.status,
        })
        .then(res => {
          this.dialog = false
          Object.assign(this.wallets[this.editedIndex], res.data.data)

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
    editItem(item) {
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })

      this.editedIndex = this.wallets.indexOf(item)
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.wallets.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/wallets/destroy/${item.id}`)

          .then(res => {
            this.wallets.splice(index, 1)
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
