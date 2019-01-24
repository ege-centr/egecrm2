import store from '@/store'

export const API_URL = 'requests'

export const REQUEST_STATUSES = [
  {title: 'новые', id: 'new'},
  {title: 'ожидающиеся', id: 'awaiting'},
  {title: 'выполненные', id: 'finished'}
]

export const MODEL_DEFAULTS = {
  status: 'new',
  phones: [{phone: '', comment: ''}]
}

// TODO: переименовать в grade_id
export const FILTERS = [
  {label: 'Статус', field: 'status', type: 'select', options: REQUEST_STATUSES},
  {label: 'Класс', field: 'grade', type: 'select', options: store.state.data.grades},
  {label: 'Ответственный', field: 'responsible_admin_id', type: 'select', options: store.state.data.admins, textField: 'name'},
  {label: 'Имя', field: 'name', type: 'text'}
]

export const RequestList = require('./List')
export const RequestDialog = require('./Dialog')
