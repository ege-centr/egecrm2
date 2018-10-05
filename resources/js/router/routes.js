import Requests from '@/pages/Requests'
import Users from '@/pages/Users'

import ClientsIndex from '@/pages/ClientsIndex'
import ClientsShow from '@/pages/ClientsShow'

export default [
  {
    path: '/requests',
    name: 'Requests',
    component: Requests
  },
  {
    path: '/users',
    name: 'Users',
    component: Users
  },
  {
    path: '/clients',
    name: 'ClientsIndex',
    component: ClientsIndex
  },
  {
    path: '/clients/:id',
    name: 'ClientsShow',
    component: ClientsShow
  },
]
