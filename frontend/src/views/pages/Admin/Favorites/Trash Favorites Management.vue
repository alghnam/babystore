<template>
  <div>
    <v-btn color="primary" class="mt-6" @click="restoreAll()"> استعادة الكل </v-btn>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <v-btn icon small @click="createItem()">
            <i class="fas fa-edit"></i>
          </v-btn>
          <thead>
            <tr>
              <th class="text-right text-uppercase">user name</th>
              <th class="text-right text-uppercase">product name</th>
              <th class="text-right text-uppercase">Status</th>
              <th class="text-right text-uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in favorites" :key="item.id">
              <td class="text-right">{{ item.user ? item.user.first_name : null }}</td>
              <td class="text-right">
                {{ item.product ? item.product.name : null }}
              </td>
              <td class="text-right">
                {{ item.status }}
              </td>
              <td class="text-right">
                <div>
                  <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="restoreItem(item)">
                    <v-icon class="mr-3">mdi-reply-all</v-icon>
                  </v-btn>
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
      <v-pagination
        v-model="page"
        :length="pageInfo && pageInfo.last_page"
        @input="getfavorites()"
        circle
      ></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      favorites: [],

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
    this.getfavorites()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    getfavorites() {
      this.$http
        .get(`admin/favorites/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.favorites = res.data.data.data
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
      this.editedIndex = this.favorites.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/favorites/restore/${this.editedItem.id}`)
        .then(res => {
          const index = this.favorites.indexOf(item)
          this.favorites.splice(index, 1)
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
        .get('admin/favorites/restore-all')
        .then(res => {
          this.favorites = []
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    deleteItem(item) {
      const index = this.favorites.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/favorites/force-delete/${item.id}`)
          .then(res => {
            this.favorites.splice(index, 1)
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
