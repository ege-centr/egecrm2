import AbstractGroupIndex from '@/pages/AbstractGroup/Index'
import AbstractGroupShow from '@/pages/AbstractGroup/Show'

export default [
  {
    path: '/abstract-groups',
    name: 'AbstractGroupIndex',
    component: AbstractGroupIndex
  },
  {
    path: '/abstract-groups/:year/:grade_id/:subject_id',
    name: 'AbstractGroupShow',
    component: AbstractGroupShow
  },
]