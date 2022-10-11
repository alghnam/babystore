<template>
  <div>
    <v-btn color="primary" class="mt-6" @click="restoreAll()"> Restore All </v-btn>
 
    <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
      <template v-slot:default>
        <thead>
          <tr>
             <th class="text-right text-uppercase">First Name</th>
            <th class="text-right text-uppercase">last Name</th>

            <th class="text-right text-uppercase">Phone No.</th>

            <th class="text-right text-uppercase">Status</th>

            <th class="text-right text-uppercase">Actions</th>
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

            <td class="text-right">
              {{ item.original_status }}
            </td>
            <td class="text-right">
              <v-btn color="primary" class="mt-6" @click="restoreItem(item)"> Restore </v-btn>
              <v-btn color="default" class="mt-6" @click="deleteItem(item)"> Delete </v-btn>
            </td>
          </tr>
        </tbody>
      </template>

      <template v-slot:top>
        <v-toolbar flat color="white"> Trash Users Management </v-toolbar>
      </template>
    </v-simple-table>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getUsers()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {

  data() {
    return {
      users: [],
      permissions: [],
      editedIndex: -1,
      editedItem: {
        first_name: null,
        last_name: null,
        phone_no: null,
        status: null,
        photo: null,
        roles: [],
      },
      defaultItem: {
       first_name: null,
        last_name: null,
        phone_no: null,
        status: null,
        photo: null,
        roles: [],
      },
      snackbar: false,
      text: null,
      color: null,
    

      total: 0,
      pageInfo: null,
      page: 1,
    }
  },

  watch: {



  },
  created() {
    this.getUsers()
  },
  methods: {
        callMessage(message) {
      this.snackbar=true
      this.text=message
     
    },
    getUsers() {
      this.$http
        .get(`admin/users/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.users = res.data.data.data
          this.pageInfo = res.data.data
            this.callMessage(res.data.message)

        })
          .catch(error => {
            this.callMessage(error.response.data.message)
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
            this.callMessage(error.response.data.message)
        })
    },

    restoreItem(item) {
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/users/restore/${this.editedItem.id}`)
        .then(res => {

              const index = this.users.indexOf(item)
              this.users.splice(index, 1)
 this.callMessage(res.data.message)    
        })
          .catch(error => {
            this.callMessage(error.response.data.message)
        })
    },
    restoreAll() {
      this.$http
        .get('admin/users/restore-all')
        .then(res => {
          
              this.users = []
 this.callMessage(res.data.message)          
        })
          .catch(error => {
            this.callMessage(error.response.data.message)
        })
    },
    deleteItem(item) {
      const index = this.cities.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/cities/force-delete/${item.id}`)
          .then(res => {
            this.cities.splice(index, 1)
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
