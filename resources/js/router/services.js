import ServiceIndex from '@/pages/Service/Index'
import { ROLES } from '@/config'

export default [
  {
    path: '/services',
    name: 'ServiceIndex',
    component: ServiceIndex,
    meta: {
      roles: [ROLES.TEACHER]
    }
  },
]
