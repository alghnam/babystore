<template>
  <div>
    <v-snackbar v-model="snackbar" :color="color">
      {{ text }}
      <template v-slot:action="{ attrs }">
        <v-btn color="pink" text v-bind="attrs" @click="snackbar = false"> اغلاق </v-btn>
      </template>
    </v-snackbar>
        <v-btn color="primary" class="mt-6 ml-auto rounded-tr-xl rounded-bl-xl" @click="restoreAll()">
      استعادة الكل
     <v-icon class="mr-3">mdi-reply-all</v-icon>
    </v-btn>
 <v-col cols="12" class="pb-3">
        <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-right text-uppercase">الاسم</th>
            <th class="text-right text-uppercase">الوصف</th>
            <th class="text-right text-uppercase">الفئة الرئيسية</th>
            <th class="text-right text-uppercase">حالة الظهور</th>
            <th class="text-right text-uppercase">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in categories" :key="item.id">
          <td >{{ item.name }}</td>
            <td class="text-right">
              {{ item.description }}
            </td>
                        <td class="text-center" v-if="item.parent_id!==null">
             {{item.main_category ? item.main_category.name : "-"}}
            </td>
            <td class="text-right">
              {{ item.original_status }}
            </td>
            <td class="text-right">
              <v-btn color="primary" class="mt-6" @click="restoreItem(item)"> استعادة </v-btn>
               <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
            </td>
          </tr>
        </tbody>
      </template>

      <template v-slot:top>
        <v-toolbar flat color="white"> سلة محذوفات ادارة الفئات</v-toolbar>
      </template>
    </v-simple-table>
      </v-col>
      <template>
      <v-pagination v-model="page" :length="pageInfo && pageInfo.last_page" @input="getCategories()" circle></v-pagination>
    </template>
  </div>
</template>

<script>
export default {
 
  data() {
    return {
      categories: [],

      editedIndex: -1,
      editedItem: {
       
      },
      defaultItem: {        
       
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
    this.getCategories()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    getCategories() {
      this.$http
        .get(`admin/categories/sub/trash?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.categories = res.data.data.data
          this.pageInfo = res.data.data
            this.callMessage(res.data.message)

        })
       .catch(error => {
            this.callMessage(error.response.data.message)
        })
    },


    restoreItem(item) {
      //  this.editedIndex = this.categories.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$http
        .get(`admin/categories/sub/restore/${this.editedItem.id}`)
        .then(res => {
 
              const index = this.categories.indexOf(item)
              this.categories.splice(index, 1)
         this.callMessage(res.data.message)
            
          })
 .catch(error => {
            this.callMessage(error.response.data.message)
        })
    },
    restoreAll() {
      this.$http
        .get('admin/categories/sub/restore-all')
        .then(res => {

              this.categories = []
         this.callMessage(res.data.message)
         
        })
         .catch(error => {
            this.callMessage(error.response.data.message)
        })
    },

    deleteItem(item) {
      const index = this.categories.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/categories/sub/force-delete/${item.id}`)
          .then(res => {
            this.categories.splice(index, 1)
         this.callMessage(res.data.message)

          })
          .catch(error => {
            this.callMessage(error.response.data.message)
        })
    },

  
  },
}
</script>
