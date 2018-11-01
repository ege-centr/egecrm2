import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    ...require('./payments').default,
    ...require('./requests').default,
    ...require('./users').default,
    ...require('./clients').default,
    ...require('./groups').default,
    ...require('./tasks').default,
    ...require('./logs').default,
    ...require('./recommended-prices').default,
  ]
})

export default router
