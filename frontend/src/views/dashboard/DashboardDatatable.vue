<template>
  <v-card>
    <v-data-table
      :headers="headers"
      :items="orders"
      item-key="full_name"
      class="table-rounded"
      hide-default-footer
      disable-sort
    >
      <!-- name-->
      <template #[`item.order_num`]="{ item }">
        <div class="d-flex flex-column">
          <span class="d-block font-weight-semibold text--primary text-truncate">{{ item.order_num }}</span>
          <small>{{ item.user ? item.user.first_name : null }}</small>
        </div>
      </template>
      <!-- <template #[`item.price`]="{ item }">
        {{ `${item.price}` }}
      </template> -->
      <template #[`item.coupon`]="{ item }">
        {{ item.coupon ? item.coupon : null }}
      </template>
      <!-- status -->
      <template #[`item.status`]="{ item }">
        <v-chip small :color="statusColor[item.status]" class="font-weight-medium">
          {{ status[item.status] }}
        </v-chip>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import { mdiSquareEditOutline, mdiDotsVertical } from '@mdi/js'
import data from './datatable-data'

export default {
  // setup() {
  //   const statusColor = {
  //     /* eslint-disable key-spacing */
  //     Current: 'primary',
  //     Professional: 'success',
  //     Rejected: 'error',
  //     Resigned: 'warning',
  //     Applied: 'info',
  //     /* eslint-enable key-spacing */
  //   }

  //   return {
  //     headers: [
  //       // { text: 'NAME', value: 'full_name' },
  //       // { text: 'EMAIL', value: 'email' },
  //       // { text: 'DATE', value: 'start_date' },
  //       // { text: 'SALARY', value: 'salary' },
  //       // { text: 'AGE', value: 'age' },
  //       // { text: 'STATUS', value: 'status' },
  //       { text: 'Order Num', value: 'order_num' },
  //       { text: 'Price', value: 'price' },
  //       { text: 'Products Count', value: 'products_count' },
  //       { text: 'Payment Method', value: 'payment.name' },
  //       { text: 'Service', value: 'service.period' },
  //       { text: 'Coupon', value: 'coupon' },
  //       { text: 'Address', value: 'address.first_name' },
  //       { text: 'STATUS', value: 'status' },
  //     ],
  //     // usreList: data,
  //     orders: data,
  //     // status: {
  //     //   1: 'Current',
  //     //   2: 'Professional',
  //     //   3: 'Rejected',
  //     //   4: 'Resigned',
  //     //   5: 'Applied',
  //     // },
  //     status: {
  //       1: 'Being processed',
  //       2: 'Shipping',
  //       3: 'sent delivered handed',
  //       0: 'hanging',
  //     },
  //     statusColor,

  //     // icons
  //     icons: {
  //       mdiSquareEditOutline,
  //       mdiDotsVertical,
  //     },
  //   }
  // },
  data() {
    return {
      headers: [
        { text: 'Order Num', value: 'order_num' },
        { text: 'Price', value: 'price' },
        { text: 'Products Count', value: 'products_count' },
        { text: 'Payment Method', value: 'payment.name' },
        { text: 'Service', value: 'service.period' },
        { text: 'Coupon', value: 'coupon' },
        { text: 'Country', value: 'address.country.name' },
        { text: 'City', value: 'address.city.name' },
        { text: 'Town', value: 'address.town.name' },
        { text: 'STATUS', value: 'status' },
      ],
      orders: [],
      status: {
        1: 'Being processed',
        2: 'Shipping',
        3: 'sent delivered handed',
        0: 'hanging',
        '-1': 'canceled',
      },
      statusColor: {
        /* eslint-disable key-spacing */
        1: 'primary',
        2: 'success',
        '-1': 'error',
        4: 'warning',
        0: 'info',
        /* eslint-enable key-spacing */
      },
    }
  },
  created() {
    this.getLatestOrders()
  },
  methods: {
    getLatestOrders() {
      this.$http
        .get('admin/orders/latest')
        .then(res => {
          this.orders = res.data.data
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
