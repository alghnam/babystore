<template>
  <v-navigation-drawer
    :value="isDrawerOpen"
    app
    floating
    width="fit-content"
    class="app-navigation-menu"
    :right="$vuetify.rtl"
    @input="val => $emit('update:is-drawer-open', val)"
  >
    <!-- Navigation Header -->
    <div class="vertical-nav-header d-flex items-center ps-6 pe-5 pt-5 pb-2">
      <router-link to="/" class="d-flex align-center text-decoration-none">
        <v-img
          :src="require('@/assets/images/logos/logo.svg')"
          max-height="30px"
          max-width="30px"
          alt="logo"
          contain
          eager
          class="app-logo me-3"
        ></v-img>
        <v-slide-x-transition>
          <h2 class="app-title text--primary">Baby Store</h2>
        </v-slide-x-transition>
      </router-link>
    </div>

    <!-- Navigation Items -->
    <v-list expand shaped class="vertical-nav-menu-items pr-5">
      <nav-menu-link title="لوحة التحكم" to="/dashboard" :icon="icons.mdiHomeOutline"></nav-menu-link>

      <nav-menu-group title=" المستخدمين " icon="mdi-account-group">
        <nav-menu-group title="المستخدمين " icon="mdi-account-group">
          <nav-menu-link title="ادارة المستخدمين" to="/users-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-users-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="الادوار" icon="mdi-lock">
          <nav-menu-link title="ادارة الادوار" to="/roles-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-roles-management"></nav-menu-link>
        </nav-menu-group>
      </nav-menu-group>
      <nav-menu-group title=" المناطق الجغرافية " icon="mdi-flag">
          <nav-menu-group title="الدول " icon="mdi-flag">
            <nav-menu-link title="ادارة الدول" to="/countries-management"></nav-menu-link>
            <nav-menu-link title="سلة المحذوفات " to="/trash-countries-management"></nav-menu-link>
          </nav-menu-group>
          <nav-menu-group title="المدن " icon="mdi-map-marker-circle">
            <nav-menu-link title="ادارة المدن" to="/cities-management"></nav-menu-link>
            <nav-menu-link title="سلة المحذوفات " to="/trash-cities-management"></nav-menu-link>
          </nav-menu-group>
          <nav-menu-group title="المناطق " icon="mdi-map-marker-circle">
            <nav-menu-link title="ادارة المناطق" to="/towns-management"></nav-menu-link>
            <nav-menu-link title="سلة المحذوفات " to="/trash-towns-management"></nav-menu-link>
          </nav-menu-group>
        </nav-menu-group>
      </nav-menu-group>

      <nav-menu-group title="محتوى النظام" :icon="icons.mdiFileOutline">
        <nav-menu-group title="البانرات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة البانرات" to="/banners-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-banners-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title=" الفئات" :icon="icons.mdiFileOutline">
          <nav-menu-group title="الفئات الرئيسية" :icon="icons.mdiFileOutline">
            <nav-menu-link title="ادارة الفئات الرئيسية" to="/main-categories-management"></nav-menu-link>
            <nav-menu-link title="سلة المحذوفات " to="/trash-main-categories-management"></nav-menu-link>
          </nav-menu-group>
          <nav-menu-group title="الفئات الفرعية" :icon="icons.mdiFileOutline">
            <nav-menu-link title="ادارة الفئات الفرعية" to="/sub-categories-management"></nav-menu-link>
            <nav-menu-link title="سلة المحذوفات " to="/trash-sub-categories-management"></nav-menu-link>
          </nav-menu-group>
          <nav-menu-group title="الفئات الفرعية الثانية" :icon="icons.mdiFileOutline">
            <nav-menu-link title="ادارة الفئات  الفرعية الثانية" to="/second-sub-categories-management"></nav-menu-link>
            <nav-menu-link title="سلة المحذوفات " to="/trash-second-sub-categories-management"></nav-menu-link>
          </nav-menu-group>
        </nav-menu-group>
        <nav-menu-group title="المنتجات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة المنتجات" to="/products-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-products-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="الكوبونات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة الكوبونات" to="/coupons-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-coupons-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="وسائل الدفع" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة وسائل الدفع" to="/payments-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-payments-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="المبيعات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة المبيعات" to="/upsells-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-upsells-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="الاوردرات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة الاوردرات" to="/orders-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-orders-management"></nav-menu-link>
        </nav-menu-group>
      </nav-menu-group>

      <nav-menu-group title=" الاشعارات والرسائل" :icon="icons.mdiFileOutline">
        <nav-menu-group title=" الاشعارات " :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة الاشعارات" to="/push-notifications-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="الرسائل المرسلة" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة الرسائل المرسلة" to="/chats-sended-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-chats-sended-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="الرسائل المستقبلة" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة الرسائل المستقبلة" to="/chats-recieved-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-chats-recieved-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="الرسائل " :icon="icons.mdiFileOutline">
          <nav-menu-link title="رسالة جديدة" to="/new-message"></nav-menu-link>
        </nav-menu-group>
      </nav-menu-group>

      <nav-menu-group title="حركات المستخدمين" :icon="icons.mdiFileOutline">
        <nav-menu-group title="قوائم البحث" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة قوائم البحث" to="/searches-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-searches-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="المشاهدات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة المشاهدات" to="/views-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-views-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title=" الحركات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة  الحركات" to="/movements-management"></nav-menu-link>
          <!-- <nav-menu-link title="سلة المحذوفات " to="/trash-movements-management"></nav-menu-link> -->
        </nav-menu-group>
        <nav-menu-group title="المحافظ" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة المحافظ" to="/wallets-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="السلات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة السلات" to="/carts-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-carts-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="الاراء" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة الاراء" to="/reviews-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-reviews-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="المفضلات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة المفضلات" to="/favorites-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-favorites-management"></nav-menu-link>
        </nav-menu-group>
      </nav-menu-group>

      <nav-menu-group title=" النظام" :icon="icons.mdiFileOutline">
        <nav-menu-group title="قوانين النظام" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة قوانين النظام" to="/rules-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-rules-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="الاسئلة" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة الاسئلة" to="/questions-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-questions-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="فئات الاسئلة" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة فئات الاسئلة" to="/question-categories-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-question-categories-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="الخدمات" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة الخدمات" to="/services-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-services-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="محفظة النظام" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة محفظة النظام" to="/wallet-system-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="تقييمات النظام" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة تقييمات النظام" to="/system-reviews-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-system-reviews-management"></nav-menu-link>
        </nav-menu-group>
        <nav-menu-group title="انواع تقييمات النظام" :icon="icons.mdiFileOutline">
          <nav-menu-link title="ادارة انواع تقييمات النظام" to="/system-review-types-management"></nav-menu-link>
          <nav-menu-link title="سلة المحذوفات " to="/trash-system-review-types-management"></nav-menu-link>
        </nav-menu-group>
      
      </nav-menu-group>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import {
  mdiHomeOutline,
  mdiAlphaTBoxOutline,
  mdiEyeOutline,
  mdiCreditCardOutline,
  mdiTable,
  mdiFileOutline,
  mdiFormSelect,
  mdiAccountCogOutline,
} from '@mdi/js'
import NavMenuSectionTitle from './components/NavMenuSectionTitle.vue'
import NavMenuGroup from './components/NavMenuGroup.vue'
import NavMenuLink from './components/NavMenuLink.vue'

export default {
  components: {
    NavMenuSectionTitle,
    NavMenuGroup,
    NavMenuLink,
  },
  props: {
    isDrawerOpen: {
      type: Boolean,
      default: null,
    },
  },
  setup() {
    return {
      icons: {
        mdiHomeOutline,
        mdiAlphaTBoxOutline,
        mdiEyeOutline,
        mdiCreditCardOutline,
        mdiTable,
        mdiFileOutline,
        mdiFormSelect,
        mdiAccountCogOutline,
      },
    }
  },
}
</script>

<style lang="scss" scoped>
.app-title {
  font-size: 1.25rem;
  font-weight: 700;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: 0.3px;
}

// ? Adjust this `translateX` value to keep logo in center when vertical nav menu is collapsed (Value depends on your logo)
.app-logo {
  transition: all 0.18s ease-in-out;
  .v-navigation-drawer--mini-variant & {
    transform: translateX(-4px);
  }
}

@include theme(app-navigation-menu) using ($material) {
  background-color: map-deep-get($material, 'background');
}

.app-navigation-menu {
  .v-list-item {
    &.vertical-nav-menu-link {
      ::v-deep .v-list-item__icon {
        .v-icon {
          transition: none !important;
        }
      }
    }
  }
}

// You can remove below style
// Upgrade Banner
.app-navigation-menu {
  .upgrade-banner {
    position: absolute;
    bottom: 13px;
    left: 50%;
    transform: translateX(-50%);
  }
}
</style>
