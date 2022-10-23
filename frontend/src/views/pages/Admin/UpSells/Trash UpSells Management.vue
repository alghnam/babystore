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
              <th class="text-right text-uppercase">name</th>
              <th class="text-right text-uppercase">description</th>
              <th class="text-right text-uppercase">footer</th>
              <th class="text-right text-uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in upsells" :key="item.id">
              <td class="text-right">{{ item.name }}</td>
              <td v-html="item.description"></td>
              <td class="text-right">{{ item.footer }}</td>

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
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getupsells()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dialog: null,
      upsells: [],

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
    this.getupsells()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    getupsells() {
      this.$http
        .get(`admin/upsells/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.upsells = res.data.data.data
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
      this.editedIndex = this.upsells.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/upsells/restore/${this.editedItem.id}`)
        .then(res => {
          const index = this.upsells.indexOf(item)
          this.upsells.splice(index, 1)
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
        .get('admin/upsells/restore-all')
        .then(res => {
          this.upsells = []
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    deleteItem(item) {
      const index = this.upsells.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/upsells/force-delete/${item.id}`)
          .then(res => {
            this.upsells.splice(index, 1)
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
