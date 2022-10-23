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
              <th class="text-right text-uppercase">اسم الفئة</th>
              <th class="text-right text-uppercase">السؤال</th>
              <th class="text-right text-uppercase">الجواب</th>
              <th class="text-right text-uppercase">الاحداث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in questions" :key="item.id">
              <td class="text-right">
                {{
                  item.question_category
                    ? item.question_category.name.text
                      ? item.question_category.name.text
                      : item.question_category.name
                    : null
                }}
              </td>
              <td class="text-right">{{ item.question }}</td>
              <td class="text-center" v-html="item.answer"></td>

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
            ادارة الاسئلة
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
                        <v-select
                          v-if="editedItem.question_category"
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="اختر الفئة"
                          :items="questionCategorieso"
                          v-model="editedItem.question_category.name"
                        ></v-select>
                        <v-select
                          v-else
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="اختر الفئة"
                          :items="questionCategorieso"
                          v-model="editedItem.question_category"
                        ></v-select>
                        <v-text-field
                          class="col-sm-5 mx-auto"
                          outlined
                          dense
                          label="السؤال"
                          v-model="editedItem.question"
                        ></v-text-field>
                        <vue-editor
                          class="col-sm-12 mx-auto"
                          v-model="editedItem.answer"
                          :editorToolbar="customToolbar"
                        ></vue-editor>

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
      dialog: false,
      questions: [],
      orderNum: null,
      orders: [],
      questionCategorieso: [],

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
        question_category: {
          id: 0,
          name: null,
        },
        status: null,
      },
      defaultItem: {
        question: null,
        answer: null,
        question_category: {
          id: 0,
          name: null,
        },
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
    dialog(val) {
      val || this.close()
    },
    'editedItem.question_category.name': {
      handler: function (val) {
        this.editedItem.question_category.id = val
      },
    },
  },
  created() {
    this.getquestions()
    this.getquestionCategories()
  },
  methods: {
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },
    showTrash() {
      this.$router.push('/trash-questions-management')
    },
    getquestions() {
      this.$http
        .get(`admin/questions/get-all-paginates?page=${this.page}&total=${this.total}`)
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
    getquestionCategories() {
      this.$http
        .get('admin/question-categories')
        .then(res => {
          res.data.data.forEach(question_category => {
            this.questionCategorieso.push({
              text: question_category.name,
              value: question_category.id,
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
    save() {
      if (this.editedIndex > -1) {
        //edit route
        this.$http
          .post(`admin/questions/update/${this.editedItem.id}`, {
            question: this.editedItem.question,
            question_category_id: this.editedItem.question_category.id,
            answer: this.editedItem.answer,
            status: 1,
          })
          .then(res => {
            this.dialog = false

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
          .post('admin/questions/store', {
            question: this.editedItem.question,
            question_category_id: this.editedItem.question_category.id,

            answer: this.editedItem.answer,
            status: 1,
          })

          .then(res => {
            this.dialog = false
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
      this.editedIndex = this.questions.indexOf(item)
      // Object.assign(this.editedItem, {
      //   ...item,
      // })
      let id = Number(item.question_category.id)

      this.editedItem = {}
      this.editedItem = Object.assign(
        {},
        {
          ...item,
        },
      )
      setTimeout(() => {
        this.editedItem.question_category.id = id
      }, 1000)
    },
    createItem() {
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.questions.indexOf(item)
      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/questions/destroy/${item.id}`)

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
