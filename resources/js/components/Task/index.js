export const API_URL = 'tasks'

export const CLASS_NAME = 'Task'

export const STATUSES = [
  {title: 'Новая', id: 'new', class: 'blue--text'},
  {title: 'Выполнено (проверяется)', id: 'testing', class: 'light-green--text'},
  {title: 'Выполнено', id: 'done', class: 'green--text'},
  {title: 'Требует доработки', id: 'debug', class: 'orange--text'},
  {title: 'Закрыто', id: 'closed', class: 'red--text'},
]

export const MODEL_DEFAULTS = {
  text: '',
  files: [],
  status: STATUSES[0].value
}

export const FILTERS = [
  {label: 'Статус', field: 'status', type: 'multiple', options: STATUSES},
  {label: 'Создатель', field: 'created_email_id', type: 'admin', valueField: 'email_id'},
  {label: 'Ответственный', field: 'responsible_admin_id', type: 'admin'},
]

export const TaskDialog = require('./Dialog')
export const TaskItem = require('./Item')
export const TaskList = require('./List')
