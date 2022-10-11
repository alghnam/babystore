<template>
  <div>
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="restoreAll()">
      استعادة الكل
      <v-icon class="mr-3">mdi-reply-all</v-icon>
    </v-btn>
    <v-snackbar v-model="snackbar" :color="color">
      {{ text }}
      <template v-slot:action="{ attrs }">
        <v-btn color="pink" text v-bind="attrs" @click="snackbar = false"> اغلاق </v-btn>
      </template>
    </v-snackbar>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">الاسم</th>
              <th class="text-right text-uppercase">الفئة الرئيسية</th>
              <th class="text-right text-uppercase">الفئة الفرعية</th>
              <th class="text-right text-uppercase">الوصف</th>
              <th class="text-right text-uppercase">الكمية</th>
              <th class="text-right text-uppercase">عداد الخصم</th>
              <th class="text-right text-uppercase">السعر الأصلي قبل الخصم</th>
              <th class="text-right text-uppercase">السعر بعد الخصم</th>
              <th class="text-right text-uppercase">السعر النهائي بعد الخصم</th>
              <th class="text-right text-uppercase">حالة الظهور</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="item in products" :key="item.id">
              <td class="text-right">{{ item.name }}</td>
              <td class="text-right">
                {{ item.category ? (item.category.main_category ? item.category.main_category.name : null) : null }}
              </td>
              <td class="text-right">
                {{ item.category ? item.category.name : null }}
              </td>

              <td class="text-right">
                {{ item.quantity }}
              </td>
              <td class="text-right">
                {{ item.counter_discount }}
              </td>
              <td class="text-right">
                {{ item.original_price }}
              </td>
              <td class="text-right">
                {{ item.price_after_discount }}
              </td>
              <td class="text-right">
                {{ item.price_discount_ends }}
              </td>

              <td class="text-right">
                {{ item.description }}
              </td>

              <td class="text-right">
                {{ item.original_status }}
              </td>
              <td class="text-right">
                <v-btn color="primary" class="mt-6" @click="restoreItem(item)"> استعادة </v-btn>

                <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </template>

        <template v-slot:top>
          <v-toolbar flat color="white"> Trash products Management </v-toolbar>
        </template>
      </v-simple-table>
    </v-col>
    <template>
      <v-pagination
        v-model="page"
        :length="pageInfo && pageInfo.last_page"
        @input="getproducts()"
        circle
      ></v-pagination>
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
      products: [],

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
    this.getproducts()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    getproducts() {
      this.$http
        .get(`admin/products/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.products = res.data.data.data
          this.pageInfo = res.data.data
          this.callMessage(res.data.message)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },

    restoreItem(item) {
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/products/restore/${this.editedItem.id}`)
        .then(res => {
          const index = this.products.indexOf(item)
          this.products.splice(index, 1)
          this.callMessage(res.data.message)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },
    restoreAll() {
      this.$http
        .get('admin/products/restore-all')
        .then(res => {
          this.products = []
          this.callMessage(res.data.message)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },

    deleteItem(item) {
      const index = this.products.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/products/force-delete/${item.id}`)
          .then(res => {
            this.callMessage(res.data.message)
          })
          .catch(error => {
            this.callMessage(error.response.data.message)
          })
      this.products.splice(index, 1)
    },
  },
}
</script>
