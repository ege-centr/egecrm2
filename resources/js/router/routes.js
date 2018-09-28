import Requests from '@/pages/Requests'
import Clients from '@/pages/Clients'
import Student from '@/pages/Student'

export default [
  {
    path: '/requests',
    name: 'Requests',
    component: Requests
  },
  {
    path: '/clients',
    name: 'Clients',
    component: Clients
  },
  {
    path: '/student/:id',
    name: 'Student',
    component: Student
  },
]
