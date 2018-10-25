import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    ...require('./requests').default,
    ...require('./users').default,
    ...require('./clients').default,
    ...require('./groups').default,
    ...require('./tasks').default
  ]
})

// router.beforeEach((to, from, next) => {
//   if (store.)
//   next()
// })

export default router
