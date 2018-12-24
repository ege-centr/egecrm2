import ClientIndex from '@/pages/Client/Index'
import ClientShow from '@/pages/Client/Show'

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
  }
]
