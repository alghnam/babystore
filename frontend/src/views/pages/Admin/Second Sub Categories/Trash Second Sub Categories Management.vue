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
              <th class="text-right text-uppercase">الاسم</th>
              <th class="text-right text-uppercase">الفئة الرئيسية</th>
              <th class="text-right text-uppercase">الفئة الفرعية</th>
              <th class="text-right text-uppercase">حالة الظهور</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in categories" :key="item.id">
              <td class="text-right">{{ item.name }}</td>

              <td
                class="text-center"
                v-if="item.category_id !== null || item.category !== null || item.category !== undefined"
              >
                {{ item.category ? (item.category.name.text ? item.category.name.text : item.category.name) : null }}
              </td>

              <td class="text-center">
                {{ item.original_status }}
              </td>
              <td class="text-right">
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
          <v-toolbar flat color="white"> سلة محذوفات ادارة الفئات الفرعية الثانية</v-toolbar>
        </template>
      </v-simple-table>
    </v-col>
    <template>
      <v-pagination
        v-model="page"
        :length="pageInfo && pageInfo.last_page"
        @input="getCategories()"
        circle
      ></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      categories: [],

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
    this.getCategories()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    getCategories() {
      this.$http
        .get(`admin/categories/sub/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.categories = res.data.data.data
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
      //  this.editedIndex = this.categories.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/categories/sub/restore/${this.editedItem.id}`)
        .then(res => {
          const index = this.categories.indexOf(item)
          this.categories.splice(index, 1)
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
        .get('admin/categories/sub/restore-all')
        .then(res => {
          this.categories = []
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    deleteItem(item) {
      const index = this.categories.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/categories/sub/force-delete/${item.id}`)
          .then(res => {
            this.categories.splice(index, 1)
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
