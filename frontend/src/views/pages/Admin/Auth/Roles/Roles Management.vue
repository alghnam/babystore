<template>
  <div>
    <v-btn color="primary" class="mt-6" @click="showTrash()"> Trash </v-btn>

    <v-btn color="primary" class="mt-6" outlined @click="createItem()"> Create </v-btn>
    <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
      <template v-slot:default>
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
              <div v-if="item.id !== 1">
                <v-btn color="primary" class="mt-6" @click="editItem(item)">
                  <v-icon color="black">mdi-pencil</v-icon> Edit
                </v-btn>
                <v-btn color="default" class="mt-6" @click="deleteItem(item)"> Delete </v-btn>
              </div>
            </td>
          </tr>
        </tbody>
      </template>

      <template v-slot:top>
        <v-toolbar flat color="white">
          Roles Management
          <v-dialog v-model="dialog">
            <template v-slot:activator="{ on, attrs }">
              <v-btn color="primary" dark class="rounded-lg mr-auto" v-bind="attrs" v-on="on" fab tile x-small
                ><v-icon>mdi-plus-circle</v-icon></v-btn
              >
            </template>

            <template v-slot:expanded-item="{ headers, item }">
              <td :colspan="headers.length">More info about {{ item.name }}</td>
            </template>
            <div class="container">
              <div class="row">
                <v-card class="col-sm-7 mx-auto">
                  <v-card-title>
                    <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                      <v-icon dark large>mdi-account-circle</v-icon> roles Management
                    </v-alert>
                  </v-card-title>
                  <v-card-text>
                    <div class="row">
                      <v-text-field
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="name"
                        v-model="editedItem.name"
                      ></v-text-field>
                      <v-text-field
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="display_name"
                        v-model="editedItem.display_name"
                      ></v-text-field>

                      <vue-editor
                        class="col-sm-12 mx-auto"
                        v-model="editedItem.description"
                        :editorToolbar="customToolbar"
                      ></vue-editor>

                      <v-select
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="permissions"
                        :items="permissions"
                        v-model="editedItem.permissions"
                        multiple
                      ></v-select>

                      <v-select
                        class="col-sm-5 mx-auto"
                        outlined
                        dense
                        label="status"
                        :items="statuses"
                        v-model="editedItem.status"
                      ></v-select>
                      <div class="col-sm-5 mx-auto row">
                        <v-btn
                          color="primary lighten-1 rounded-tr-xl rounded-bl-xl"
                          class="col-sm-5 mx-auto"
                          @click="save()"
                          dark
                          >حفظ <i class="fas fa-file mr-3"></i
                        ></v-btn>
                        <v-btn
                          color="white"
                          light
                          class="col-sm-5 mx-auto black--text rounded-tr-xl rounded-bl-xl"
                          @click="close()"
                          dark
                          >رجوع
                          <v-icon class="mr-3">mdi-reply-all</v-icon>
                        </v-btn>
                      </div>
                    </div>
                  </v-card-text>
                </v-card>
              </div>
            </div>
          </v-dialog>
        </v-toolbar>
      </template>
    </v-simple-table>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getRoles()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
import { VueEditor } from 'vue2-editor'

export default {
  data() {
    return {
      dialog: false,
      userToken: null,
      roles: [],
      permissions: [],
      send_permissions: [],
      editedIndex: -1,
      editedItem: {
        name: null,
        display_name: null,
        description: null,
        status: null,
        permissions: [],
        status: null,
      },
      //description data
      customToolbar: [
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
        ['link', 'code-block'],
      ],
      defaultItem: {
         name: null,
        display_name: null,
        description: null,
        status: null,
        permissions: [],
        status: null,
      },
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
    dialog(val) {
      val || this.close()
    },

  },
  created() {
    this.getRoles()
    this.getPermissions()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    showTrash() {
      this.$router.push('/trash-roles-managment')
    },

    getRoles() {
      this.$http
        .get(`admin/roles/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.roles = res.data.data.data
          this.pageInfo = res.data.data
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
    save() {
      let dum_permissions = []
      if (this.editedItem.permissions) {
        this.editedItem.permissions.forEach(permission => {
          if (permission.value) {
            dum_permissions.push(permission.value)
          } else {
            dum_permissions.push(permission)
          }
        })
      }

      this.editedItem.permissions = dum_permissions

      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/roles/update/${this.editedItem.id}`, {
            name: this.editedItem.name,
            display_name: this.editedItem.display_name,
            description: this.editedItem.description,
            status: this.editedItem.status,

            permissions: this.editedItem.permissions,
          })

          .then(res => {
            this.dialog = false
            Object.assign(this.roles[this.editedIndex], res.data.data)

            this.callMessage(res.data.message)
          })

          .catch(error => {
            this.dialog = false

            this.callMessage(error.response.data.message)
          })
      } else {
        //add (save) route
        this.$http
          .post(`admin/roles/store`, {
            name: this.editedItem.name,
            display_name: this.editedItem.display_name,
            description: this.editedItem.description,
            permissions: this.editedItem.permissions,
            status: this.editedItem.status,
          })
          .then(res => {
            this.dialog = false
            this.roles.push(res.data.data)

            Object.assign(this.roles[this.editedIndex], res.data.data)

            this.callMessage(res.data.message)
          })
          .catch(error => {
            this.dialog = false

            this.callMessage(error.response.data.message)
          })
      }
    },
    editItem(item) {
      this.editedIndex = this.roles.indexOf(item)
      this.editedItem = Object.assign({}, item)

       //get permissions for this role id
      let permissionso = []
      this.$http
        .get(`admin/permissions/permissions-role-by-name/${item.id}`)
        .then(res => {
          res.data.data.forEach(permission => {
            permissionso.push({
              text: permission.name,
              value: permission.id,
            })
          })
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
      this.editedItem.permissions = permissionso
      this.dialog = true
    },
    createItem() {
      this.status = 'active'
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.roles.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/roles/destroy/${item.id}`)

          .then(res => {
            this.roles.splice(index, 1)
            this.callMessage(res.data.message)
          })
          .catch(error => {
            this.callMessage(error.response.data.message)
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
