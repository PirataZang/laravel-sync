<template>
    <div class="default-layout">
        <div class="layout-content">
            <header class="layout-header">
                <i class="icon fas fa-bars" @click="toggleMenu"></i>
                <div class="user-content">
                    <span class="name">{{ user?.name }}</span>
                    <span>{{ user?.email }}</span>
                </div>
            </header>
            <div class="content">
                <Menu
                    :collapsed="!expanded && !isMobile"
                    :overlay="isMobile"
                    :showMenu="isMobile ? showMenuMobile : true"
                    @close="showMenuMobile = false"
                    @update:showMenu="val => showMenuMobile = val"
                />
                <div class="page-content">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Menu from '../components/Menu.vue'

// desktop: controla se o menu está expandido (texto visível)
const expanded = ref(false)
// mobile: controla se o menu está visível como overlay
const showMenuMobile = ref(false)

// detecta se estamos em tela pequena
const isMobile = ref(window.innerWidth <= 768)

function handleResize() {
    isMobile.value = window.innerWidth <= 768
    if (!isMobile.value) {
        // garante que overlay mobile esteja fechado ao voltar para desktop
        showMenuMobile.value = false
    }
}

onMounted(() => {
    window.addEventListener('resize', handleResize)
})
onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize)
})

function toggleMenu() {
    if (isMobile.value) {
        showMenuMobile.value = !showMenuMobile.value
    } else {
        expanded.value = !expanded.value
    }
}

const user = JSON.parse(localStorage.getItem('user'))
</script>

<style scoped lang="scss">
.default-layout {
    display: flex;
    min-height: 100vh;

    @media (max-width: 768px) {
        flex-direction: column;
    }

    .layout-content {
        display: flex;
        flex-direction: column;
        flex: 1;
        top: 0;

        .icon {
            cursor: pointer;
            width: 60px;
            font-size: 22px;
        }

        .layout-header {
            display: flex;
            gap: 10px;
            align-items: center;
            height: 50px;
            width: 100%;
            z-index: 99;
            overflow: hidden;
            color: white;
            background-color: #111827;
            position: fixed;

            .user-content {
                display: flex;
                position: absolute;
                right: 30px;
                flex-direction: column;
                font-size: 12px;

                .name {
                    font-size: 14px;
                    font-weight: 600;
                }
            }
        }

        .content {
            top: 50px;
            height: 100%;
            display: flex;
            position: relative;
            padding-right: 20px;
            background: #f9fafb;
            gap: 20px;

            @media (max-width: 768px) {
                padding: 10px;
                gap: 0px;
            }

            .page-content {
                flex: 1;
            }
        }
    }
}
</style>
