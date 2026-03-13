import { createRouter, createWebHistory } from 'vue-router'

import Home from '../pages/Home.vue'
import Login from '../pages/Login.vue'
import UserList from '../pages/user/UserList.vue'
import UserForm from '../pages/user/UserForm.vue'
import CategoryList from '../pages/category/CategoryList.vue'
import CategoryForm from '../pages/category/CategoryForm.vue'
import TransactionList from '../pages/transaction/TransactionList.vue'
import TransactionForm from '../pages/transaction/TransactionForm.vue'
import Settings from '../pages/Settings.vue'

const routes = [
    {
        path: '/login',
        component: Login,
        meta: { layout: 'clean' }
    },
    {
        path: '/',
        component: Home,
        meta: { layout: 'default' }
    },

    // User
    {
        path: '/user',
        component: UserList,
        name: 'UserList',
        meta: { layout: 'default' },
    },
    {
        path: '/user/form/:id?',
        component: UserForm,
        name: 'UserForm',
        meta: { layout: 'default' }
    },

    // Category
    {
        path: '/category',
        component: CategoryList,
        name: 'CategoryList',
        meta: { layout: 'default' }
    },
    {
        path: '/category/form/:id?',
        component: CategoryForm,
        name: 'CategoryForm',
        meta: { layout: 'default' }
    },

    // Transaction
    {
        path: '/transaction',
        component: TransactionList,
        name: 'TransactionList',
        meta: { layout: 'default' }
    },
    {
        path: '/transaction/form/:id?',
        component: TransactionForm,
        name: 'TransactionForm',
        meta: { layout: 'default' }
    },

    // Settings
    {
        path: '/settings',
        component: Settings,
        name: 'Settings',
        meta: { layout: 'default' }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('auth_token')

    if (to.path !== '/login' && !isAuthenticated) {
        next('/login')
    } else if (to.path === '/login' && isAuthenticated) {
        next('/')
    } else {
        next()
    }
})

export default router
