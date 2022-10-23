<template>
  <div>
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="showTrash()">
      سلة المحذوفات
      <v-icon class="mr-3">mdi-delete</v-icon>
    </v-btn>

    <v-btn color="primary" outlined class="mr-auto mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="createItem()">
      انشاء
      <v-icon class="mr-3">mdi-plus</v-icon>
    </v-btn>

    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">الاسم</th>
              <th class="text-right text-uppercase">الفئة الرئيسية</th>
              <th class="text-right text-uppercase">الفئة الفرعية</th>
              <th class="text-right text-center text-uppercase">عليه عروض</th>
              <th class="text-right text-center text-uppercase">السعر النهائي بعد الخصم</th>
              <th class="text-right text-center text-uppercase">الكمية</th>
              <th class="text-right text-center text-uppercase">السعر الأصلي</th>
              <th class="text-right text-uppercase">الوصف</th>
              <th class="text-right text-center text-uppercase">حالة الظهور</th>
              <th class="text-right text-center text-uppercase">الاحداث</th>
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
                {{ item.original_is_offers }}
              </td>
              <td class="text-right">
                {{ item.price_discount_ends }}
              </td>
              <td class="text-right">
                {{ item.quantity }}
              </td>
              <td class="text-right">
                {{ item.original_price }}
              </td>

              <td class="text-right" v-html="item.description"></td>

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
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      products: [],
      category_id: null,
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
    showTrash() {
      this.$router.push('/trash-products-management')
    },

    getproducts() {
      this.category_id = this.$route.params.category_id
      this.$http
        .get(`admin/products/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.products = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    editItem(item) {
      this.$router.push(`/edit-product/${item.id}`)
    },
    createItem() {
      this.$router.push('/create-product')
    },
    deleteItem(item) {
      const index = this.products.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') && this.$http.get(`admin/products/destroy/${item.id}`)

        .then(res => {
            this.products.splice(index, 1)
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message

        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
  },
}
</script>
