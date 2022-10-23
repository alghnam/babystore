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
              <th class="text-right text-uppercase">كلمة البحث</th>
              <th class="text-right text-uppercase">المستخدم</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in searches" :key="item.id">
              <td class="text-right">{{ item.word }}</td>

              <td class="text-right">
                {{ item.user ? item.user.first_name : null }}
              </td>

              <td>
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
        @input="getsearches()"
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
      searches: [],

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
      userName: null,
      user_id: null,
      editedIndex: -1,
      editedItem: {
        word: null,
        status: null,
        user: {
          id: null,
          first_name: null,
          last_name: null,
        },
      },
      defaultItem: {
        word: null,
        status: null,
        user: {
          id: null,
          first_name: null,
          last_name: null,
        },
      },
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
    this.getsearches()
    this.getusers()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-searches-management')
    },
    getsearches() {
      this.$http
        .get(`admin/searches/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.searches = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    getusers() {
      this.$http
        .get('admin/users')
        .then(res => {
          res.data.data.forEach(user => {
            this.users.push({
              text: user.first_name,
              value: user.id,
            })
          })
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },

    save() {
      //edit route
      this.$http
        .post(`admin/searches/update/${this.editedItem.id}`, {
          status: this.editedItem.status,
        })
        .then(res => {
          this.dialog = false
          Object.assign(this.searches[this.editedIndex], {
            original_status: res.data.data.original_status,
          })


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
    editItem(item) {
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })
      this.editedIndex = this.searches.indexOf(item)
    },

    deleteItem(item) {
      const index = this.searches.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/searches/destroy/${item.id}`)

          .then(res => {
            this.searches.splice(index, 1)
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
