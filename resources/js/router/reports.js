import { ROLES } from '@/config'
import ReportIndex from '@/pages/Report/Index'
import ReportShow from '@/pages/Report/Show'
// import ReportForm from '@/pages/Report/Form'

export default [
  {
    path: '/reports',
    name: 'ReportIndex',
    component: ReportIndex,
    meta: {
      roles: [ ROLES.TEACHER, ROLES.ADMIN, ROLES.CLIENT ]
    }
  },
  {
    path: '/reports/:id',
    name: 'ReportShow',
    component: ReportShow,
    meta: {
      roles: [ ROLES.CLIENT ]
    }
  },
  // {
  //   path: '/reports/:id/edit',
  //   name: 'ReportEdit',
  //   component: ReportForm
  // },
  // {
  //   path: '/reports/create',
  //   name: 'ReportCreate',
  //   component: ReportForm
  // }
]
