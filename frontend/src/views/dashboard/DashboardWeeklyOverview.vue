<template>
  <v-card class="pl-15 pr-15">
    <v-card-title class="align-start">
      <span>الملخص الشهري</span>

      <v-spacer></v-spacer>
    </v-card-title>

    <v-card-text>
      <!-- Chart -->
      <vue-apex-charts :options="chartOptions" :series="chartData" height="210"></vue-apex-charts>
    </v-card-text>
  </v-card>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
// eslint-disable-next-line object-curly-newline
import { mdiDotsVertical, mdiTrendingUp, mdiCurrencyUsd } from '@mdi/js'
import { getCurrentInstance } from '@vue/composition-api'

export default {
  components: {
    VueApexCharts,
  },

  data() {
    return {
      customChartColor: this.$vuetify.theme.isDark ? '#3b3559' : '#f5f5f5',
      arr: [],
    }
  },
  computed: {
    chartOptions() {
      return {
        colors: [this.$vuetify.theme.currentTheme.primary],
        chart: {
          type: 'bar',
          toolbar: {
            show: false,
          },
          offsetX: -15,
        },
        plotOptions: {
          bar: {
            columnWidth: '40%',
            distributed: true,
            borderRadius: 8,
            startingShape: 'rounded',
            endingShape: 'rounded',
          },
        },
        dataLabels: {
          enabled: false,
        },
        legend: {
          show: false,
        },
        xaxis: {
          categories: ['jan', 'feb', 'mar', 'apr', 'may', 'june', 'july', 'aug', 'sep', 'oct', 'nov', 'dec'],
          axisBorder: {
            show: false,
          },
          axisTicks: {
            show: false,
          },
          tickPlacement: 'on',
          labels: {
            show: false,
            style: {
              fontSize: '12px',
            },
          },
        },
        yaxis: {
          show: true,
          tickAmount: 4,
          labels: {
            offsetY: 3,
            formatter: value => `${value}`,
          },
        },
        stroke: {
          width: [2, 2],
        },
        grid: {
          strokeDashArray: 12,
          padding: {
            right: 0,
          },
        },
      }
    },
    chartData() {
      return [
        {
          data: this.arr,
        },
      ]
    },
  },
  created() {
    this.getOrdersGroupMonth()
  },
  methods: {
    getOrdersGroupMonth() {
      this.$http
        .get('admin/orders/get-orders-group-month')
        .then(res => {
          this.arr = res.data.data

          // this.arr.forEach(item => {
          //   setTimeout(() => {
          //     this.chartData[0].data.push(item)
          //   }, 1000)
          // })
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
