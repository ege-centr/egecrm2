import ClientIndex from '@/pages/Client/Index'
import ClientShow from '@/pages/Client/Show'
import ClientPhotos from '@/pages/Client/Photos'

export default [
  {
    path: '/client-photos',
    name: 'ClientPhotos',
    component: ClientPhotos
  },
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
