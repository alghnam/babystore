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
            <th class="text-right text-uppercase">اسم المستخدم</th>
            <th class="text-right text-uppercase">نوع التقييم </th>
            <th class="text-right text-uppercase">الاسم</th>
            <th class="text-right text-uppercase">المحتوى</th>
            <th class="text-right text-uppercase">الايميل</th>
            <th class="text-right text-uppercase">الاحداث</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in reviews" :key="item.id">
            <td class="text-right">{{ item.user ? item.user.first_name : "-" }}</td>
            <td class="text-right">{{ item.system_review_type ? item.system_review_type.name : "-" }}</td>

            <td class="text-right">
              {{ item.name ? item.name : null }}
            </td>

            <td class="text-right">
              {{ item.body ? item.body : null }}
            </td>

            <td class="text-right">
              {{ item.email ? item.email : null }}
            </td>

            <td class="text-right">
              <div>
                <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="restoreItem(item)">
                  <v-icon class="mr-3">mdi-reply-all</v-icon>
                </v-btn>
                <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
              </div>
            </td>
          </tr>
        </tbody>
      </template>

  
    </v-simple-table>
    </v-col>
    <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getreviews()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {

  data() {
    return {
      dialog: null,
      reviews: [],
      editedIndex: -1,
      editedItem: {
       
      },
      defaultItem: {
       
      },
      snackbar: false,
      text: null,
      color: null,
      ErrorBool: false,
      SuccessBool: false,
    

      total: 0,
      pageInfo: null,
      page: 1,
    }
  },

  watch: {
    dialog(val) {
      val || this.close()
    },
   
  },
  created() {
    this.getreviews()
    this.getPermissions()
  },
  methods: {

    callMessage(message) {
      this.snackbar=true
      this.text=message
     
    },

    getreviews() {
      this.$http
        .get(`admin/system-reviews/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.reviews = res.data.data.data
          this.pageInfo = res.data.data
            this.$store.state.snackbar=true
          this.$store.state.text = res.data.message
        })
      .catch(error => {
            this.$store.state.snackbar=true
          this.$store.state.text = error.response.data.message
        })
    },

    restoreItem(item) {
      this.editedIndex = this.reviews.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/system-reviews/restore/${this.editedItem.id}`)
        .then(res => {
              const index = this.reviews.indexOf(item)
              this.reviews.splice(index, 1)
          this.$store.state.snackbar=true
          this.$store.state.text = res.data.message
        })
      .catch(error => {
            this.$store.state.snackbar=true
          this.$store.state.text = error.response.data.message
        })
    },
    restoreAll() {
      this.$http
        .get('admin/system-reviews/restore-all')
        .then(res => {
              this.reviews = []
             this.$store.state.snackbar=true
          this.$store.state.text = res.data.message
        })
      .catch(error => {
            this.$store.state.snackbar=true
          this.$store.state.text = error.response.data.message
        })
    },


    deleteItem(item) {
      const index = this.reviews.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/system-reviews/force-delete/${item.id}`)
          .then(res => {
                this.reviews.splice(index, 1)
 this.$store.state.snackbar=true
          this.$store.state.text = res.data.message              
          })
      .catch(error => {
            this.$store.state.snackbar=true
          this.$store.state.text = error.response.data.message
        })
    },

  
  },
}
</script>
