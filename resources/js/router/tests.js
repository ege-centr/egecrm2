import TestIndex from '@/pages/Test/Index'
import TestClientStart from '@/pages/Test/Client/Start'
// import TestShow from '@/pages/Test/Show'
// import TestForm from '@/pages/Test/Form'

export default [
  {
    path: '/tests',
    name: 'TestIndex',
    component: TestIndex
  },
  {
    path: '/tests/:id',
    name: 'TestClientStart',
    component: TestClientStart
  },
  // {
  //   path: '/tests/:id/edit',
  //   name: 'TestEdit',
  //   component: TestForm
  // },
  // {
  //   path: '/tests/create',
  //   name: 'TestCreate',
  //   component: TestForm
  // }
]
