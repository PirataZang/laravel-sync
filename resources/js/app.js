import { createApp } from 'vue'
import App from './App.vue'
import router from './router/routes'
import '../css/app.css'
import '../sass/app.scss'
import 'ag-grid-community/styles/ag-grid.css'
import 'ag-grid-community/styles/ag-theme-quartz.css'
import '@fortawesome/fontawesome-free/css/all.min.css'

const app = createApp(App)

app
    .use(router)
    .mount('#app')