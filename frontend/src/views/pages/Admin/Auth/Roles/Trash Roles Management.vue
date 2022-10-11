<template>
  <div>
 
    <v-btn color="primary" class="mt-6" @click="restoreAll()"> Restore All </v-btn>

    <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
      <template v-slot:default>
        <v-btn icon small @click="createItem()">
          <i class="fas fa-edit"></i>
        </v-btn>
        <thead>
          <tr>
            <th class="text-right text-uppercase">Name</th>
            <th class="text-right text-uppercase">Display Name</th>
            <th class="text-right text-uppercase">Description</th>
            <th class="text-right text-uppercase">Status</th>
            <th class="text-right text-uppercase">Actions</th>
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
              <v-btn color="primary" class="mt-6" @click="restoreItem(item)"> Restore </v-btn>
              <v-btn color="default" class="mt-6" @click="deleteItem(item)"> Delete </v-btn>
            </td>
          </tr>
        </tbody>
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

  watch: {

  },
  created() {
    this.getRoles()
    this.getPermissions()
  },
  methods: {
    
    callMessage(message) {
      this.snackbar=true
      this.text=message
     
    },

    getRoles() {
      this.$http
        .get(`admin/roles/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.roles = res.data.data.data
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
      this.editedIndex = this.roles.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/roles/restore/${this.editedItem.id}`)
        .then(res => {

              const index = this.roles.indexOf(item)
              this.roles.splice(index, 1)
             this.callMessage(res.data.message)
          
        })
      .catch(error => {
            this.callMessage(error.response.data.message)
        })
    },
    restoreAll() {
      this.$http
        .get('admin/roles/restore-all')
        .then(res => {
              this.roles = []
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
