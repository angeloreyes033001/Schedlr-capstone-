import { createApp } from 'vue'
import './style.css'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.js'
import App from './App.vue'
import { createPinia } from 'pinia'
import router from './routers/routers'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import {far} from '@fortawesome/free-regular-svg-icons';
import {fab} from '@fortawesome/free-brands-svg-icons';

library.add(fas,far,fab);

createApp(App)
.component('fa',FontAwesomeIcon)
.use(router)
.use(createPinia())
.mount('#app')
