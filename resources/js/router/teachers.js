import TeacherIndex from '@/pages/Teacher/Index'
import TeacherShow from '@/pages/Teacher/Show'

export default [
  {
    path: '/teachers',
    name: 'TeacherIndex',
    component: TeacherIndex
  },
  {
    path: '/teachers/:id',
    name: 'TeacherShow',
    component: TeacherShow
  }
]
