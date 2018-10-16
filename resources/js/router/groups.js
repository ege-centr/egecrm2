import GroupIndex from '@/pages/Group/Index'
import GroupForm from '@/pages/Group/Form'

export default [
  {
    path: '/groups',
    name: 'GroupIndex',
    component: GroupIndex
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
