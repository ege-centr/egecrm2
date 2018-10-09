import Requests from '@/pages/Requests'
import Users from '@/pages/Users'

import ClientsIndex from '@/pages/ClientsIndex'
import ClientsShow from '@/pages/ClientsShow'
import ClientForm from '@/pages/Client/ClientForm'

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
  {
    path: '/clients/:id/edit',
    name: 'ClientEdit',
    component: ClientForm
  },
  {
    path: '/clients/create',
    name: 'ClientCreate',
    component: ClientForm
  },
]
