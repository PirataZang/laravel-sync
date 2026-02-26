<template>
    <div>
        <div v-if="showMenu" class="menu-backdrop" @click="close" aria-hidden="true"></div>

        <aside v-if="showMenu" class="app-menu" role="navigation" @keydown.esc="close">
            <div class="panel-header">
                <button class="close-btn" @click="close" aria-label="Fechar menu"><i class="fas fa-times"></i></button>
            </div>

            <ul class="menu-items">
                <li v-for="item in items" :key="item.name">
                    <router-link :to="item.to" :class="['menu-link', { 'is-active': isActive(item) }]" @click="onItemClick">
                        <i :class="item.icon" aria-hidden="true"></i>
                        <span class="menu-text">{{ item.title }}</span>
                    </router-link>
                </li>
            </ul>
        </aside>
    </div>
</template>

<script setup>
import { toRefs } from 'vue'
import { useRoute } from 'vue-router'

const props = defineProps({
    items: {
        type: Array,
        default: () => [
            { name: 'home', to: '/home', icon: 'fas fa-home', title: 'Home' },
            { name: 'users', to: '/user', icon: 'fas fa-users', title: 'Users' },
            { name: 'login', to: '/login', icon: 'fas fa-sign-in-alt', title: 'Login' },
        ],
    },
    showMenu: { type: Boolean, default: false },
})

const { showMenu } = toRefs(props)

const emit = defineEmits(['update:showMenu', 'close'])

function close() {
    emit('update:showMenu', false)
    emit('close')
}

function onItemClick() {
    close()
}

const route = useRoute()

function isActive(item) {
    if (!item || !item.to) return false
    return route.path === item.to || route.path.startsWith(item.to + '/')
}
</script>

<style scoped lang="scss">
.menu-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 9998;
}
.app-menu {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    width: 250px;
    background: #111827;
    color: #fff;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.6);
}
.panel-header {
    display: flex;
    justify-content: flex-end;
    padding: 8px;
}
.close-btn {
    background: transparent;
    border: none;
    color: inherit;
    font-size: 18px;
    cursor: pointer;
}
.menu-items {
    list-style: none;
    margin: 0;
    padding: 8px;
}
.menu-items li {
    margin-bottom: 4px;
}
.menu-link {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 10px;
    color: inherit;
    text-decoration: none;
}
.menu-link i {
    width: 28px;
    text-align: center;
}
.menu-text {
    white-space: nowrap;
}

.menu-link.is-active { background: rgba(255,255,255,0.06); font-weight: 600; border-left: 3px solid #4f46e5 }
</style>
