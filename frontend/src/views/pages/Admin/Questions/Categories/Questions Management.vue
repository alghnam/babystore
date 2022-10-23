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
              <th class="text-right text-uppercase">الصورة</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in questions" :key="item.id">
              <td class="text-right">
                {{ item.name }}
              </td>
              <td class="text-right">
                <div v-if="item.image">
                  <img
                    :src="$store.state.baseURL + '/storage/' + trimAttribute(item.image.url, '(S)')"
                    alt="product image"
                  />
                </div>
              </td>

              <td>
                <v-btn color="primary" class="mt-1 rounded-lg" fab x-small tile @click="editItem(item)">
                  <v-icon color="black" class="white--text">mdi-pencil</v-icon>
                </v-btn>
                <v-btn color="default" class="mt-1 mr-3 rounded-lg" fab x-small tile @click="deleteItem(item)">
                  <v-icon color="black" class="">mdi-delete</v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </template>

        <template v-slot:top>
          <v-toolbar flat color="white">
            ادارة اقسام الاسئلة
            <v-dialog v-model="dialog">
              <template v-slot:activator="{ on, attrs }">
                <v-btn color="primary" dark class="rounded-lg mr-auto" v-bind="attrs" v-on="on" fab tile x-small
                  ><v-icon>mdi-plus-circle</v-icon></v-btn
                >
              </template>

              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">More info about {{ item.question }}</td>
              </template>
              <div class="container">
                <div class="row">
                  <v-card class="col-sm-7 mx-auto">
                    <v-card-title>
                      <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" color="primary">
                        <v-icon large>mdi-account-circle</v-icon> ادارة الاسئلة
                      </v-alert>
                    </v-card-title>
                    <v-card-text>
                      <div class="row">
                        <v-col cols="12" sm="12" md="6" lg="6" xl="6" class="mx-auto">
                        <v-img
                          :src="
                            editedItem.image
                              ? $store.state.baseURL + '/storage/' + trimAttribute(editedItem.image.url, '(S)')
                              : ''
                          "
                          v-if="!editedItem.photo_url"
                        ></v-img>
                        <img
                          :src="editedItem.photo_url"
                          v-if="editedItem.photo_url"
                          style="height: 118px; width: 84px"
                        />

                        <!-- image choose section -->
                        <v-file-input
                          truncate-length="15"
                          outlined
                          dense
                          label="صورة المنتج"
                          class="col-sm-5 mx-auto"
                          v-model="photo"
                        ></v-file-input>
                        </v-col>
                        <v-col cols="12" sm="12" md="6" lg="6" xl="6" class="mx-auto">

                        <v-text-field outlined dense label="الاسم" v-model="editedItem.name"></v-text-field>
                        </v-col>
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
    </v-col>
    <template>
      <v-pagination
        v-model="page"
        :length="pageInfo && pageInfo.last_page"
        @input="getquestions()"
        circle
      ></v-pagination>
    </template>
  </div>
</template>

<script>
import axios from 'axios'
import { VueEditor } from 'vue2-editor'

export default {
  components: {
    VueEditor,
  },
  data() {
    return {
      photo: null,
      dialog: false,
      questions: [],
      orderNum: null,
      orders: [],

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
      //answer data
      customToolbar: [
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
        ['link', 'code-block'],
      ],
      editedIndex: -1,
      editedItem: {
        question: null,
        answer: null,
        status: null,
        photo_url: null,
        image: null,
        // photo:null
      },
      defaultItem: {
        question: null,
        answer: null,
        photo_url: null,
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
  computed: {},
  watch: {
    photo(val) {
      if (val) {
        let reader = new FileReader()
        reader.addEventListener('load', e => {
          this.editedItem.photo_url = e.target.result
        })
        reader.readAsDataURL(val)
      } else {
        this.editedItem.photo_url = null
      }
    },
    dialog(val) {
      val || this.close()
    },
  },
  created() {
    this.getquestions()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    trimAttribute(value, size) {
      if (value !== undefined || value !==null) {
        let new_url = value.slice(0, 27) + '/thumbnail/' + value.slice(28)
        let index = new_url.length - 4
        let url = new_url.slice(0, index) + size + new_url.slice(index)

        return url.substr(0, url.length)
      }
    },
    showTrash() {
      this.$router.push('/trash-question-categories-management')
    },
    getquestions() {
      this.$http
        .get(`admin/question-categories/get-all-paginates?page=${this.page}&total=${this.total}`)
        .then(res => {
          this.questions = res.data.data.data

          this.pageInfo = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },

    save() {
      let formData = []

      formData = new FormData()

      formData.append('image', this.photo)
      formData.append('name', this.editedItem.name)

      formData.append('status', 1)
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/question-categories/update/${this.editedItem.id}`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              'X-Requested-With': 'XMLHttpRequest',
            },
          })
          .then(res => {
            this.close()

            // Object.assign(this.questions[this.editedIndex], {
            //   name: this.editedItem.name,
            // photo: this.photo,
            // original_status: res.data.data.original_status,
            // })
            Object.assign(this.questions[this.editedIndex], res.data.data)

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            }
          })
      } else {
        this.$http
          .post('admin/question-categories/store', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              'X-Requested-With': 'XMLHttpRequest',
            },
          })

          .then(res => {
            this.close()
            this.questions.push(res.data.data)

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            }
          })
      }
    },
    editItem(item) {
      this.dialog = true
      Object.assign(this.editedItem, {
        ...item,
      })

      this.editedIndex = this.questions.indexOf(item)
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.questions.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/question-categories/destroy/${item.id}`)

          .then(res => {
            this.questions.splice(index, 1)
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
