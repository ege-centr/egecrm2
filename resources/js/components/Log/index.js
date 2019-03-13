export const API_URL = 'logs'

export const TYPES = [
  {id: 'update', title: 'обновление'},
  {id: 'delete', title: 'удаление'},
  {id: 'create', title: 'создание'}
]

export const FILTERS = [
  {label: 'тип', field: 'type', type: 'multiple', options: TYPES}
]