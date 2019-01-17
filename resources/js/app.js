
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')
window.Vue.http = axios

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Index from '@/components/Index'
import store from './store'
import router from './router'
import filters from '@/other/filters'
import {GlobalPlugin} from '@/other/plugins'
import VueTheMask from 'vue-the-mask'
import Vuetify from 'vuetify'
// Translation provided by Vuetify (javascript)
import ru from 'vuetify/es5/locale/ru'
import vueUpload from '@websanova/vue-upload'
import * as VueGoogleMaps from 'vue2-google-maps'

[GlobalPlugin, VueTheMask, vueUpload].forEach(use => Vue.use(use))

Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyAXXZZwXMG5yNxFHN7yR4GYJgSe9cKKl7o',
    libraries: 'places',
    language: 'ru'
  },
})

Vue.use(Vuetify, {
  lang: {
    locales: { ru },
    current: 'ru'
  }
})

Object.entries(filters).forEach(entry => Vue.filter(entry[0], entry[1]))

const app = new Vue({
    el: '#app',
    components: { Index },
    store,
    router
});
