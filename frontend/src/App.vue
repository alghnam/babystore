<template>
  <component :is="resolveLayout">
    <v-snackbar v-model="$store.state.snackbar" :color="color">
      {{ $store.state.text }}

      <template v-slot:action="{ attrs }">
        <v-btn color="pink" text v-bind="attrs" @click="$store.state.snackbar = false"> اغلاق </v-btn>
      </template>
    </v-snackbar>
    <router-view></router-view>
  </component>
  <!--<v-app>
    <AppBarUserMenu />
    <v-main>
      
    </v-main>
  </v-app>-->
</template>

<script>
import { computed } from '@vue/composition-api'
import { useRouter } from '@/utils'
import LayoutBlank from '@/layouts/Blank.vue'
import LayoutContent from '@/layouts/Content.vue'
import UpgradeToPro from './components/UpgradeToPro.vue'
import AppBarUserMenu from './layouts/components/AppBarUserMenu.vue'

import StarRating from 'vue-star-rating'
// import 'quill/dist/quil.snow.css'
import { quillEditor } from 'vue-quill-editor'
export default {
  components: {
    quillEditor,
    LayoutBlank,
    LayoutContent,
    UpgradeToPro,
    AppBarUserMenu,
    StarRating,
  },
  data() {
    return {
      name: 'app',
      content: '<h2>Example</h2>',
      editorOption: {},
    }
  },
  setup() {
    const { route } = useRouter()

    const resolveLayout = computed(() => {
      // Handles initial route
      if (route.value.name === null) return null

      if (route.value.meta.layout === 'blank') return 'layout-blank'

      return 'layout-content'
    })

    return {
      resolveLayout,
    }
  },
  watch: {
    $route: {
      handler: function (val) {
        localStorage.setItem('last_page', val.path)
      },
    },
  },
}
</script>
