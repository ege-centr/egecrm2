import store from '@/store'

export const CLASS_NAME = 'Group\\Group'
export const API_URL = 'groups'
export const GROUP_CLIENTS_API_URL = 'group-clients'
export const GROUP_ACTS_API_URL = 'group-acts'

export const FILTERS = [
  {label: 'Год', field: 'year', type: 'multiple', options: store.state.data.years},
  {label: 'Преподаватель', field: 'teacher_id', type: 'multiple', options: store.state.data.teachers, textField: 'names.abbreviation'},
  {label: 'Предмет', field: 'subject_id', type: 'multiple', options: store.state.data.subjects, textField: 'name'},
  {label: 'Класс', field: 'grade_id', type: 'multiple', options: store.state.data.grades},
  {label: 'Филиал', field: 'branch_id', type: 'multiple', options: store.state.data.branches, textField: 'full'},
]

export const MODEL_DEFAULTS = {
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

export const GroupSchedule = require('./Schedule')
export const GroupList = require('./List')
export const GroupDialog = require('./Dialog')
export const MoveClientDialog = require('./MoveClientDialog')
export const GroupActList = require('./Act/List')
export const GroupActDialog = require('./Act/Dialog')