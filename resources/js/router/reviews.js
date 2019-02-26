import { ROLES } from '@/config'
import ReviewIndex from '@/pages/Review/Index'
// import ReviewShow from '@/pages/Review/Show'
// import ReviewForm from '@/pages/Review/Form'

export default [
  {
    path: '/reviews',
    name: 'ReviewIndex',
    component: ReviewIndex,
    meta: {
      roles: [ROLES.CLIENT]
    }
  },
  // {
  //   path: '/reviews/:id',
  //   name: 'ReviewShow',
  //   component: ReviewShow
  // },
  // {
  //   path: '/reviews/:id/edit',
  //   name: 'ReviewEdit',
  //   component: ReviewForm
  // },
  // {
  //   path: '/reviews/create',
  //   name: 'ReviewCreate',
  //   component: ReviewForm
  // }
]
