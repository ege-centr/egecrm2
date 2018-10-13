import ClientIndex from '@/pages/Client/Index'
import ClientShow from '@/pages/Client/Show'
import ClientForm from '@/pages/Client/Form'

export default [
  {
    path: '/clients',
    name: 'ClientIndex',
    component: ClientIndex
  },
  {
    path: '/clients/:id',
    name: 'ClientShow',
    component: ClientShow
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
  }
]
