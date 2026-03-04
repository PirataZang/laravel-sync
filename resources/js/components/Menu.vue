<template>
    <div>
        <!-- backdrop só aparece em modo overlay/móvel -->
        <div v-if="showMenu && overlay" class="menu-backdrop" @click="close" aria-hidden="true"></div>

        <aside
            v-if="showMenu"
            :class="['app-menu', { collapsed: collapsed } ]"
            role="navigation"
            @keydown.esc="close"
        >
            <div v-if="overlay" class="panel-header">
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
            { name: 'home', to: '/', icon: 'fas fa-home', title: 'Página Inicial' },
            { name: 'users', to: '/user', icon: 'fas fa-users', title: 'Usuários' },
            { name: 'categories', to: '/category', icon: 'fas fa-list', title: 'Categorias' },
            { name: 'transactions', to: '/transaction', icon: 'fas fa-exchange-alt', title: 'Transações' },
        ],
    },
    showMenu: { type: Boolean, default: false },
    // indica que o menu deve aparecer apenas como ícones
    collapsed: { type: Boolean, default: false },
    // use este flag para renderizar como um overlay móvel
    overlay: { type: Boolean, default: false },
})

// transformar props em refs para uso no template
const { showMenu, collapsed, overlay } = toRefs(props)

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
.app-menu {
    position: fixed;
    height: stretch;
    z-index: 10;
    width: 250px;
    background: #111827;
    color: #fff;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.6);
    transition: width 0.2s ease;
}
/* estado colapsado apenas mostra ícones */
.app-menu.collapsed {
    width: 60px;
}
.app-menu.collapsed .menu-text {
    display: none;
}
.app-menu.collapsed .panel-header {
    display: none;
}

.menu-text {
    transition: opacity 0.2s ease;
}
.app-menu.collapsed .menu-text {
    opacity: 0;
}

/* mobile overlay - ocupa metade da tela */
@media (max-width: 768px) {
    .app-menu {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        width: 50vw;
        z-index: 1000;
    }
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
