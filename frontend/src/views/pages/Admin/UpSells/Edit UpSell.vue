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
                  <v-icon large dark color="white">mdi-account-circle</v-icon> تعديل مبيعات
                </v-alert>
              </v-card-title>
              <!-- page content -->
              <v-card-text>
                <div class="row">
                  <!-- left section -->
                  <v-col xs="12" sm="12" md="9" lg="9" xl="9">
                    <v-card outlined>
                      <v-card-text>
                        <v-row>
                          <v-col cols="12" sm="12" md="5" lg="5" xl="5" class="mx-auto">
                            <v-text-field
                              class=""
                              outlined
                              dense
                              label="العنوان"
                              v-model="editedItem.name"
                            ></v-text-field>
                          </v-col>
                          <v-col cols="12" sm="12" md="5" lg="5" xl="5" class="mx-auto">
                            <v-text-field
                              class=""
                              outlined
                              dense
                              label="الفوتر"
                              v-model="editedItem.footer"
                            ></v-text-field>
                          </v-col>

                          <v-col cols="12" sm="12" md="11" lg="11" xl="11" class="mx-auto">
                            <vue-editor v-model="editedItem.description" :editorToolbar="customToolbar"></vue-editor>
                          </v-col>

                          <v-col xs="12" sm="12" md="12" lg="12" xl="12" v-if="editedItem.product"> </v-col>

                          <v-col xs="12" sm="12" md="11" lg="11" xl="11" class="mx-auto">
                            <v-card>
                              <v-list-item class="pr-0 pl-0">
                                <v-list-item-action class="pa-0">
                                  <v-switch
                                    class="mt-0 pa-0"
                                    v-model="data_similar"
                                    color="red"
                                    hide-details
                                  ></v-switch>
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
                              <v-card-text v-show="show_similars" class="mt-3 pa-0" v-if="data_similar == true">
                                <v-row class="pa-0">
                                  <v-col sm="12" md="7" lg="7" xl="7" class="pa-1">
                                    <v-menu rounded offset-y class="pr-0 pl-0">
                                      <template v-slot:activator="{ attrs, on }">
                                        <v-text-field
                                          class="white--text mt-5"
                                          v-bind="attrs"
                                          v-on="on"
                                          outlined
                                          dense
                                          clearable
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
                                  </v-col>
                                  <v-col sm="12" md="7" lg="7" xl="7" class="pa-1">
                                    <v-btn color="primary" class="mt-3" @click="getupsellsProduct(item)">
                                      عرض المبيعات
                                      <v-icon color="white">mdi-pencil</v-icon>
                                    </v-btn>
                                    <v-btn color="blue lighten-1 mt-3 mr-2 ml-2" @click="saveUpSell()" dark
                                      >حفظ <v-icon>mdi-file</v-icon></v-btn
                                    >
                                  </v-col>
                                  <v-col sm="12" md="5" lg="5" xl="5" class="pa-1"> </v-col>
                                  <v-col cols="12" v-if="productUpsells.length > 0">
                                    <v-row>
                                      <v-col
                                        sm="12"
                                        md="4"
                                        lg="4"
                                        xl="3"
                                        class="pa-1 mt-3"
                                        v-for="proo in productUpsells"
                                        :key="proo.id"
                                      >
                                        <v-card elevation="15"  class="">
                                          <v-img height="100%" :src="$store.state.baseURL + '/storage/' + proo.img">
                                            <v-btn
                                              color="red"
                                              tile
                                              icon
                                              class=""
                                              @click="deleteProductUpsellRender(proo)"
                                            >
                                              x
                                            </v-btn>
                                            <v-row class="pa-2">
                                              <v-col cols="12" xs="12" sm="12" md="11" lg="11" xl="11">
                                                <v-chip class="w-100">{{ proo.text }}</v-chip>
                                              </v-col>
                                            </v-row>
                                          </v-img>
                                        </v-card>
                                      </v-col></v-row
                                    >
                                    <v-divider class="mt-9 w-100" style="background-color: black"></v-divider>
                                  </v-col>
                                  <v-col
                                    sm="12"
                                    md="4"
                                    lg="4"
                                    xl="3"
                                    class="pa-1 mt-3"
                                    v-for="upsellProduct in upsellsProduct"
                                    :key="upsellProduct"
                                  >
                                    <v-card elevation="15" class="">
                                      <v-img
                                        height="100%"
                                        :src="
                                          upsellProduct.product_images.length > 0
                                            ? $store.state.baseURL +
                                              '/storage/' +
                                              upsellProduct.product_images[0].filename
                                            : ''
                                        "
                                      >
                                        <v-btn
                                          color="red"
                                          tile
                                          icon
                                          class=""
                                          @click="deleteUpsellProduct(upsellProduct.id)"
                                        >
                                          x
                                        </v-btn>
                                        <v-row class="pa-2">
                                          <v-col cols="12" xs="12" sm="12" md="11" lg="11" xl="11">
                                            <v-chip class="w-100">{{ upsellProduct.name }}</v-chip>
                                          </v-col>
                                        </v-row>
                                      </v-img>
                                    </v-card>
                                  </v-col>
                                  <v-col sm="12" md="5" lg="5" xl="5"></v-col>
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
                  <v-col sm="12" md="3" lg="3" xl="3">
                    <v-card min-height="250" height="fit-content" outlined class="pa-3">
                      <h2>تفاصيل المنتج</h2>
                      <h3>الاسم</h3>
                      <h5>{{ editedItem.product.name }}</h5>
                      <h3>الوصف</h3>
                      <p v-html="editedItem.product.description"></p
                    ></v-card>
                  </v-col>
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
  computed: {},
  data() {
    return {
      productsModel: [],
      productUpsellsModel: [],
      prodUpsells: [],
      productUpsells: [],
      products: [],
      prods: [],
      id: null,
      product: null,
      prodUpsellsIds: [],
      productId: null,
      render: false,
      is_deleted: false,
      is_deletedUpSell: false,
      upsellsProduct: [],

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
      product_id: null,
      products: [],
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
      photo: null,
      showVariants: false,
      showSimilars: false,
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
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    productUpsellsModel(val) {
      if (val) {
        this.$http
          .get(`admin/products/search/${val}`)
          .then(res => {
            this.prodUpsells = []
            res.data.data.forEach(pro => {
              this.prodUpsells.push({
                text: pro.name,
                value: pro.id,
                img: pro.product_images[0].filename,
              })
            })
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            }
          })
      } else {
        this.prodUpsells = []
      }
    },
  },
  created() {
    this.id = this.$route.params.id
    this.product_id = this.$route.params.product_id
    this.showUpsell()
    //  this.getupsellsProduct()
    // this.showProduct()
  },
  methods: {
    trimAttribute(value, size) {
      if (value !== null) {
        let new_url = value.slice(0, 13) + '/thumbnail/' + value.slice(14)
        let index = new_url.length - 4
        let url = new_url.slice(0, index) + size + new_url.slice(index)
        return url.substr(0, url.length)
      }
    },
    showUpsell() {
      this.$http.get(`admin/upsells/show/${this.id}`).then(res => {
        this.editedItem = res.data.data
      })
    },
    getupsellsProduct() {
      this.$http.get(`admin/upsells/upsells-product/${this.product_id}`).then(res => {
        this.upsellsProduct = res.data.data
      })
    },
    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    getProductUpsell(item) {
      let prosIds = []
      this.productUpsells.forEach(el => {
        prosIds.push(el.id)
      })

      if (!prosIds.includes(item.value)) {
        let result = this.productUpsells.includes(item.value)
        if (!result) {
          this.productUpsells.push(item)
        }
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

    deleteUpsellProduct(upsell) {
      const index = this.upsellsProduct.indexOf(upsell)

      this.$http.get(`admin/upsells/delete-upsell-product/${this.id}/${upsell}`).then(res => {
        this.upsellsProduct.splice(index, 1)
      })
    },
    deleteProductUpsellRender(pro) {
      let result = this.upsellsProduct.includes(pro)
      if (result) {
        this.deleteUpsellProduct(pro)
      }
      this.productUpsells.splice(pro)
      this.is_deletedUpSell = true
    },

    async saveUpSell() {
      if (this.is_deletedUpSell == true) {
        this.prodUpsellsIds = []
      }
      let upsellId
      this.productUpsells.forEach(el => {
        let result = this.prodUpsellsIds.includes(el.value)
        if (!result) {
          upsellId = el.value
          this.prodUpsellsIds.push(upsellId)
          this.deleteProductUpsellRender(upsellId)
        }
      })

      this.$http
        .post(`admin/upsells/update/${this.id}/${this.product_id}`, {
          upsells: this.prodUpsellsIds,
          name: this.editedItem.name,
          description: this.editedItem.description,
          footer: this.editedItem.footer,
        })

        .then(res => {
          this.getupsellsProduct()
          this.productUpsells = []
          this.prodUpsellsIds = []
          ;(this.editedItem = {
            name: null,
            description: null,
            footer: null,
          }),
            (this.$store.state.snackbar = true)
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
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
