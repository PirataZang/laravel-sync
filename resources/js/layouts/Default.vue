<template>
    <div class="default-layout">
        <Menu :showMenu="expanded" @close="expanded = false" @update:showMenu="expanded = $event" />
        <div class="layout-content">
            <header class="layout-header">
                <i class="icon fas fa-bars" @click="expanded = true"></i>
                <div class="user-content">
                    <span class="name">{{ user?.name }}</span>
                    <span>{{ user?.email }}</span>
                </div>
            </header>
            <div class="content">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Menu from '../components/Menu.vue'
const expanded = ref(false)

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
            width: 50px;
        }

        .layout-header {
            display: flex;
            gap: 10px;
            align-items: center;
            height: 50px;
            width: 100%;
            overflow: hidden;
            color: white;
            background-color: #111827;
            position: relative;

            .user-content{
                display: flex;
                position: absolute;
                right: 30px;
                flex-direction: column;
                font-size: 12px;

                .name{
                    font-size: 14px;
                    font-weight: 600;
                }
            }
        }

        .content {
            flex: 1;
            padding: 20px;
            background: #f9fafb;

            @media (max-width: 768px) {
                padding: 10px;
            }
        }
    }
}
</style>
