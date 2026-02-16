import { createApp } from 'vue'
import App from './App.vue'
import router from './router/routes'
import 'ag-grid-community/styles/ag-grid.css'
import 'ag-grid-community/styles/ag-theme-quartz.css'

createApp(App)
    .use(router)
    .mount('#app')
