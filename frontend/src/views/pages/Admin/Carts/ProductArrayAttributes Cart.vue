<template>
  <div>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">اسم المنتج</th>
              <th class="text-right text-uppercase">الكمية</th>
              <th class="text-right text-uppercase">مواصفاته</th>

              <th class="text-right text-uppercase">السعر النهائي بعد الخصم</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="item in products" :key="item.id">
              <td class="text-right">{{ item.product.name }}</td>

              <td class="text-right">
                {{ item.pivot.quantity }}
              </td>

              <td class="text-right">
                {{ item.attributes }}
              </td>

              <td class="text-right">
                {{ item.price_discount_ends }}
              </td>

              <td class="text-right"></td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-col>
    <template>
      <v-pagination
        v-model="page"
        :length="pageInfo && pageInfo.last_page"
        @input="getproductscart()"
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
      cart_id: null,
      orderNum: null,
      orders: [],
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
    this.getproductscart()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    getproductscart() {
      this.cart_id = this.$route.params.cart_id

      this.$http
        .get(
          `admin/carts/get-all-product-array-attributes-cart-paginates/${this.cart_id}?page=${this.page}&total=${this.total}`,
        )
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
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })

      this.editedIndex = this.products.indexOf(item)
    },
    createItem() {
      this.dialog = true
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
