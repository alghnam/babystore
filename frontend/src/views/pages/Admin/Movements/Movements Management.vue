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
              <th class="text-right text-uppercase">الاسم</th>
              <th class="text-right text-uppercase">القيمة</th>
              <th class="text-right text-uppercase">رقم المحفظة</th>
              <th class="text-right text-uppercase">المتبقي بالمحفظة</th>
              <th class="text-right text-uppercase">النوع</th>
              <!-- <th class="text-right text-uppercase">الاحداث</th> -->
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in movements" :key="item.id">
              <td class="text-right">{{ item.name }}</td>
              <td class="text-right">{{ item.value }}</td>

              <td class="text-right">
                {{ item.wallet ? item.wallet.id : null }}
              </td>
              <td class="text-right">{{ item.remaining_wallet_points }}</td>

              <td class="text-right">
                {{ item.original_type }}
              </td>

              <!-- <td class="text-right">
                <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
              </td> -->
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-col>
    <template>
      <v-pagination
        v-model="page"
        :length="pageInfo && pageInfo.last_page"
        @input="getmovements()"
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
      wallets: [],
      movements: [],

      types: [
        {
          text: 'Acquired',
          value: '0',
        },
        {
          text: 'Replaced',
          value: '1',
        },
        {
          text: 'Deposition',
          value: '2',
        },
        {
          text: 'Buying',
          value: '3',
        },
      ],
      movementName: null,
      movement_id: null,
      editedIndex: -1,
      editedItem: {
        name: null,
        value: null,
        movement: {
          id: null,
        },
        status: null,
      },
      defaultItem: {},
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
    this.getmovements()
    this.getwallets()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-movements-management')
    },
    getmovements() {
      this.$http
        .get(`admin/movements/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.movements = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    getwallets() {
      this.$http
        .get('admin/wallets')
        .then(res => {
          res.data.data.forEach(wallet => {
            this.wallets.push({
              text: wallet.id,
              value: wallet.id,
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

    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.movements.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/movements/destroy/${item.id}`)

          .then(res => {
            this.movements.splice(index, 1)
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
