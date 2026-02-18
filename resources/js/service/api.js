import axios from 'axios'
import { loadingStore } from '../store/LoadingStore'

export const api = axios.create({
    baseURL: 'http://localhost:8000/api'
})

// 👉 interceptor de request
api.interceptors.request.use((config) => {
    loadingStore.start()

    const token = localStorage.getItem('auth_token')

    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }

    return config
})

// 👉 interceptor de response
api.interceptors.response.use(
    (response) => {
        loadingStore.stop()
        return response
    },
    (error) => {
        loadingStore.stop()

        // 👉 se token expirou
        if (error.response?.status === 401) {
            localStorage.removeItem('auth_token')
            window.location.href = '/login'
        }

        return Promise.reject(error)
    }
)