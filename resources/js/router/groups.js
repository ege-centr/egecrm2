import GroupIndex from '@/pages/Group/Index'
import GroupShow from '@/pages/Group/Show'

export default [
  {
    path: '/groups',
    name: 'GroupIndex',
    component: GroupIndex
  },
  {
    path: '/groups/:id',
    name: 'GroupShow',
    component: GroupShow
  }
]
