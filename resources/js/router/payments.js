import { ROLES } from '@/config'
import PaymentIndex from '@/pages/Payment/Index'

export default [
  {
    path: '/payments',
    name: 'PaymentIndex',
    component: PaymentIndex,
    meta: {
      roles: [ROLES.ADMIN]
    },
  }
]
