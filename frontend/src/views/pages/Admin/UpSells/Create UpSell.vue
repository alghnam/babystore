<template>
  <div>
      <v-col cols="12" class="pb-3">
 
    <v-simple-table class="mx-auto pb-5 rounded-xl elevation-10">
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="primary" dark class="" v-bind="attrs" v-on="on" icon>
          <v-icon>mdi-plus-circle</v-icon>
        </v-btn>
      </template>

      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length">More info about {{ item.name }}</td>
      </template>
      <!-- page layout -->
      <div class="container">
        <div class="row">
          <v-card class="col-sm-12 mx-auto">
            <!-- page title -->
            <v-card-title class="pa-2">
              <v-alert class="col-sm-12 mx-auto white--text font-2 text-center" dark color="primary">
                <v-icon large dark color="white">mdi-account-circle</v-icon> انشاء المبيعات
              </v-alert>
            </v-card-title>
            <!-- page content -->
            <v-card-text>
              <div class="row">
                <!-- left section -->
                <v-col xs="12" sm="12" md="8" lg="8" xl="8">
                  <v-card outlined>
                    <v-card-text>
                      <v-row>
                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <v-text-field
                            class="col-sm-5 mx-auto"
                            outlined
                            dense
                            label="العنوان"
                            v-model="editedItem.name"
                          ></v-text-field>
                               <v-text-field
                            class="col-sm-5 mx-auto"
                            outlined
                            dense
                            label="الفوتر"
                            v-model="editedItem.footer"
                          ></v-text-field>
                          <vue-editor v-model="editedItem.description" :editorToolbar="customToolbar"></vue-editor>

                
                     
                        </v-col>

                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <v-card>
                            <v-list-item class="pr-0">
                              <v-list-item-action class="pa-0">
                                <v-switch class="mt-0 pa-0" v-model="data_similar" color="red" hide-details></v-switch>
                              </v-list-item-action>
                              <v-list-item-content>
                                <p class="text-right pr-3 pl-3" style="margin: auto">المنتج</p>
                              </v-list-item-content>
                              <v-list-item-action class="pa-0 ma-0" style="border-left: 0.3px solid #808080">
                                <v-btn fab tile @click="show_similars = !show_similars">
                                  <v-icon>
                                    {{
                                      show_similars == true
                                        ? 'mdi-arrow-up-bold-hexagon-outline'
                                        : 'mdi-arrow-down-bold-hexagon-outline'
                                    }}
                                  </v-icon>
                                </v-btn>
                              </v-list-item-action>
                            </v-list-item>
                            <v-divider></v-divider>
                            <v-card-text v-show="show_similars" class="mt-3 pa-2" v-if="data_similar == true">
                              <v-row>
                                <v-col sm="12" md="12" lg="12" xl="12">
                                  <v-row> </v-row>

                                  <v-col sm="12" md="12" lg="12" xl="12">
                                    <v-row>
                                      <v-col sm="12" md="12" lg="12" xl="12">
                                        <v-menu rounded offset-y >
                                          <template v-slot:activator="{ attrs, on }" >
                                            <v-text-field
                                              
                                              class="white--text ma-5"
                                              v-bind="attrs"
                                              v-on="on"
                                              outlined
                                              dense
                                              label="اختار المنتج الذي تريد وضع العروض عليه"
                                              v-model="productsModel"
                                              v-show="!render"
                                            >
                                            </v-text-field>
                                          </template>

                                          <v-list>
                                            <v-list-item v-for="item in prods" :key="item" link>
                                              <v-list-item-title
                                                v-text="item.text"
                                                @click="getProduct(item)"
                                              ></v-list-item-title>
                                            </v-list-item>
                                          </v-list>
                                        </v-menu>

                                        <div v-for="proo in products" :key="proo.id">
                                          <!--   <h4>{{ proo.name }}</h4> -->
                                          <h4>{{ proo.text }}</h4>

                                          <v-btn color="default" class="mt-6" @click="deleteProduct(proo)"> حذف </v-btn>
                                        </div>

                                        <v-btn color="blue lighten-1" class="col-sm-5 mx-auto" dark  v-if="render==false"
                                          >حفظ <i class="fas fa-file mr-3"></i
                                        ></v-btn>
                                      </v-col>
                                    </v-row>
                                  </v-col>

                                  <v-col sm="12" md="12" lg="12" xl="12">
                                    <v-row>
                                      <v-col sm="12" md="12" lg="12" xl="12">
                                        <v-menu rounded offset-y>
                                          <template v-slot:activator="{ attrs, on }">
                                            <v-text-field
                                              class="white--text ma-5"
                                              v-bind="attrs"
                                              v-on="on"
                                              outlined
                                              dense
                                              label="اختار العروض التي تريد اظهارها على هذا المنتج"
                                              v-model="productUpsellsModel"
                                            >
                                            </v-text-field>
                                          </template>

                                          <v-list>
                                            <v-list-item v-for="item in prodUpsells" :key="item" link>
                                              <v-list-item-title
                                                v-text="item.text"
                                                @click="getProductUpsell(item)"
                                              ></v-list-item-title>
                                            </v-list-item>
                                          </v-list>
                                        </v-menu>

                                        <div v-for="proo in productUpsells" :key="proo.id">
                                          <!--   <h4>{{ proo.name }}</h4> -->
                                          <h4>{{ proo.text }}</h4>

                                          <v-btn color="default" class="mt-6" @click="deleteProductUpsell(proo)">
                                            حذف
                                          </v-btn>
                                        </div>

                                                                                               <v-btn color="blue lighten-1" style="margin-left: 543px" @click="saveUpSell()" dark
                    >حفظ <i class="fas fa-file mr-3"></i
                  ></v-btn>
                                      </v-col>
                                    </v-row>
                                  </v-col>
                                </v-col>
                              </v-row>
                            </v-card-text>
                            <v-card-text v-show="show_similars" class="mt-3 pa-2" v-if="data_similar == false">
                              <v-alert color="red" class="text-center">لا يوجد بيانات لعرضها</v-alert>
                            </v-card-text>
                          </v-card>
                        </v-col>
                      </v-row>
                    </v-card-text></v-card
                  >
                </v-col>

                <div v-if="product_id !== null && showSimilars">
                  <v-menu rounded offset-y>
                    <template v-slot:activator="{ attrs, on }">
                      <v-text-field
                        class="white--text ma-5"
                        v-bind="attrs"
                        v-on="on"
                        outlined
                        dense
                        label="المستخدمين"
                        v-model="users"
                      >
                      </v-text-field>
                    </template>

                    <v-list>
                      <v-list-item v-for="item in prods" :key="item" link>
                        <v-list-item-title v-text="item.text" @click="getProduct(item)"></v-list-item-title>
                      </v-list-item>
                    </v-list>
                  </v-menu>

                  <div v-for="similar in products" :key="similar.id">
                    <h4>{{ similar.text }}</h4>

                    <v-btn color="default" class="mt-6" @click="deleteSimilar(similar)"> حذف </v-btn>
                  </div>
                  <v-btn color="blue lighten-1" class="col-sm-5 mx-auto" @click="saveUpSell()" dark
                    >حفظ <i class="fas fa-file mr-3"></i
                  ></v-btn>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </div>
      </div>
    </v-simple-table>
    </v-col>
  </div>
</template>

<script>
import axios from 'axios'
import { VueEditor } from 'vue2-editor'

export default {
  components: {
    VueEditor,
  },
  computed: {

  },
  data() {
    return {
      productsModel: [],
      productUpsellsModel: [],
      prodUpsells: [],
      productUpsells: [],
      products: [],
      prods: [],
      prodUpsellsIds: [],
      productId: null,
      render: false,
      is_deleted: false,
      is_deletedUpSell: false,
     
      //sections data
      show_similars: false,
      data_similar: true,
      items: [
        {
          action: 'mdi-ticket',
          items: [{ title: 'List Item' }],
          title: 'Attractions',
          show: false,
        },
        {
          action: 'mdi-silverware-fork-knife',
          active: true,
          items: [{ title: 'Breakfast & brunch' }, { title: 'New American' }, { title: 'Sushi' }],
          title: 'Dining',
          show: false,
        },
      ],
      //description data
      customToolbar: [
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
        ['link', 'code-block'],
      ],
      dialog: false,
     
      products: [],
      product: [],
      productsSim: [],
      productsSimilar: [],
      option: null,
      users: null,
     
      statuses: [
        {
          text: 'Active',
          value: 1,
        },
        {
          text: 'InActive',
          value: 0,
        },
      ],
    
      editedIndex: -1,
      editedItem: {
        name: null,
        description: null,
        footer: null,
      },
     
      item: {},
      defaultItem: {},
      status: 0,
      snackbar: false,
      text: null,
      color: null,

      total: 0,
      pageInfo: null,
      page: 1,
      content: '<h2>I am Example</h2>',
      editorOption: {
        debug: 'info',
        placeholder: 'type your description ...',
        readOnly: true,
        theme: 'snow',
      },
     
    }
  },

  watch: {
   
    search(word) {
      this.$http
        .get(`admin/users/search/${word}`)
        .then(res => {
          this.products.push(res.data.data.name)
        })
        .catch(error => {
          this.callMessage(error.response.data.message)
        })
    },

    productsModel(val) {
      if (val) {
        this.$http
          .get(`admin/products/search/${val}`)
          .then(res => {
            this.prods=[]

            res.data.data.forEach(pro => {

              // this.prods = []
              this.prods.push({
                text: pro.name,
                value: pro.id,
              })
            })
          })
          .catch(error => {
            if (error && error.response) {
              this.callMessage(error.response.data.message)
            }
          })
      } else {
        this.prods = []
      }
    },

    productUpsellsModel(val) {
      if (val) {
        this.$http
          .get(`admin/products/search/${val}`)
          .then(res => {
            res.data.data.forEach(pro => {
              this.prodUpsells.push({
                text: pro.name,
                value: pro.id,
              })
            })
          })
          .catch(error => {
            if (error && error.response) {
              this.callMessage(error.response.data.message)
            }
          })
      } else {
        this.prodUpsells = []
      }
    },
  },
  created() {
  },
  methods: {


    callMessage(message) {
      this.snackbar = true
      this.text = message
    },


    getProduct(item) {
      let usersIds = []
      this.products.forEach(el => {
        usersIds.push(el.id)
      })

      if (!usersIds.includes(item.value)) {
        this.products.push(item)
        this.users = item.first_name
      }
      if (this.products.length !== 0) {
        this.render = true
        this.productId = this.products[0].value
      }
    },
    getProductUpsell(item) {
      let usersIds = []
      this.productUpsells.forEach(el => {
        usersIds.push(el.id)
      })

      if (!usersIds.includes(item.value)) {
        this.productUpsells.push(item)
        this.users = item.first_name
      }
    },





    showTrash() {
      this.$router.push('/trash-products-managment')
    },

  
    deleteProduct(pro) {
      const index = this.products.indexOf(pro)
      this.products.splice(index, 1)
      this.is_deleted = true
      this.render = false

    },

    deleteProductUpsell(pro) {
      const index = this.productUpsells.indexOf(pro)
      this.productUpsells.splice(index, 1)
      this.is_deletedUpSell = true

    },

    async saveUpSell() {
      if (this.is_deletedUpSell == true) {
        this.prodUpsellsIds = []
      }
      this.productUpsells.forEach(el => {
        let result=this.prodUpsellsIds.push(el.value)
               if(!result){

        upsellId = el.value
        this.prodUpsellsIds.push(upsellId)
       }
      })

      this.$http
        .post('admin/upsells/store', {
          name: this.editedItem.name,
          description: this.editedItem.description,
          footer: this.editedItem.footer,
          product_id: this.productId,
          upsells: this.prodUpsellsIds,
        })

        .then(res => {
        this.prodUpsellsIds = []
        this.productUpsells=[]
        this.products=[]

         this.editedItem= {
        name: null,
        description: null,
        footer: null,
      }
     
        
          
        })
        .catch(error => {
          if(error&&error.response){
        this.prodUpsellsIds = []

            this.callMessage(error.response.data.message)
          }
        
        })


    },



    editItem(item) {
      this.dialog = true
      this.editedIndex = this.products.indexOf(item)
      item.category.id = item.category_id

      Object.assign(this.editedItem, {
        ...item,
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
