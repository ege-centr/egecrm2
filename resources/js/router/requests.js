import RequestIndex from '@/pages/Request/Index'
import RequestShow from '@/pages/Request/Show'

export default [
  {
    path: '/requests',
    name: 'RequestIndex',
    component: RequestIndex,
    meta: {
      title: 'Заявки'
    }
  },
  {
    path: '/requests/:id',
    name: 'RequestShow',
    component: RequestShow,
  },
]
