import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'
import { ROLES } from '@/config'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  },
  routes: [
    ...require('./schedules').default,
    ...require('./profiles').default,
    ...require('./devs').default,
    ...require('./visits').default,
    ...require('./contracts').default,
    ...require('./macros').default,
    ...require('./abstract-groups').default,
    ...require('./tests').default,
    ...require('./teachers').default,
    ...require('./settings').default,
    ...require('./payments').default,
    ...require('./requests').default,
    ...require('./admins').default,
    ...require('./clients').default,
    ...require('./groups').default,
    ...require('./tasks').default,
    ...require('./logs').default,
  ]
})

router.beforeEach((to, from, next) => {
  // очищаем результаты поиска при переходе по другой ссылке
  if (store.state.search !== null) {
    store.commit('set', {field: 'search', payload: null})
  }
  Vue.nextTick(() => {
    if (store.state.user) {
      if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('roles')) {
        if (to.meta.roles.indexOf(store.state.user.class) !== -1) {
          next()
        }
      } else {
        if (store.state.user.class === ROLES.ADMIN) {
          next()
        }
      }
    } else {
      colorLog('no user', 'LightCoral')
      // Vue.nextTick(() => router.push({name: to.name}))
    }
  })
})

export default router
