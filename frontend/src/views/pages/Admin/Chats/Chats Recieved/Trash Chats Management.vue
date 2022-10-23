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
              <th class="text-right text-uppercase">اسم المرسل</th>
              <th class="text-right text-uppercase">الرسالة</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in chats" :key="item.id">
              <td class="text-right">{{ item.client ? item.client.first_name : null }}</td>

              <td class="text-center" v-html="item.message"></td>

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
          <v-toolbar flat color="white"> سلة محذوفات ادارة المحادثات</v-toolbar>
        </template>
      </v-simple-table>
    </v-col>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getchats()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      chats: [],

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
    this.getchats()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    getchats() {
      this.$http
        .get(`admin/chats/trash-recieved?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.chats = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },

    restoreItem(item) {
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/chats/restore/${this.editedItem.id}`)
        .then(res => {
          const index = this.chats.indexOf(item)
          this.chats.splice(index, 1)
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
    restoreAll() {
      this.$http
        .get('admin/chats/restore-all-recieved')
        .then(res => {
          if (res.data.message != null) {
            this.chats = []
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          }
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },

    deleteItem(item) {
      const index = this.chats.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/chats/force-delete/${item.id}`)
          .then(res => {
            this.chats.splice(index, 1)
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
