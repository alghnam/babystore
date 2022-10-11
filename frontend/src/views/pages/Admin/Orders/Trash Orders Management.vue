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
              <th class="text-right text-uppercase">الخدمة</th>
              <th class="text-right text-uppercase">وسيلة الدفع</th>
              <th class="text-right text-uppercase">العناوين</th>
              <th class="text-right text-uppercase">الكوبون</th>
              <th class="text-right text-uppercase">الشحن</th>
              <th class="text-right text-uppercase">عدد المنتجات</th>
              <th class="text-right text-uppercase">السعر</th>
              <th class="text-right text-uppercase">حالة الظهور</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in orders" :key="item.id">
              <td class="text-right">{{ item.order_num }}</td>
              <td class="text-right">
                {{ item.user ? item.user.first_name : '-' }} . ' '. {{ item.user ? item.user.last_name : '-' }}
              </td>

              <td class="text-center">
                {{ item.service ? item.service.period : '-' }} . 'vlaue:' .
                {{ item.service ? item.service.value : '-' }}
              </td>
              <td class="text-right">
                {{ item.payment ? item.payment.name : '-' }}
              </td>
              <td class="text-right">
                {{ item.addresses ? item.addresses : '-' }}
              </td>
              <td class="text-right">
                {{ item.couponcode ? item.couponcode.name : '-' }}
              </td>
              <td class="text-right">{{ item.shipping }}</td>
              <td class="text-right">{{ item.products_count }}</td>
              <td class="text-right">{{ item.price }}</td>

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
          this.callMessage(res.data.message)
        })
        .catch(error => {
          if (error && error.response) {
            this.callMessage(error.response.data.message)
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
            this.callMessage(res.data.message)
          }
        })
        .catch(error => {
          if (error && error.response) {
            this.callMessage(error.response.data.message)
          }
        })
    },
    restoreAll() {
      this.$http
        .get('admin/orders/restore-all')
        .then(res => {
          if (res.data.message != null) {
            this.orders = []
            this.callMessage(res.data.message)
          }
        })
        .catch(error => {
          if (error && error.response) {
            this.callMessage(error.response.data.message)
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
            this.callMessage(res.data.message)
          })
          .catch(error => {
            if (error && error.response) {
              this.callMessage(error.response.data.message)
            }
          })
    },
  },
}
</script>
