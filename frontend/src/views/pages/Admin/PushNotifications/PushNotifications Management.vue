<template>
  <div>
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" outlined @click="createItem()">
      انشاء
      <v-icon class="mr-3">mdi-plus-circle</v-icon>
    </v-btn>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">العنوان</th>
              <th class="text-right text-uppercase">موضوع الرسالة</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in pushnotifications" :key="item.id">
              <td class="text-right">{{ item.title }}</td>
              <td class="text-right">{{ item.body }}</td>

              <td class="text-right">
                <div>
                  <v-btn
                    color="primary"
                    class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl"
                    outlined
                    @click="showUsers(item)"
                  >
                    عرض المستخدمين
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
        @input="getpushnotifications()"
        circle
      ></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
      pushnotifications: [],
      products: [],
      users: [],

      userspushnotifications: [],
      usersnotifications: [],
      product_id: null,
      //description data
      customToolbar: [
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
        ['link', 'code-block'],
      ],
      editedIndex: -1,
      editedItem: {
        product: {
          name: null,
        },
        user_id: null,
        user: {
          phone_no: null,
        },
        product_id: null,
        title: null,
        description: null,
      },
      defaultItem: {},
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
      status: 0,
      userspushnotifications: [],
      productspushnotifications: [],
      imgs: [],
      img: null,
      base_imgs: [],

      snackbar: false,
      text: null,
      color: null,

      total: 0,
      pageInfo: null,
      page: 1,
      statusInput: null,
    }
  },

  watch: {
    'editedItem.user.phone_no': {
      handler: function (val) {
        if (val) {
          this.$http.get(`admin/users/search/${val}`).then(res => {
            res.data.data.forEach(user => {
              // this.userspushnotifications = []
              this.userspushnotifications.push({
                text: user.name,
                value: user.id,
              })
            })
          })
        } else {
          this.userspushnotifications = []
        }
      },
    },
    dialog(val) {
      val || this.close()
    },
  },
  created() {
    this.getpushnotifications()
    this.getusers()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    createItem() {
      this.$router.push('/create-push-notification')
    },
    showUsers(item) {
      this.$router.push(`users-push-notification/${item.id}`)
    },
    getuser(item) {
      let usersIds = []
      this.userspushnotification.forEach(el => {
        usersIds.push(el.id)
      })

      if (!usersIds.includes(item.value)) {
        this.userspushnotification.push(item)
        this.users = item.name
      }
    },

    getuser(item) {
      this.user_id = item.value
      this.editedItem.user.phone_no = item.text
    },
    getusers() {
      this.$http
        .get('admin/users')
        .then(res => {
          res.data.data.forEach(user => {
            this.users.push({
              text: user.phone_no,
              value: user.id,
            })
          })
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    getpushnotifications() {
      this.editedItem.product.name = null
      this.editedItem.user.phone_no = null
      this.status = 'inactive'
      this.product_id = this.$route.params.product_id
      this.$http
        .get(`admin/pushnotifications/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.pushnotifications = res.data.data.data
          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    editItem(item) {
      this.editedIndex = this.pushnotifications.indexOf(item)
      Object.assign(this.editedItem, {
        ...item,
      })

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
