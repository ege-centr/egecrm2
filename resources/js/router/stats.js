import StatIndex from '@/pages/Stat/Index'
import StatPaymentIndex from '@/pages/Stat/Payment/Index'

export default [
  {
    path: '/stats',
    name: 'StatIndex',
    component: StatIndex
  },

  {
    path: '/stats/payments',
    name: 'StatPaymentIndex',
    component: StatPaymentIndex
  },
]
