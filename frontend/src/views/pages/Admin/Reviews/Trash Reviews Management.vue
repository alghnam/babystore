<template>
  <div>
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="restoreAll()">
      استعادة الكل
      <v-icon class="mr-3">mdi-reply-all</v-icon>
    </v-btn>

    <v-simple-table>
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-uppercase">الاسم الاول</th>
            <th class="text-uppercase">الاسم الاخير</th>
            <th class="text-center text-uppercase">اسم المنتج</th>
            <th class="text-center text-uppercase">الوصف</th>
            <th class="text-center text-uppercase">التقييم</th>
            <th class="text-center text-uppercase">الاحداث</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in reviews" :key="item.id">
            <td>{{ item.first_name }}</td>
            <td>{{ item.last_name }}</td>

            <td class="text-center">
              {{ item.product ? item.product.name : null }}
            </td>

            <td class="text-center">
              {{ item.description }}
            </td>
            <td class="text-center">
              {{ item.rating }}
              <v-rating
                v-model="item.rating"
                color="yellow darken-3"
                background-color="grey darken-1"
                empty-icon="$ratingFull"
                half-increments
                large
              ></v-rating>
            </td>

            <td>
              <div>
                <v-btn color="primary" class="mt-6" @click="restoreItem(item)"> استعادة </v-btn>
              </div>
            </td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getreviews()" circle></v-pagination>
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
      reviews: [],
      editedIndex: -1,
      editedItem: {
        name: null,
        display_name: null,
        description: null,
        status: null,
      },
      defaultItem: {
        name: null,
        display_name: null,
        description: null,
        status: null,
      },
      snackbar: false,
      text: null,
      color: null,
      ErrorBool: false,
      SuccessBool: false,

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
    this.getreviews()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    getreviews() {
      this.$http
        .get(`admin/reviews/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.reviews = res.data.data.data
          this.pageInfo = res.data.data
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    restoreItem(item) {
      this.editedIndex = this.reviews.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/reviews/restore/${this.editedItem.id}`)
        .then(res => {
          const index = this.reviews.indexOf(item)
          this.reviews.splice(index, 1)
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    restoreAll() {
      this.$http
        .get('admin/reviews/restore-all')
        .then(res => {
          this.reviews = []
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.reviews.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/reviews/force-delete/${item.id}`)
          .then(res => {
            this.reviews.splice(index, 1)
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
