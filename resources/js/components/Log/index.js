import { ROLES } from '@/config'

export const API_URL = 'logs'

export const TYPES = [
  {id: 'update', title: 'обновление'},
  {id: 'delete', title: 'удаление'},
  {id: 'create', title: 'создание'},
  {id: 'url', title: 'url'},
  {id: 'auth', title: 'авторизация'},
]

export const FILTERS = [
  {label: 'тип действия', field: 'type', type: 'multiple', options: TYPES},
  {label: 'тип пользователя', field: 'user_type', type: 'multiple', options: [
    {id: ROLES.ADMIN, title: 'администратор'},
    {id: ROLES.TEACHER, title: 'преподаватель'},
    {id: ROLES.CLIENT, title: 'ученик'},
  ]},
  {label: 'период', field: 'created_at', type: 'interval'},
]