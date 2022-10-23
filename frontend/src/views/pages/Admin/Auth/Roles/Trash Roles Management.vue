<template>
  <div>
    <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="restoreAll()">
      استعادة الكل
      <v-icon class="mr-3">mdi-reply-all</v-icon>
    </v-btn>
    <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
      <template v-slot:default>
        <v-btn icon small @click="createItem()">
          <i class="fas fa-edit"></i>
        </v-btn>
        <thead>
          <tr>
            <th class="text-right text-uppercase">الاسم</th>
            <th class="text-right text-uppercase">الاسم المعروض</th>
            <th class="text-right text-uppercase">الوصف</th>
            <th class="text-right text-uppercase">الحالة</th>
            <th class="text-right text-uppercase">الأحداث</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in roles" :key="item.id">
            <td class="text-right">{{ item.name }}</td>

            <td class="text-right">
              {{ item.display_name }}
            </td>
            <td class="text-right">
              {{ item.description }}
            </td>

            <td class="text-right">
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
        <v-toolbar flat color="white">سلة المحذوفات</v-toolbar>
      </template>
    </v-simple-table>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getRoles()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      roles: [],
      permissions: [],
      send_permissions: [],
      editedIndex: -1,
      editedItem: {
        name: null,
        display_name: null,
        description: null,
        status: null,
      },
      defaultItem: {
        name: null,
        display_name: null,
        description: null,
        status: null,
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
    this.getRoles()
    this.getPermissions()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    getRoles() {
      this.$http
        .get(`admin/roles/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.roles = res.data.data.data
          this.pageInfo = res.data.data
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    getPermissions() {
      this.$http
        .get('admin/permissions')
        .then(res => {
          res.data.data.forEach(da => {
            this.permissions.push({
              value: da.id,
              text: da.name,
            })
          })
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    restoreItem(item) {
      this.editedIndex = this.roles.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/roles/restore/${this.editedItem.id}`)
        .then(res => {
          const index = this.roles.indexOf(item)
          this.roles.splice(index, 1)
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
        .get('admin/roles/restore-all')
        .then(res => {
          this.roles = []
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    deleteItem(item) {
      const index = this.roles.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/roles/force-delete/${item.id}`)
          .then(res => {
            this.roles.splice(index, 1)
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
