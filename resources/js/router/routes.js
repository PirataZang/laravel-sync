import { createRouter, createWebHistory } from 'vue-router'

import Home from '../pages/Home.vue'
import Login from '../pages/Login.vue'
import UserList from '../pages/user/UserList.vue'

const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login},
    { path: '/user/list', component: UserList},
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
