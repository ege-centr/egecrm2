export const API_URL = 'logs'

export const TYPES = [
  {id: 'update', title: 'обновление'},
  {id: 'delete', title: 'удаление'},
  {id: 'create', title: 'создание'},
  {id: 'url', title: 'url'},
  {id: 'auth', title: 'авторизация'},
]

export const FILTERS = [
  {label: 'тип', field: 'type', type: 'multiple', options: TYPES}
]