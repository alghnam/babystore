<template>
  <div>
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
                <v-icon large dark color="white">mdi-account-circle</v-icon> تعديل منتج
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
                          <v-text-field outlined dense label="إسم المنتج" v-model="editedItem.name"></v-text-field>
                        </v-col>
                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <!-- image choose section -->

                          <input type="file" name="" id="" ref="chooseImg" hidden />

                          <v-slide-group v-model="model" class="pa-4" active-class="success" show-arrows>
                            <v-slide-item>
                              <v-card color="primary" class="ma-4" height="200" width="200" @click="chooseImg">
                                <v-card-text>
                                  <v-row>
                                    <v-icon dark color="white" size="75" class="mt-15 mx-auto"
                                      >mdi-plus-box-outline</v-icon
                                    >
                                  </v-row>
                                </v-card-text>
                              </v-card></v-slide-item
                            >
                            <v-slide-item v-for="(img, i) in imgs" :key="i" v-slot="{ active, toggle }">
                              <!-- <v-slide-item v-for="(img, i) in editedItem.product_images" :key="i" v-slot="{ active, toggle }">-->

                              <v-card
                                :color="active ? undefined : 'grey lighten-1'"
                                class="ma-4"
                                height="200"
                                width="200"
                                @click="toggle"
                              >
                                <v-img
                                  height="100%"
                                  width="100%"
                                  :src="
                                    img.filename ? $store.state.baseURL + '/storage/' + trim(img.filename, '(S)') : img
                                  "
                                >
                                  <v-btn
                                    v-if="img.id"
                                    color="primary"
                                    class="mt-6"
                                    style="margin-left: 101px"
                                    @click="deleteImage(img)"
                                    ><v-icon color="black">mdi-delete</v-icon>
                                  </v-btn>
                                  <v-btn
                                    v-if="!img.id"
                                    color="primary"
                                    class="mt-6"
                                    style="margin-left: 101px"
                                    @click="deleteImageNotStore(i)"
                                    ><v-icon color="black">mdi-delete</v-icon>
                                  </v-btn>
                                </v-img>

                                <v-img height="100%" width="100%" :src="img">
                                  <v-btn
                                    v-if="img.id"
                                    color="red"
                                    class="mt-6"
                                    style="margin-left: 101px"
                                    @click="deleteImage(img)"
                                    ><v-icon color="black">mdi-delete</v-icon>
                                  </v-btn>
                                  <v-btn
                                    v-if="!img.id"
                                    color="red"
                                    style="margin-left: 101px"
                                    class="mt-6"
                                    @click="deleteImageNotStore(i)"
                                    ><v-icon color="black">mdi-delete</v-icon>
                                  </v-btn>
                                </v-img>
                              </v-card>
                            </v-slide-item>
                          </v-slide-group>
                        </v-col>
                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <vue-editor v-model="editedItem.description" :editorToolbar="customToolbar"></vue-editor>
                        </v-col>
                        <!-- price section -->
                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <v-card>
                            <v-list-item class="pr-0 pl-0">
                              <v-list-item-content>
                                <p class="text-right pr-3 pl-3" style="margin: auto">السعر</p>
                              </v-list-item-content>
                              <v-list-item-action class="pa-0 ma-0" style="border-left: 0.3px solid #808080">
                                <v-btn fab tile @click="show_prices = !show_prices">
                                  <v-icon>
                                    {{
                                      show_prices == true
                                        ? 'mdi-arrow-up-bold-hexagon-outline'
                                        : 'mdi-arrow-down-bold-hexagon-outline'
                                    }}
                                  </v-icon>
                                </v-btn>
                              </v-list-item-action>
                            </v-list-item>
                            <v-divider></v-divider>
                            <v-card-text v-show="show_prices" class="mt-3 pa-2" v-if="data_price == true">
                              <v-row>
                                <v-col sm="12" md="9" lg="9" xl="9" class="mx-auto">
                                  <v-row>
                                    <v-col sm="12" md="5" lg="5" xl="5" class="mx-auto">
                                      <v-text-field
                                        style="width: 100%"
                                        outlined
                                        dense
                                        label="السعر الأصلي قبل الخصم"
                                        v-model="editedItem.original_price"
                                        type="number"
                                      ></v-text-field>
                                    </v-col>

                                    <v-col sm="12" md="5" lg="5" xl="5">
                                      <v-text-field
                                        style="width: 100%"
                                        outlined
                                        dense
                                        label="السعر النهائي بعد الخصم"
                                        v-model="editedItem.price_discount_ends"
                                        type="number"
                                      ></v-text-field>
                                    </v-col>
                                  </v-row>
                                </v-col>
                              </v-row>
                            </v-card-text>
                            <v-card-text v-show="show_prices" class="mt-3 pa-2" v-if="data_price == false">
                              <v-alert color="red" class="text-center">لا يوجد بيانات لعرضها</v-alert>
                            </v-card-text>
                          </v-card>
                        </v-col>
                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <v-card>
                            <v-list-item class="pr-0 pl-0">
                              <v-list-item-action class="pa-0">
                                <v-switch class="mt-0 pa-0" v-model="data_variant" color="red" hide-details></v-switch>
                              </v-list-item-action>

                              <v-list-item-content>
                                <p class="text-right pr-3 pl-3" style="margin: auto">المتغيرات</p>
                              </v-list-item-content>
                              <v-list-item-action class="pa-0 ma-0" style="border-left: 0.3px solid #808080">
                                <v-btn fab tile @click="show_variants = !show_variants">
                                  <v-icon>
                                    {{
                                      show_variants == true
                                        ? 'mdi-arrow-up-bold-hexagon-outline'
                                        : 'mdi-arrow-down-bold-hexagon-outline'
                                    }}
                                  </v-icon>
                                </v-btn>
                              </v-list-item-action>
                            </v-list-item>
                            <v-divider></v-divider>
                            <v-card-text v-show="show_variants" class="mt-3 pa-2" v-if="data_variant == true">
                              <v-btn color="primary" class="mt-6" @click="addOption()">اضافة خيار </v-btn>
                              <v-row v-if="main_attrs.length !== 0" class="mt-5">
                                <v-col sm="12" md="12" lg="12" xl="12">
                                  <v-row>
                                    <h4 class="pr-3 pl-3">ادخل المواصفات التي تريديها لهذا المنتج</h4>
                                    <v-col sm="12" md="12" lg="12" xl="12" v-for="(item, i) in main_attrs" :key="i">
                                      <v-row>
                                        <v-col sm="12" md="3" lg="3" xl="3">
                                          <v-text-field
                                            outlined
                                            dense
                                            label="الخيار"
                                            v-model="item.name"
                                            id="var1"
                                            ref="var1"
                                          ></v-text-field>
                                        </v-col>
                                        <v-col sm="12" md="7" lg="7" xl="7">
                                          <v-combobox
                                            outli
                                            ned
                                            dense
                                            label="القيمة"
                                            v-model="item.attrs"
                                            chips
                                            multiple
                                            clearable
                                            deletable-chips
                                          ></v-combobox>
                                        </v-col>
                                        <v-col sm="12" md="2" lg="2" xl="2" class="d-flex">
                                          <v-btn
                                            color="blue lighten-1"
                                            @click="saveAttributes(item)"
                                            dark
                                            fab
                                            tile
                                            x-small
                                            class="rounded-lg mt-1 mx-auto"
                                            ><v-icon>mdi-file</v-icon></v-btn
                                          >
                                          <div
                                            v-if="
                                              (item.name == null || item.name == 'undefined' || item.name == ' ') &&
                                              (item.attrs == null || item.attrs == 'undefined' || item.attrs == ' ')
                                            "
                                          >
                                            <v-btn color="primary" class="mt-1" @click="deleteOption(i)">
                                              <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                          </div>
                                          <div
                                            v-if="
                                              (item.name !== null || item.name !== 'undefined' || item.name !== ' ') &&
                                              (item.attrs !== null || item.attrs !== 'undefined' || item.attrs !== ' ')
                                            "
                                          >
                                            <v-btn
                                              fab
                                              tile
                                              x-small
                                              color="primary"
                                              class="mt-1 rounded-lg"
                                              @click="deleteItem(item.id, i)"
                                            >
                                              <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                          </div>
                                        </v-col>
                                      </v-row>
                                    </v-col>
                                  </v-row>
                                  <h4 style="margin-left: 178px">
                                    عليك ان تختار من تلك المواصفات ليتم تنسيقها مع بعضها
                                  </h4>

                                  <v-col sm="12" md="12" lg="12" xl="12">
                                    <v-row>
                                      <v-col sm="12" md="12" lg="12" xl="12">
                                        <v-row>
                                          <v-col sm="12" md="12" lg="12" xl="12">
                                            <v-select
                                              v-for="(attr, i) in main_attrs"
                                              :label="attr.name"
                                              :items="attr.attrs"
                                              :key="i"
                                              outlined
                                              v-model="x.attr[i].value"
                                              @change="x.attr[i].name = attr.name"
                                              dense
                                            >
                                            </v-select>
                                          </v-col>
                                        </v-row>
                                      </v-col>
                                    </v-row>
                                    <v-btn @click="saveSubProduct()" color="primary" dark>حفظ</v-btn>
                                  </v-col>

                                  <v-col sm="12" md="12" lg="12" xl="12">
                                    <v-row>
                                      <v-col sm="12" md="12" lg="12" xl="12">
                                        <div v-if="editedItem.product_array_attributes">
                                          <div v-if="editedItem.product_array_attributes.length !== 0">
                                            <h4 style="margin-left: 240px">ادخل تفاصيل المواصفات لهذا المنتج</h4>
                                            <v-row>
                                              <v-col
                                                sm="12"
                                                md="4"
                                                lg="4"
                                                xl="3"
                                                class="mt-5"
                                                v-for="(attribute1, i) in editedItem.product_array_attributes"
                                                :key="i"
                                              >
                                                <v-card elevation="15" min-height="200" class="pa-3">
                                                  <div v-if="attribute1 && attribute1.attributes">
                                                    <div v-for="(attr, i) in attribute1.attributes" :key="i">
                                                      <div v-if="attr.name && attr.value" class="mt-3">
                                                        <div v-if="i < 3">
                                                          <v-chip
                                                            label
                                                            color="primary"
                                                            dark
                                                            style="width: 100% !important"
                                                          >
                                                            {{ attr.name }}
                                                          </v-chip>
                                                          <v-chip label style="width: 100% !important" class="mt-2">
                                                            {{ attr.value }}
                                                          </v-chip>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    <v-btn text class="mt-3 w-100" @click="showDetails(attribute1)"
                                                      >إضغط للمزيد</v-btn
                                                    >
                                                    <!-- <div v-if="attribute1.image">
                                                      <img
                                                        :src="
                                                          $store.state.baseURL +
                                                          '/storage/' +
                                                          trimAttribute(attribute1.image.url, '(S)')
                                                        "
                                                        alt="product image"
                                                        v-if="!photo"
                                                      />
                                                      <img
                                                        :src="editedItem.photo_url"
                                                        v-if="editedItem.photo_url"
                                                        style="height: 118px; width: 84px"
                                                      /> <img
                                                        :src="
                                                          $store.state.baseURL +
                                                          '/storage/' +
                                                          trimAttribute(attribute1.image.url, '(S)')
                                                        "
                                                        alt="product image"
                                                        v-if="!photo"
                                                      />
                                                      <img
                                                        :src="editedItem.photo_url"
                                                        v-if="editedItem.photo_url"
                                                        style="height: 118px; width: 84px"
                                                      />
                                                    </div> -->
                                                  </div>
                                                  <div class="w-100 mt-3">
                                                    <v-btn
                                                      @click="editAttr(attribute1, i)"
                                                      style="margin-left: 0px"
                                                      fab
                                                      x-small
                                                      class="rounded-lg"
                                                      dark
                                                      color="primary"
                                                    >
                                                      <v-icon>mdi-pen</v-icon>
                                                    </v-btn>
                                                    <v-btn
                                                      @click="deleteArrayAttribute(attribute1.id, i)"
                                                      style="margin-left: 0px"
                                                      fab
                                                      x-small
                                                      class="rounded-lg mr-3"
                                                    >
                                                      <v-icon>mdi-delete</v-icon>
                                                    </v-btn>
                                                  </div>
                                                </v-card>
                                              </v-col>
                                            </v-row>
                                          </div>
                                        </div>
                                      </v-col>
                                    </v-row>
                                  </v-col>
                                </v-col>
                              </v-row>
                            </v-card-text>
                            <v-card-text v-show="show_variants" class="mt-3 pa-2" v-if="data_variant == false">
                              <v-alert color="red" class="text-center">لا يوجد بيانات لعرضها</v-alert>
                            </v-card-text>
                          </v-card>
                        </v-col>
                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <v-card>
                            <v-list-item class="pr-0 pl-0">
                              <v-list-item-action class="pa-0">
                                <v-switch class="mt-0 pa-0" v-model="data_similar" color="red" hide-details></v-switch>
                              </v-list-item-action>
                              <v-list-item-content>
                                <p class="text-right pr-3 pl-3" style="margin: auto">المنتجات المشابهة</p>
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
                                        <v-menu rounded offset-y>
                                          <template v-slot:activator="{ attrs, on }">
                                            <v-text-field
                                              class="white--text ma-5"
                                              v-bind="attrs"
                                              v-on="on"
                                              outlined
                                              dense
                                              label="المنتجات المشابهة"
                                              v-model="similars"
                                            >
                                            </v-text-field>
                                          </template>

                                          <v-list>
                                            <v-list-item v-for="item in productsSim" :key="item" link>
                                              <v-list-item-title
                                                v-text="item.text"
                                                @click="getSimilar(item)"
                                              ></v-list-item-title>
                                            </v-list-item>
                                          </v-list>
                                        </v-menu>
                                        <!-- similar products cards  -->
                                        <v-row>
                                          <v-col
                                            cols="12"
                                            md="6"
                                            lg="4"
                                            xl="3"
                                            v-for="similar in productsSimilar"
                                            :key="similar.id"
                                          >
                                            <v-card  elevation="15">
                                              <v-img
                                                height="100%"
                                                width="100%"
                                                :src="
                                                  similar.product_images.length > 0
                                                    ? $store.state.baseURL +
                                                      '/storage/' +
                                                      similar.product_images[0].filename
                                                    : ''
                                                "
                                              >
                                                <v-btn color="red" tile icon class="" @click="deleteSimilar(similar)">
                                                  x
                                                </v-btn>
                                                <v-chip v-if="similar.name" class="mt-2 w-100">{{
                                                  similar.name
                                                }}</v-chip>
                                              </v-img>
                                            </v-card>
                                          </v-col>

                                          <!-- <h4 v-if="similar.name">{{ similar.name }}</h4>
                                          <h4 v-if="similar.text">{{ similar.text }}</h4>

                                          <v-btn color="default" class="mt-6" @click="deleteSimilar(similar)">
                                            <v-icon>mdi-delete</v-icon>
                                          </v-btn> -->
                                        </v-row>
                                        <!-- end of similar products  -->
                                        <v-btn color="blue lighten-1" class="mt-5" @click="saveSimilarProduct()" dark>
                                          <v-icon>mdi-file</v-icon>
                                        </v-btn>
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

                <!-- right section -->
                <v-col xs="12" sm="12" md="4" lg="4" xl="4">
                  <v-card outlined>
                    <v-card-text>
                      <v-row>
                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <v-card>
                            <v-card-title style="direction: rtl"> المخزون </v-card-title>
                            <v-card-text>
                              <v-text-field
                                class="col-sm-12 mx-auto"
                                outlined
                                dense
                                label="الكمية"
                                v-model="editedItem.quantity"
                                type="number"
                              ></v-text-field>
                            </v-card-text>
                          </v-card>
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                  <v-card outlined class="mt-6">
                    <v-card-text>
                      <v-row>
                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <v-card>
                            <v-card-title style="direction: rtl">الظهور </v-card-title>
                            <v-card-text>
                              <v-select
                                class="col-sm-12 mx-auto"
                                outlined
                                dense
                                label="الحالة"
                                :items="statuses"
                                v-model="editedItem.status"
                              ></v-select>
                              <v-select
                                class="col-sm-12 mx-auto"
                                outlined
                                dense
                                label="عليه عروض"
                                :items="offers"
                                v-model="editedItem.is_offers"
                              ></v-select>
                            </v-card-text>
                          </v-card>
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                  <v-card outlined class="mt-6">
                    <v-card-text>
                      <v-row>
                        <v-col xs="12" sm="12" md="12" lg="12" xl="12">
                          <v-card>
                            <v-card-title style="direction: rtl"> الفئة </v-card-title>
                            <v-card-text>
                              <div v-if="editedItem.category">
                                <div v-if="editedItem.category.main_category">
                                  {{ editedItem.category.main_category.name }}
                                  {{ editedItem.category.name }}
                                  <v-select
                                    class="col-sm-12 mx-auto"
                                    outlined
                                    dense
                                    label="الفئة الرئيسية"
                                    :items="maincategorieso"
                                    v-model="editedItem.category.main_category.id"
                                  ></v-select>
                                </div>
                              </div>
                              <div v-else>
                                <v-select
                                  class="col-sm-12 mx-auto"
                                  outlined
                                  dense
                                  label="الفئة الرئيسية"
                                  :items="maincategorieso"
                                  v-model="editedItem.category.main_category"
                                ></v-select>
                              </div>
                              <v-select
                                v-if="editedItem.category"
                                class="col-sm-12 mx-auto"
                                outlined
                                dense
                                label="الفئة الفرعية"
                                :items="subcategorieso"
                                v-model="editedItem.category.id"
                              ></v-select>
                              <v-select
                                v-else
                                class="col-sm-12 mx-auto"
                                outlined
                                dense
                                label="الفئة الفرعية"
                                :items="subcategorieso"
                                v-model="editedItem.category"
                              ></v-select>

                              <v-select
                                v-if="editedItem.sub_category"
                                class="col-sm-12 mx-auto"
                                outlined
                                dense
                                label="الفئة  الفرعية الثانية"
                                :items="secondsubcategorieso"
                                v-model="editedItem.sub_category.id"
                              ></v-select>
                              <v-select
                                v-else
                                class="col-sm-12 mx-auto"
                                outlined
                                dense
                                label="الفئة الفرعية الثانية "
                                :items="secondsubcategorieso"
                                v-model="editedItem.sub_category"
                              ></v-select>
                            </v-card-text>
                          </v-card>
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                  <v-btn color="blue lighten-1" class="col-sm-12 mx-auto mt-5" @click="save()" dark
                    >حفظ <i class="fas fa-file mr-3"></i
                  ></v-btn>
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
                        label="المنتجات المشابهة"
                        v-model="similars"
                        @click="getSimilar()"
                      >
                      </v-text-field>
                    </template>

                    <v-list>
                      <v-list-item v-for="item in productsSim" :key="item" link>
                        <v-list-item-title v-text="item.text" @click="getSimilar(item)"></v-list-item-title>
                      </v-list-item>
                    </v-list>
                  </v-menu>

                  <div v-for="similar in productsSimilar" :key="similar">
                    <h4>{{ similar }}</h4>

                    <v-btn color="default" class="mt-6" @click="deleteSimilar(similar)"> حذف </v-btn>
                  </div>
                  <v-btn color="blue lighten-1" class="col-sm-5 mx-auto" @click="saveSimilarProduct()" dark
                    >حفظ <i class="fas fa-file mr-3"></i
                  ></v-btn>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </div>
      </div>
    </v-simple-table>
    <!-- edit the sub product -->
    <v-dialog
      v-model="subPDialog" 
      :width="$vuetify.breakpoint.name == 'xs' || $vuetify.breakpoint.name == 'sm' ? '100vw' : '40vw'"
    >
      <v-card min-height="70vh" class="pa-0">
        <v-row class="pa-10">
          <v-col cols="12" md="5" lg="5" xl="5" class="mx-auto">
            <v-img v-if="photo" elevation="15" :src="img" height="200"></v-img>
            <v-img
              v-else-if="editedSubItem.image"
              elevation="15"
              :src="$store.state.baseURL + '/storage/' + editedSubItem.image.url"
              height="200"
            ></v-img>
          </v-col>
          <v-col cols="12" md="5" lg="5" xl="5" class="mx-auto"> </v-col>
          <v-file-input
            truncate-length="15"
            outlined
            dense
            prepend-icon=""
            prepend-inner-icon="mdi-file-image"
            label="صورة المنتج"
            class="col-sm-5 mx-auto"
            v-model="photo"
          ></v-file-input>

          <v-text-field
            class="col-sm-5 mx-auto"
            outlined
            dense
            label="الكمية"
            v-model="editedSubItem.quantity"
            type="number"
          ></v-text-field>

          <v-col cols="12" xs="12" sm="12" md="5" lg="5" xl="5" class="mx-auto pr-0 pl-0">
            <h3 class="mb-3">السعر</h3>

            <v-text-field
              class="col-sm-12 mx-auto"
              outlined
              dense
              label="السعر الأصلي قبل الخصم"
              v-model="editedSubItem.original_price"
              type="number"
            ></v-text-field>

            <v-text-field
              class="col-sm-12 mx-auto"
              outlined
              dense
              label="السعر النهائي بعد الخصم"
              v-model="editedSubItem.price_discount_ends"
              type="number"
            ></v-text-field>
            <v-btn
              color="blue lighten-1"
              @click="saveDetailsArrayAttribute(editedSubItem)"
              dark
              class="mt-3"
              style="width: 100%"
              >حفظ <i class="fas fa-file mr-3"></i
            ></v-btn>
          </v-col>
          <v-col cols="12" xs="12" sm="12" md="5" lg="5" xl="5" class="mx-auto"> </v-col>
        </v-row>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="showDetailsDialog"
      :width="$vuetify.breakpoint.name == 'xs' || $vuetify.breakpoint.name == 'sm' ? '100vw' : '25vw'"
    >
      <v-card min-height="70vh">
        <v-row class="pa-3">
          <v-col cols="12" md="12" lg="12" xl="12" class="mx-auto">
            <v-img
              class="w-100"
              :src="$store.state.baseURL + '/storage/' + editedSubItem.image.url"
              v-if="editedSubItem.image"
            ></v-img>
          </v-col>
        </v-row>
        <div v-if="editedSubItem && editedSubItem.attributes">
          <div v-for="(attr, i) in editedSubItem.attributes" :key="i">
            <div v-if="attr.name && attr.value" class="mt-3">
              <div class="pa-5">
                <v-chip label color="primary" dark style="width: 100% !important">
                  {{ attr.name }}
                </v-chip>
                <v-chip label style="width: 100% !important" class="mt-2">
                  {{ attr.value }}
                </v-chip>
              </div>
            </div>
          </div>
        </div>
      </v-card>
    </v-dialog>
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
    x() {
      return this.sub_product
    },
  },
  data() {
    return {
      //sections data
      show_similars: false,
      //prices data
      show_prices: false,
      data_price: true,
      data_similar: true,
      //variants data
      show_variants: false,
      data_variant: true,
      // },
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
      add: false,
      editSave: false,
      deleteO: false,
      product_id: null,
      product_attribute_id: null,
      products: [],
      product: [],
      productsSim: [],
      productsSimilar: [],
      option: null,
      attributes: [],
      similars: null,
      categoryName: null,
      category_id: null,
      main_category_id_edit: null,
      main_category_id_create: null,
      maincategorieso: [],
      subcategorieso: [],
      secondsubcategorieso: [],
      photos: [],
      photos_seo: [],
      imgs_seo: [],
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
      offers: [
        {
          text: 'Yes',
          value: '1',
        },
        {
          text: 'No',
          value: '0',
        },
      ],
      c_attrs: [],
      editedIndex: -1,
      editedItem: {
        status: null,
        is_offers: null,

        category: {
          id: null,
          name: null,
          main_category: {
            name: null,
          },
          photo_url: null,
        },
        productAttributes: {
          product_id: null,
          option: null,
          value: null,
        },
        product_array_attributes: {
          product_id: null,
          attributes: [],
        },
        seo: {},
        storage_detail: {},
      },
      imgs: [],
      img: null,
      //
      item: {},
      defaultItem: {
        status: null,
        is_offers: null,

        category: {
          id: null,
          name: null,
          main_category: {
            name: null,
          },
          photo_url: null,
        },
        productAttributes: {
          product_id: null,
          option: null,
          value: null,
        },
        productArrayAttributes: {
          product_id: null,
          attributes: [],
        },
        seo: {},
        storage_detail: {},
      },
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
      main_attrs: [],
      sub_product: {
        attr: [],
      },
      product_array_attribute_id: null,
      photo: null,
      showVariants: false,
      showSimilars: false,
      base_imgs: [],
      i: 0,
      subPDialog: false,
      editedSubItem: {},
      showDetailsDialog: false,
      similarsIds: [],
    }
  },

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
    search(word) {
      this.$http
        .get(`admin/products/search/${word}`)
        .then(res => {
          this.productsSimilar.push(res.data.data.name)
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    // },
    photos(val) {
      this.imgs = []
      let files = val
      files.forEach(file => {
        let reader = new FileReader()
        let img = document.createElement('img')
        reader.addEventListener('load', e => {
          img.setAttribute('src', e.target.result)
          img.style.height = '50%'
          img.style.width = '10%'
          this.editedItem.product_images.push(e.target.result)
        })
        reader.readAsDataURL(file)
      })
    },
    photo(val) {
      this.img = []
      let file = val
      let reader = new FileReader()
      let img = document.createElement('img')
      reader.addEventListener('load', e => {
        img.setAttribute('src', e.target.result)
        img.style.height = '50%'
        img.style.width = '10%'
        this.img = e.target.result
      })
      reader.readAsDataURL(file)
    },

    photos_seo(val) {
      this.imgs_seo = []

      let imgHolderSeo = this.$refs.imgHolderSeo

      let filesSeo = val
      filesSeo.forEach(fileSeo => {
        let readerSeo = new FileReader()
        let img_seo = document.createElement('img')
        readerSeo.addEventListener('load', e => {
          img_seo.setAttribute('src', e.target.result)
          img_seo.style.height = '50%'
          img_seo.style.width = '10%'
          this.imgs_seo.push(e.target.result)
          imgHolderSeo.appendChild(img)
        })
        readerSeo.readAsDataURL(fileSeo)
      })
    },
    dialog(val) {
      val || this.close()
    },
    sub_product: {
      handler: function (val) {},
    },
    'editedItem.category.main_category.id': {
      handler: function (val) {
        //alert(val)
        this.main_category_id = val
        if (this.main_category_id !== undefined) {
          this.$http
            .get(`admin/categories/get-sub-categories-for-main/${this.main_category_id}`)
            .then(res => {
              this.subcategorieso = []

              res.data.data.forEach(subCategory => {
                this.subcategorieso.push({
                  text: subCategory.name,
                  value: subCategory.id,
                })
              })
            })

            .catch(error => {
              if (error && error.response) {
                this.$store.state.snackbar = true
                this.$store.state.text = error.response.data.message
              }
            })
        }
      },
    },
    'editedItem.category.id': {
      handler: function (val) {
        // this.sub_category_id = val
        this.editedItem.category.id = val
        if (this.editedItem.category && this.editedItem.category.id !== undefined) {
          this.$http
            .get(`admin/categories/sub/get-second-sub-categories-for-sub/${this.editedItem.category.id}`)
            .then(res => {
              this.secondsubcategorieso = []

              res.data.data.forEach(secondsubCategory => {
                this.secondsubcategorieso.push({
                  text: secondsubCategory.name,
                  value: secondsubCategory.id,
                })
              })
            })

            .catch(error => {
              if (error && error.response) {
                this.$store.state.snackbar = true
                this.$store.state.text = error.response.data.message
              }
            })
        }
      },
    },
    'editedItem.sub_category.id': {
      handler: function (val) {
        this.editedItem.sub_category.id = val
      },
    },

    'editedItem.product.category.name': {
      handler: function (val) {
        this.sub_category_id = val

        this.$http
          .get(`admin/products/get-products-for-category/${this.sub_category_id}`)
          .then(res => {
            res.data.data.forEach(subCategory => {
              this.productsCategory.push({
                text: subCategory.name,
                value: subCategory.id,
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
    },

    base_imgs(val) {
      let file = val[val.length - 1]
      let reader = new FileReader()
      reader.onload = e => {
        this.imgs.push(e.target.result)
      }
      reader.readAsDataURL(file)
    },
    similars(val) {
      if (val) {
        this.$http.get(`admin/products/search-for-similar/${val}`).then(res => {
          this.productsSim = []
          res.data.data.forEach(product => {
            this.productsSim.push({
              text: product.name,
              name: product.name,
              value: product.id,
              img: product.product_images[0],
              product_images: product.product_images,
            })
          })
        })
      } else {
        this.productsSim = []
      }
    },
  },
  created() {
    this.showProduct()
    this.getMainCategories()
  },
  methods: {
    saveDetailsArrayAttribute(attribute) {
      let index = this.editedItem.product_array_attributes.indexOf(attribute)
      this.product_array_attribute_id = attribute.id
      let formData = []

      formData = new FormData()

      formData.append('image', this.photo)

      formData.append('product_id', this.product_id)
      formData.append('quantity', attribute.quantity)
      if (attribute.original_price == undefined) {
        formData.append('original_price', 0)
      } else {
        formData.append('original_price', attribute.original_price)
      }
      if (attribute.price_discount_ends == undefined) {
        formData.append('price_discount_ends', 0)
      } else {
        formData.append('price_discount_ends', attribute.price_discount_ends)
      }
      formData.append('sku', attribute.sku)
      formData.append('barcode', attribute.barcode)
      formData.append('weight', attribute.weight)
      this.$http
        .post(`admin/product-attributes/update-details-array-attribute/${this.product_array_attribute_id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'X-Requested-With': 'XMLHttpRequest',
          },
        })
        .then((res) => {

          Object.assign(this.editedItem.product_array_attributes[index], res.data.data)
          this.subPDialog = false
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
          this.photo = null
          this.img = null
        })
        .catch(error => {

          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })

    },
    getSimilar(item) {
      this.similarsIds.push(item.value)
      this.productsSimilar.push(item)
      if (!this.similarsIds.includes(item.value)) {
        this.productsSimilar.push(item)
        this.similars = item.name
      }
    },

    //open choose image
    chooseImg() {
      let El = this.$refs.chooseImg
      El.click()
      this.i++
      //listen to changes in photos
      El.addEventListener('input', async e => {
        let file = e.target.files[0]
        //  this.base_imgs.push(this.editedItem.product_images)
        this.base_imgs.push(file)
        return (El.value = '')
      })
    },

    showVariantsMeth() {
      this.showVariants = true
    },
    disapearVariantsMeth() {
      this.showVariants = false
    },
    showSimilarsMeth() {
      this.showSimilars = true
    },
    disapearSimilarsMeth() {
      this.showSimilars = false
    },

    assignProducts() {
      let main_product = {
        name: 'hello',
      }
      let products = []
    },

    callMessage(message) {
      this.snackbar = true
      this.text = message
    },

    trim(value, size) {
      let new_url = value.slice(0, 15) + 'thumbnail/' + value.slice(15)
      let index = new_url.length - 4
      let url = new_url.slice(0, index) + size + new_url.slice(index)
      return url.substr(0, url.length)
    },

    trimAttribute(value, size) {
      if (value !== null || value !== undefined) {
        let new_url = value.slice(0, 15) + 'thumbnail/' + value.slice(16)
        return new_url
        // let index = new_url.length - 4
        // let url = new_url.slice(0, index) + size + new_url.slice(index)
        // return url.substr(0, url.length)
      }
    },
    trimAttributeSubItem(value, size) {
      if (value !== null || value !== undefined) {
        // return value
        let new_url = value.slice(0, 32) + '/thumbnail/' + value.slice(33) 
       return new_url 

        //  let index = new_url.length - 4
        //  let url = new_url.slice(0, index) + size + new_url.slice(index)
        //  return url.substr(0, url.length)
      }
    },
    trimSeo(value, size) {
      let new_url = value.slice(0, 15) + 'thumbnail/' + value.slice(15)
      let index = new_url.length - 4
      let url = new_url.slice(0, index) + size + new_url.slice(index)
      return url.substr(0, url.length)
    },
    editAttr(item, index) {
      this.editedSubItem = item
      this.subPDialog = true
    },
    showTrash() {
      this.$router.push('/trash-products-managment')
    },

    edit(index) {
      this.product_attribute_id = index
      this.editSave = true
    },
    editArrayAttribute(id) {
      this.product_array_attribute_id = id
    },
    addOption() {
      this.main_attrs.push({})
      this.sub_product.attr.push({
        name: null,
        value: null,
      })

      this.add = true
    },
    deleteOption(index) {
      this.deleteO = true
      this.main_attrs.splice(index, 1)

    },
    deleteArrayAttribute(id, i) {
      this.$http
        .get(`admin/product-attributes/delete-many-attributes/${id}`)
        .then(res => {
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message

          this.editedItem.product_array_attributes.splice(i, 1)
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    saveSubProduct() {
      this.$http
        .post('admin/product-attributes/store-many-attributes', {
          product_id: this.product_id,
          attributes: this.x.attr,
        })
        .then(res => {
          this.editedItem.product_array_attributes.push(res.data.data)
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    
    saveAttributes(item) {
      this.product_attribute_id = item.id
      if (item.id) {
        this.$http
          .post(`admin/product-attributes/update/${this.product_attribute_id}`, {
            product_id: this.product_id,
            option: item.name,
            attributes: item.attrs,
          })

          .then(res => {
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          })
      } else {
        this.$http
          .post('admin/product-attributes/store', {
            product_id: this.product_id,
            option: item.name,
            attributes: item.attrs,
          })
          .then(res => {
            // this.main_attrs.push({ id: res.data.data.id, name: res.data.data.option, attrs: JSON.parse(res.data.data.attributes) })
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

    showDetails(item) {
      this.editedSubItem = item
      this.showDetailsDialog = true
    },
    showProduct() {
      this.product_id = this.$route.params.id
      this.$http.get(`admin/similar-products/similars-product/${this.product_id}`).then(res => {
        res.data.data.forEach(item => {
          this.$http.get(`admin/similar-products/show/${item.similar}`).then(res => {
            this.productsSimilar.push(res.data.data)
          })
        })
      })

      this.$http
        .get(`admin/products/show/${this.product_id}`)
        .then(res => {
          if (res.data.data.category !== null) {
            if (this.editedItem.category) {
              this.editedItem.category.main_category = res.data.data.category.main_category
            }
          }
          this.editedItem = res.data.data
          this.imgs = this.editedItem.product_images
          this.editedItem.product_attributes.forEach(el => {
            this.main_attrs.push({ id: el.id, name: el.option, attrs: JSON.parse(el.attributes) })
            setTimeout(() => {
              this.sub_product.attr.push({
                name: el.option,
                value: null,
              })
            }, 500)
          })
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    saveStorageDetail() {
      this.$http
        .post(`admin/storage-details/update/${this.editedItem.storage_detail.id}`, {
          sku: this.editedItem.storage_detail.sku,
          barcode: this.editedItem.storage_detail.barcode,
          weight: this.editedItem.storage_detail.weight,
          product_id: this.product_id,
        })

        .then(res => {
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
    deleteSimilar(similar) {
      if (similar.value) {
        let similarInteger = Number.isInteger(similar)
        this.$http
          .get(`admin/similar-products/destroy/${this.product_id}/${similar.value}`)
          .then(res => {
            const index = this.productsSimilar.indexOf(similar)
            this.productsSimilar.splice(index, 1)
            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            if (error && error.response) {
              this.$store.state.snackbar = true
              this.$store.state.text = error.response.data.message
            }
          })
      } else if (similar.id) {
        let similarInteger = Number.isInteger(similar)
        this.$http
          .get(`admin/similar-products/destroy/${this.product_id}/${similar.id}`)
          .then(res => {
            const index = this.productsSimilar.indexOf(similar)
            this.productsSimilar.splice(index, 1)
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
    saveSeoProduct() {
      let formData = []

      formData = new FormData()

      this.photos_seo.forEach(file => {
        formData.append('seo_images[]', file)
      })
      formData.append('slug', this.editedItem.seo.slug)
      formData.append('title', this.editedItem.seo.title)
      formData.append('meta_description', this.editedItem.seo.meta_description)
      formData.append('product_id', this.product_id)
      this.$http
        .post(`admin/seos/update/${this.editedItem.seo.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'X-Requested-With': 'XMLHttpRequest',
          },
        })

        .then(res => {
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    async saveSimilarProduct() {
      let dum_products_sim = []
      if (this.productsSimilar) {
        this.productsSimilar.forEach(product => {
          if (product.value) {
            dum_products_sim.push(product.value)
          } else {
            dum_products_sim.push(product.id)
          }
        })
      }

      // this.productsSimilar = dum_products_sim
      this.$http
        .post(`admin/similar-products/update/${this.product_id}`, {
          // similar: this.productsSimilar,
          similar: this.similarsIds,
        })

        .then(res => {
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    getMainCategories() {
      this.$http
        .get('admin/categories/get-main-categories')
        .then(res => {
          res.data.data.forEach(category => {
            this.maincategorieso.push({
              text: category.name,
              value: category.id,
            })
          })
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    save() {
      let formData = []

      formData = new FormData()

      formData.append('status', this.editedItem.status)

      formData.append('is_offers', this.editedItem.is_offers)

      formData.append('name', this.editedItem.name)
      formData.append('description', this.editedItem.description)
      this.base_imgs.forEach(file => {
        formData.append('product_images[]', file)
      })
      formData.append('quantity', this.editedItem.quantity)
      if (this.editedItem.original_price == undefined) {
        formData.append('original_price', 0)
      } else {
        formData.append('original_price', this.editedItem.original_price)
      }
      if (this.editedItem.price_discount_ends == undefined) {
        formData.append('price_discount_ends', 0)
      } else {
        formData.append('price_discount_ends', this.editedItem.price_discount_ends)
      }
      if (this.editedItem.category && this.editedItem.category.id == undefined) {
        this.editedItem.category.id = this.editedItem.category_id
      }

      if (this.editedItem.sub_category && this.editedItem.sub_category.id == undefined) {
        formData.append('category_id', this.editedItem.category.id)

        formData.append('sub_category_id', this.editedItem.sub_category)
      } else {
        if (this.editedItem.sub_category && this.editedItem.sub_category.id !== null) {
          formData.append('sub_category_id', this.editedItem.sub_category.id)
          formData.append('category_id', this.editedItem.category.id)
        } else {
          formData.append('category_id', this.editedItem.category.id)
        }
      }

      this.$http
        .post(`admin/products/update/${this.product_id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'X-Requested-With': 'XMLHttpRequest',
          },
        })

        .then(res => {
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },

    createItem() {
      this.dialog = true
    },
    deleteImage(img) {
      this.$http
        .get(`admin/products/delete-image/${img.id}`)

        .then(res => {
          const index = this.imgs.indexOf(img)
          this.imgs.splice(index, 1)
          this.base_imgs.splice(index, 1)
          this.$store.state.snackbar = true
          this.$store.state.text = res.data.message
        })
        .catch(error => {
          this.$store.state.snackbar = true
          this.$store.state.text = error.response.data.message
        })
    },
    deleteImageNotStore(index) {
      //this.deleteO = true
      this.imgs.splice(index, 1)
      this.base_imgs.splice(index, 1)
    },
    deleteItem(item, i) {
      const index = this.main_attrs.indexOf(item)

      confirm('هل أنت متأكد من حذف هذا العنصر؟') &&
        this.$http
          .get(`admin/product-attributes/destroy/${item}`)

          .then(res => {
            
            this.main_attrs.splice(i, 1)

            this.$store.state.snackbar = true
            this.$store.state.text = res.data.message
          })
          .catch(error => {
            this.$store.state.text = error.response.data.message
            this.$store.state.snackbar = true
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
  mounted() {
    this.assignProducts()
    setTimeout(() => {}, 5000)
  },
}
</script>
