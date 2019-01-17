import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'
import { ROLES } from '@/config'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    ...require('./tests').default,
    ...require('./teachers').default,
    ...require('./special-dates').default,
    ...require('./payments').default,
    ...require('./requests').default,
    ...require('./admins').default,
    ...require('./clients').default,
    ...require('./groups').default,
    ...require('./tasks').default,
    ...require('./logs').default,
    ...require('./recommended-prices').default,
  ]
})

// router.beforeEach((to, from, next) => {
//   if (store.state.user) {
//     colorLog('to')
//     console.log(to)
//     if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('roles')) {
//       console.log(store.state.user.class, to.meta.roles)
//       if (to.meta.roles.indexOf(store.state.user.class) !== -1) {
//         next()
//       }
//     } else {
//       if (store.state.user.class === ROLES.ADMIN) {
//         next()
//       }
//     }
//   } else {
//     colorLog('no user', 'LightCoral')
//     next()
//   }
// })

export default router
