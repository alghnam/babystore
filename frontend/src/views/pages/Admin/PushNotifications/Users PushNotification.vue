<template>
  <div>
    <v-col cols="12" class="pb-3">
      <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-right text-uppercase">First Name</th>
              <th class="text-right text-uppercase">last Name</th>

              <th class="text-right text-uppercase">Phone No.</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="item in users" :key="item.id">
              <td class="text-right">{{ item.first_name }}</td>

              <td class="text-right">
                {{ item.last_name }}
              </td>

              <td class="text-right">
                {{ item.phone_no }}
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
        @input="getUsersPushnotification()"
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
      users: [],
      pushnotification_id: null,
      orderNum: null,
      orders: [],
      uses: [
        {
          text: 'Used',
          value: 1,
        },
        {
          text: 'Not Use',
          value: 0,
        },
      ],
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

      order_id: null,
      editedIndex: -1,
      editedItem: {
        id: null,
        name: null,
        value: null,
        end_date: null,
        order: {
          id: null,
          order_num: null,
        },
        status: null,
      },
      defaultItem: {
        id: null,
        name: null,
        value: null,
        order: {
          id: null,
          order_num: null,
        },
        status: null,
      },
      snackbar: false,
      text: null,
      color: null,

      total: 0,
      pageInfo: null,
      page: 1,
      menu: false,
    }
  },
  computed: {},
  watch: {
    dialog(val) {
      val || this.close()
    },
    'editedItem.order.order_num': {
      handler: function (val) {
        this.editedItem.order.id = val
      },
    },
  },
  created() {
    this.getUsersPushnotification()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    getUsersPushnotification() {
      this.pushnotification_id = this.$route.params.pushnotification_id

      this.$http
        .get(
          `admin/pushnotifications/get-all-users-pushnotification-paginates/${this.pushnotification_id}?page=${this.page}&total=${this.total}`,
        )
        .then(res => {
          this.users = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    editItem(item) {
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })

      this.editedIndex = this.users.indexOf(item)
    },
    createItem() {
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
