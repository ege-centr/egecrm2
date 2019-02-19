import BalanceIndex from '@/pages/Balance/Index'
import { ROLES } from '@/config'

export default [
  {
    path: '/balance',
    name: 'BalanceIndex',
    component: BalanceIndex,
    meta: {
      roles: [ROLES.CLIENT, ROLES.TEACHER]
    }
  },
]
