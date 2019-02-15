import { ROLES } from '@/config'
import ProfileIndex from '@/pages/Profile/Index'

export default [
  {
    path: '/profile',
    name: 'ProfileIndex',
    component: ProfileIndex,
    meta: {
      roles: [ ROLES.CLIENT ]
    }
  },
]
