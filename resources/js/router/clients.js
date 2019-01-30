import ClientIndex from '@/pages/Client/Index'
import ClientIndexInfinite from '@/pages/Client/IndexInfinite'
import ClientShow from '@/pages/Client/Show'

export default [
  {
    path: '/clients',
    name: 'ClientIndex',
    component: ClientIndex
  },
  {
    path: '/clients-infinite',
    name: 'ClientIndexInfinite',
    component: ClientIndexInfinite
  },
  {
    path: '/clients/:id',
    name: 'ClientShow',
    component: ClientShow
  }
]
