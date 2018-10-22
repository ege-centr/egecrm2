import GroupIndex from '@/pages/Group/Index'
import GroupForm from '@/pages/Group/Form'
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
  },
  {
    path: '/groups/create',
    name: 'GroupCreate',
    component: GroupForm
  },
  {
    path: '/groups/:id/edit',
    name: 'GroupEdit',
    component: GroupForm
  },
]
