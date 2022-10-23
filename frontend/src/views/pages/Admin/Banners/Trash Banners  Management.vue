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
              <th class="text-right text-uppercase">العنوان</th>
              <th class="text-right text-uppercase">الوصف</th>
              <th class="text-right text-uppercase">اسم المنتج</th>
              <th class="text-right text-uppercase">الحالة</th>
              <th class="text-right text-uppercase">الأحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in banners" :key="item.id">
              <td class="text-right">{{ item.title }}</td>
              <td class="text-right">{{ item.description }}</td>
              <td class="text-right">
                {{ item.product.name.text ? item.product.name.text : item.product.name }}
                <div v-if="item.image">
                  <img style="width: 100px;"
                    :src="$store.state.baseURL + '/storage/' + trimAttribute(item.image.url, '(S)')"
                    alt="product image"
                  />
                </div>
              </td>
              <td class="text-right">
                {{ item.original_status }}
              </td>
              <td class="text-right">
                <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="restoreItem(item)">
                  <v-icon class="mr-3">mdi-reply-all</v-icon>
                </v-btn>
                <div>
                  <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                    <v-icon color="black" class="">mdi-delete</v-icon>
                  </v-btn>
                </div>
              </td>
            </tr>
          </tbody>
        </template>
        <template v-slot:top>
          <v-toolbar flat color="white">سلة المحذوفات </v-toolbar>
        </template>
      </v-simple-table>
    </v-col>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getbanners()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      banners: [],
      editedIndex: -1,
      editedItem: {
        product: {
          name: null,
        },

        product_id: null,
        title: null,
        description: null,
        status: null,
        photo_url: null,
      },
      defaultItem: {
        product: {
          name: null,
        },

        product_id: null,
        title: null,
        description: null,
        status: null,
        photo_url: null,
      },
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
    this.getbanners()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
        trimAttribute(value, size) {
      if (value !== null) {
        let new_url = value.slice(0, 13) + '/thumbnail/' + value.slice(14)
        let index = new_url.length - 4
        let url = new_url.slice(0, index) + size + new_url.slice(index)
        return url.substr(0, url.length)
      }
    },
    getbanners() {
      this.$http
        .get(`admin/banners/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.banners = res.data.data.data
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
      this.editedIndex = this.banners.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/banners/restore/${this.editedItem.id}`)
        .then(res => {
          const index = this.banners.indexOf(item)
          this.banners.splice(index, 1)
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
        .get('admin/banners/restore-all')
        .then(res => {
          this.banners = []
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    deleteItem(item) {
      const index = this.banners.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/banners/force-delete/${item.id}`)
          .then(res => {
            this.banners.splice(index, 1)
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
