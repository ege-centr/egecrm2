import ScheduleIndex from '@/pages/Schedule/Index'
import { ROLES } from '@/config'

export default [
  {
    path: '/schedule',
    name: 'ScheduleIndex',
    component: ScheduleIndex,
    meta: {
      roles: [ROLES.CLIENT],
    }
  },
]
