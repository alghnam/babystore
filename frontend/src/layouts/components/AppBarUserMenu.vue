<template>
  <div>
    <v-menu offset-y left nudge-bottom="14" min-width="230" content-class="user-profile-menu-content">
      <template v-slot:activator="{ on, attrs }">
        <v-badge bottom color="success" overlap offset-x="12" offset-y="12" class="ms-4" dot>
          <v-avatar size="40px" v-bind="attrs" v-on="on">
            <v-img :src="require('@/assets/images/avatars/1.png')"></v-img>
          </v-avatar>
        </v-badge>
      </template>

      <v-list>
        <div class="pb-3 pt-2">
          <v-badge bottom color="success" overlap offset-x="12" offset-y="12" class="ms-4" dot>
            <v-avatar size="40px">
              <v-img :src="require('@/assets/images/avatars/1.png')"></v-img>
            </v-avatar>
          </v-badge>
          <div class="d-inline-flex flex-column justify-center ms-3" style="vertical-align: middle">
            <span class="text--primary font-weight-semibold mb-n1">
              <button @click="goToProfile()">{{ this.name }}</button>
            </span>
          </div>
        </div>

        <v-divider class="my-2"></v-divider>
        <!-- logout -->
        <v-list-item link>
          <v-list-item-icon class="me-2">
            <v-icon size="22">
              {{ icons.mdiLogoutVariant }}
            </v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title @click="logout()">Logout</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script>
import {
  mdiAccountOutline,
  mdiEmailOutline,
  mdiCheckboxMarkedOutline,
  mdiChatOutline,
  mdiCogOutline,
  mdiCurrencyUsd,
  mdiHelpCircleOutline,
  mdiLogoutVariant,
} from '@mdi/js'

export default {
  setup() {
    return {
      data() {
        return {
          name: null,
        }
      },
      icons: {
        mdiAccountOutline,
        mdiEmailOutline,
        mdiCheckboxMarkedOutline,
        mdiChatOutline,
        mdiCogOutline,
        mdiCurrencyUsd,
        mdiHelpCircleOutline,
        mdiLogoutVariant,
      },
    }
  },
  created() {
    this.name = JSON.parse(localStorage.getItem('user')).first_name
  },
  methods: {
    logout() {
      this.$http.get('logout').then(() => {
        localStorage.removeItem('token')
        this.$router.push('/login')
      })
    },
    goToProfile() {
      this.$router.push('/profile')
    },
  },
}
</script>

<style lang="scss">
.user-profile-menu-content {
  .v-list-item {
    min-height: 2.5rem !important;
  }
}
</style>
