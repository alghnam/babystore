<template>
  <div>
    <v-btn color="primary" class="mt-6" @click="restoreAll()"> Restore All </v-btn>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">title</th>
              <th class="text-right text-uppercase">description</th>
              <th class="text-right text-uppercase">product name</th>
              <th class="text-right text-uppercase">Status</th>
              <th class="text-right text-uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in banners" :key="item.id">
              <td class="text-right">{{ item.title }}</td>
              <td class="text-right">{{ item.description }}</td>
              <td class="text-right">
                {{ item.product ? item.product.name : null }}
                {{ item.image ? item.image.url : null }}
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
    getbanners() {
      this.$http
        .get(`admin/banners/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.banners = res.data.data.data
          this.pageInfo = res.data.data
          this.callMessage(res.data.message)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
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
          this.callMessage(res.data.message)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },
    restoreAll() {
      this.$http
        .get('admin/banners/restore-all')
        .then(res => {
          this.banners = []
          this.callMessage(res.data.message)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },

    deleteItem(item) {
      const index = this.banners.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/banners/force-delete/${item.id}`)
          .then(res => {
            this.banners.splice(index, 1)
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
