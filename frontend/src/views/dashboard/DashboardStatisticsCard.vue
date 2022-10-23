<template>
  <v-card>
    <v-card-title class="align-start">
      <span class="font-weight-semibold" style="line-height: 1.5">
        <v-icon>mdi-cards</v-icon>
        كروت الإحصائية</span
      >
      <v-spacer></v-spacer>
    </v-card-title>

    <v-card-text>
      <v-row>
        <v-col v-for="data in countsData" :key="data.title" cols="6" md="3" class="d-flex align-center">
          <div class="d-flex">
            <v-avatar
              size="44"
              :color="
                data.title == 'Orders'
                  ? 'primary'
                  : data.title == 'Users'
                  ? 'secondary'
                  : data.title == 'Products'
                  ? 'orange darken-1'
                  : 'success'
              "
              rounded
              class="elevation-1"
            >
              <v-icon dark color="white" size="30">
                {{
                  data.title == 'Orders'
                    ? 'mdi-cart'
                    : data.title == 'Users'
                    ? 'mdi-account-group'
                    : data.title == 'Products'
                    ? 'mdi-dolly'
                    : 'mdi-package-variant'
                }}
              </v-icon>
            </v-avatar>
            <div class="ms-3">
              <p class="text-xs mb-0">
                {{ data.title }}
              </p>
              <h3 class="text-xl font-weight-semibold">
                {{ data.total ? data.total : 0 }}
              </h3>
            </div>
          </div>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import { mdiAccountOutline, mdiCurrencyUsd, mdiTrendingUp, mdiDotsVertical, mdiLabelOutline } from '@mdi/js'

export default {
  data() {
    return {
      countsData: [],
    }
  },
  created() {
    this.getCountsData()
  },
  methods: {
    getCountsData() {
      this.$http
        .get('admin/counts-all-data')
        .then(res => {
          res.data.data.forEach(countData => {
            this.countsData.push({
              title: countData.title,
              total: countData.total,
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
}
</script>
