import store from '@/store'

export const API_URL = 'groups'

export const GROUP_CLIENTS_API_URL = 'group-clients'

export const model_defaults = {
  is_ready_to_start: false,
  is_archived: false,
  clients: [],
  lessons: []
}

export const LEVELS = [
  {text: 'низкий', value: 'low'},
  {text: 'средний', value: 'mid'},
  {text: 'высокий', value: 'high'},
  {text: 'спец. группа', value: 'special'}
]

export const FILTERS = [
  {label: 'Класс', field: 'grade_id', type: 'select', options: store.state.data.grades, valueField: 'id', textField: 'title'},
  {label: 'Предмет', field: 'subject_id', type: 'select', options: store.state.data.subjects, valueField: 'id', textField: 'name'},
  // {label: 'Год', field: 'year', type: 'select', options: store.state.data.years, valueField: 'id', textField: 'name'},
];

export const GroupSchedule = require('./Schedule')
export const GroupList = require('./List')
export const MoveClientDialog = require('./MoveClientDialog')