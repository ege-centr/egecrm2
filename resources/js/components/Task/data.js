export const url = 'tasks'
export const statuses = [
  {text: 'Новая', value: 'new', class: ''},
  {text: 'Выполнено (проверяется)', value: 'testing', class: 'green--text'},
  {text: 'Выполнено', value: 'done', class: 'green--text'},
  {text: 'Требует доработки', value: 'debug', class: 'orange--text'},
  {text: 'Закрыто', value: 'closed', class: 'red--text'},
]
export const model_defaults = {
  text: '',
  status: statuses[0].value
}
export const filters = [
  {label: 'Статус', field: 'status', type: 'select', options: statuses},
  {label: 'Текст задачи', field: 'text', type: 'text'}
]
export const TaskDialog = require('./Dialog')
export const TaskItem = require('./Item')
