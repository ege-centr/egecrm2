import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    ...require('./requests').default,
    ...require('./users').default,
    ...require('./clients').default,
    ...require('./groups').default,
    ...require('./tasks').default,
    ...require('./logs').default,
  ]
})

export default router
