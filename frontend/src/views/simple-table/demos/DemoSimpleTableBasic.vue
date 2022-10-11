<template>
  <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
    <template v-slot:default>
      <thead>
        <tr>
          <th class="text-right text-uppercase">Name</th>
          <th class="text-right text-uppercase">Personal Id</th>
          <th class="text-right text-uppercase">Passport Num.</th>
          <th class="text-right text-uppercase">Phone No.</th>
          <th class="text-right text-uppercase">Status</th>
          <th class="text-right text-uppercase">Confirmed</th>
          <th class="text-right text-uppercase">Agreement</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in users" :key="item.id">
          <td class="text-right">{{ item.name }}</td>
          <td class="text-right">
            {{ item.personal_id }}
          </td>
          <td class="text-right">
            {{ item.phone_no }}
          </td>
          <td class="text-right">
            {{ item.original_status }}
          </td>
          <td class="text-right">
            {{ item.confirmed }}
          </td>
          <td class="text-right">
            {{ item.agreement }}
          </td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      editedIndex: -1
    }
  },
  methods: {
    getUsers() {
      this.$http.get('admin/users').then(res => {
        users = res.data
      })
    },
    editUser(user, index) {
           this.editedIndex = index;

    },
  },
  created() {
    this.getUsers()
  },
}
</script>
