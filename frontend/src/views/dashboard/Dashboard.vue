<template>
  <v-row>
    <v-col cols="12" md="12">
      <dashboard-statistics-card></dashboard-statistics-card>
    </v-col>

    <v-col cols="12" sm="12" md="8" class="pr-5 pl-5">
      <dashboard-weekly-overview></dashboard-weekly-overview>
    </v-col>

    <v-col cols="12" md="4">
      <v-row class="match-height">
        <v-col cols="12" sm="6">
          <v-card class="mx-auto">
            <h1 style="padding-top: 64px; padding-right: 50px">{{ currency }}</h1>
          </v-card>
        </v-col>
        <v-col cols="12" sm="6">
          <statistics-card-vertical
            :change="totalSales.change"
            :color="totalSales.color"
            :icon="totalSales.icon"
            :statistics="totalSales.statistics"
            :stat-title="totalSales.statTitle"
            :subtitle="totalSales.subtitle"
          ></statistics-card-vertical>
        </v-col>
        <v-col cols="12" sm="6">
          <statistics-card-vertical
            :change="totalViews.change"
            :color="totalViews.color"
            :icon="totalViews.icon"
            :statistics="totalViews.statistics"
            :stat-title="totalViews.statTitle"
            :subtitle="totalViews.subtitle"
          ></statistics-card-vertical>
        </v-col>

        <v-col cols="12" sm="6">
          <statistics-card-vertical
            :change="totalReviews.change"
            :color="totalReviews.color"
            :icon="totalReviews.icon"
            :statistics="totalReviews.statistics"
            :stat-title="totalReviews.statTitle"
            :subtitle="totalReviews.subtitle"
          ></statistics-card-vertical>
        </v-col>
      </v-row>
    </v-col>

    <v-col cols="12">
      <dashboard-datatable></dashboard-datatable>
    </v-col>
  </v-row>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import { mdiPoll, mdiLabelVariantOutline, mdiCurrencyUsd, mdiHelpCircleOutline } from '@mdi/js'
import StatisticsCardVertical from '@/components/statistics-card/StatisticsCardVertical.vue'

// demos
import DashboardStatisticsCard from './DashboardStatisticsCard.vue'
import DashboardCardTotalEarning from './DashboardCardTotalEarning.vue'
import DashboardCardDepositAndWithdraw from './DashboardCardDepositAndWithdraw.vue'
import DashboardCardSalesByCountries from './DashboardCardSalesByCountries.vue'
import DashboardWeeklyOverview from './DashboardWeeklyOverview.vue'
import DashboardDatatable from './DashboardDatatable.vue'

export default {
  components: {
    StatisticsCardVertical,
    DashboardStatisticsCard,
    DashboardCardTotalEarning,
    DashboardCardDepositAndWithdraw,
    DashboardCardSalesByCountries,
    DashboardWeeklyOverview,
    DashboardDatatable,
  },

  data() {
    return {
      currency: null,
      totalSales: {
        statTitle: 'الربح الكلي',
        icon: 'mdi-chart-line',
        color: 'primary',
        subtitle: '',
        statistics: 0,
        change: '',
      },
      totalViews: {
        statTitle: 'المشاهدات',
        icon: 'mdi-eye',
        color: 'success',
        subtitle: '',
        statistics: 0,
        change: '',
      },
      totalReviews: {
        statTitle: 'إجمالي التقييمات',
        icon: 'mdi-account-edit',
        color: 'warning',
        subtitle: '',
        statistics: 0,
        change: '',
      },
    }
  },
  created() {
    this.gettotalProfit()
    this.getViews()
    this.getReviews()
    this.showCurrency()
  },
  methods: {
    showCurrency() {
      this.$http
        .get('admin/show-currency')
        .then(res => {
          this.currency = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    gettotalProfit() {
      this.$http
        .get('admin/orders/prices-sent-delivered-orders')
        .then(res => {
          this.totalSales.statistics = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    getViews() {
      this.$http
        .get('admin/views/count-data')
        .then(res => {
          this.totalViews.statistics = res.data.data
        })
        .catch(error => {
          if (error && error.response) {
            this.$store.state.snackbar = true
            this.$store.state.text = error.response.data.message
          }
        })
    },
    getReviews() {
      this.$http
        .get('admin/reviews/count-data')
        .then(res => {
          this.totalReviews.statistics = res.data.data
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
