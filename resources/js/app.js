
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Index from './Index'
import store from './store'
import router from './router'
import filters from './filters'
import Vuetify from 'vuetify'

Vue.use(Vuetify)

Object.entries(filters).forEach(entry => Vue.filter(entry[0], entry[1]))

const app = new Vue({
    el: '#app',
    components: { Index },
    store,
    router
});
