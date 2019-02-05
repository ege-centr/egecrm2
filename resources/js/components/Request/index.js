import store from '@/store'

export const API_URL = 'requests'

export const REQUEST_STATUSES = [
  {title: 'новые', id: 'new'},
  {title: 'ожидающиеся', id: 'awaiting'},
  {title: 'выполненные', id: 'finished'}
]

export const MODEL_DEFAULTS = {
  status: REQUEST_STATUSES[0].id,
  phones: [{phone: '', comment: ''}]
}

export const FILTERS = [
  {label: 'Статус', field: 'status', type: 'multiple', options: REQUEST_STATUSES},
  {label: 'Класс', field: 'grade_id', type: 'multiple', options: store.state.data.grades},
  {label: 'Ответственный', field: 'responsible_admin_id', type: 'admin'},
  {label: 'Пользователь', field: 'created_admin_id', type: 'admin'},
  {label: 'Период', field: 'created_at', type: 'interval'}
]

export const RequestItem = require('./Item')
export const RequestDialog = require('./Dialog')
