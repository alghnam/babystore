<template>
  <div>
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="restoreAll()">
      استعادة الكل
      <v-icon class="mr-3">mdi-reply-all</v-icon>
    </v-btn>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
                <th class="text-right text-uppercase">رقم الطلب</th>
              <th class="text-right text-uppercase">المستحدم</th>

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
                {{ item.original_status }}
              </td>
              <td>
                <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="restoreItem(item)">
                  <v-icon class="mr-3">mdi-reply-all</v-icon>
                </v-btn>
                <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </template>

        <template v-slot:top>
          <v-toolbar flat color="white"> سلة محذوفات ادارة الاوردرات</v-toolbar>
        </template>
      </v-simple-table>
    </v-col>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getorders()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      orders: [],
      roles: [],
      send_roles: [],
      permissions: [],
      send_permissions: [],
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

  watch: {},
  created() {
    this.getorders()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    getorders() {
      this.$http
        .get(`admin/orders/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.orders = res.data.data.data
          this.pageInfo = res.data.data
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

    restoreItem(item) {
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/orders/restore/${this.editedItem.id}`)
        .then(res => {
          if (res.data.message != null) {
            const index = this.orders.indexOf(item)
            this.orders.splice(index, 1)
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          }
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    restoreAll() {
      this.$http
        .get('admin/orders/restore-all')
        .then(res => {
          if (res.data.message != null) {
            this.orders = []
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          }
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },

    deleteItem(item) {
      const index = this.orders.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/orders/force-delete/${item.id}`)
          .then(res => {
            this.orders.splice(index, 1)
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
