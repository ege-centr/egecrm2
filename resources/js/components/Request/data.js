import store from '@/store'

export const request_statuses = [
  {text: 'новые', value: 'new'},
  {text: 'ожидающиеся', value: 'awaiting'},
  {text: 'выполненные', value: 'finished'}
]

export const model_defaults = {
  status: 'new',
  phones: [{phone: '', comment: ''}]
}

export const filters = [
  {label: 'Статус', field: 'status', type: 'select', options: request_statuses},
  // {label: 'Класс', field: 'grade', type: 'select', options: store.state.data.grades, valueField: 'id', textField: 'title'},
  // {label: 'Ответственный', field: 'responsible_admin_id', type: 'select', options: store.state.data.admins, valueField: 'id', textField: 'name'},
  {label: 'Имя', field: 'name', type: 'text'}
]
