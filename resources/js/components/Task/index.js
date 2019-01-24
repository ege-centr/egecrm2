export const API_URL = 'tasks'

export const CLASS_NAME = 'Task'

export const STATUSES = [
  {text: 'Новая', value: 'new', class: ''},
  {text: 'Выполнено (проверяется)', value: 'testing', class: 'green--text'},
  {text: 'Выполнено', value: 'done', class: 'green--text'},
  {text: 'Требует доработки', value: 'debug', class: 'orange--text'},
  {text: 'Закрыто', value: 'closed', class: 'red--text'},
]

export const MODEL_DEFAULTS = {
  text: '',
  status: STATUSES[0].value
}

export const FILTERS = [
  {label: 'Статус', field: 'status', type: 'select', options: STATUSES},
  {label: 'Текст задачи', field: 'text', type: 'text'}
]

export const TaskDialog = require('./Dialog')
export const TaskItem = require('./Item')
export const TaskList = require('./List')
