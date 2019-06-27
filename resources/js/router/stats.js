import StatIndex from '@/pages/Stat/Index'
import StatPaymentIndex from '@/pages/Stat/Payment/Index'

export default [
  {
    path: '/stats',
    name: 'StatIndex',
    component: StatIndex,
    meta: {
      right: 101,
    }
  },

  {
    path: '/stats/payments',
    name: 'StatPaymentIndex',
    component: StatPaymentIndex,
    meta: {
      right: 101,
    }
  },
]
