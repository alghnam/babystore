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
              <th class="text-right text-uppercase">المستخدم</th>
              <th class="text-right text-uppercase">المنتج</th>
              <th class="text-right text-uppercase">تمت مشاهدته بتاريخ</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in views" :key="item.id">
              <td class="text-center">
                {{ item.user ? item.user.first_name : null }}
              </td>
              <td class="text-center">
                {{ item.product ? item.product.name : null }}
              </td>
              <td class="text-right">{{ item.view_at }}</td>

              <td class="text-right">
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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getviews()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      views: [],

      userName: null,
      user_id: null,
      product_id: null,
      editedIndex: -1,
      editedItem: {
        amount: null,
        user: {
          id: null,
          first_name: null,
          last_name: null,
        },
        product: {
          id: null,
          name: null,
        },
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
    this.getviews()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-views-management')
    },
    getviews() {
      this.$http
        .get(`admin/views/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.views = res.data.data.data

          this.pageInfo = res.data.data
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

      this.editedIndex = this.views.indexOf(item)
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.views.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/views/destroy/${item.id}`)

          .then(res => {
            this.views.splice(index, 1)
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
