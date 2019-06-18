import AdminIndex from '@/pages/Admin/Index'
import AdminPhotos from '@/pages/Admin/Photos'

export default [
  {
    path: '/admin-photos',
    name: 'AdminPhotos',
    component: AdminPhotos
  },
  {
    path: '/admins',
    name: 'AdminIndex',
    component: AdminIndex
  }
]
