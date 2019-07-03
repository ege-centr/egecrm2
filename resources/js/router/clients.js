import ClientIndex from '@/pages/Client/Index'
import ClientShow from '@/pages/Client/Show'
import ClienttPhotos from '@/pages/Client/Photos'

export default [
  {
    path: '/client-photos',
    name: 'ClienttPhotos',
    component: ClienttPhotos
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
