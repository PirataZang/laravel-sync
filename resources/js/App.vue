<template>
    <router-view v-slot="{ Component }">
        <component :is="layoutComponent">
            <component :is="Component" />
        </component>
    </router-view>
    <GlobalLoading />
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import DefaultLayout from './layouts/Default.vue'
import CleanLayout from './layouts/Clean.vue'
import GlobalLoading from './components/utils/GlobalLoading.vue'

const route = useRoute()

const layouts = {
    default: DefaultLayout,
    clean: CleanLayout,
}

const layoutComponent = computed(() => {
    const key = route.meta?.layout || 'default'
    return layouts[key] || DefaultLayout
})
</script>
