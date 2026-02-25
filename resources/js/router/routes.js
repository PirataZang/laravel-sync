import { createRouter, createWebHistory } from 'vue-router'

import Home from '../pages/Home.vue'
import Login from '../pages/Login.vue'
import UserList from '../pages/user/UserList.vue'
import UserForm from '../pages/user/UserForm.vue'

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
        path: '/user/list',
        component: UserList,
        meta: { layout: 'default' }
    },
    {
        path: '/user/form/:id?',
        component: UserForm,
        name: 'UserForm',
        meta: { layout: 'default' }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
