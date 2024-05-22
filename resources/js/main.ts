import '@/style/index.css'

import { createApp, ref } from 'vue'
import { createPinia } from 'pinia'
import { createORM } from 'pinia-orm'

import App from './App.vue'
import router from '@/router'
import axios from 'axios'
import User from '@/models/User'
import { PageSpinnerKey, SpinnerKey } from '@/symbols.js'
import SvgIconVue from './components/SvgIcon.vue'
import { GesturePlugin } from '@vueuse/gesture'
import { VueMountable } from 'vue-mountable'
import modalPlugin from '@/plugins/modalPlugin'

axios.interceptors.response.use(
    (response) => {
        return response
    },
    (error) => {
        // // if the response is a 401, token invalid
        if (error.response.status === 401) {
            console.log('got 401, logging out')

            User.logout()
        }
        throw error
    }
)

// set token in header
if (localStorage.getItem('token')) {
    console.log('token found')
    axios.defaults.headers.common['Authorization'] =
        `Bearer ${localStorage.getItem('token')}`
}

const app = createApp(App)
    .use(createPinia().use(createORM()))
    .use(GesturePlugin)
    .use(VueMountable())
    .use(modalPlugin)
    .use(router)

app.provide(SpinnerKey, {
    visible: ref(false),
    show() {
        this.visible.value = true
    },
    hide() {
        this.visible.value = false
    },
})

app.provide(PageSpinnerKey, {
    visible: ref(false),
    show() {
        this.visible.value = true
    },
    hide() {
        this.visible.value = false
    },
})

app.component('svg-icon', SvgIconVue)

app.mount('#app')
