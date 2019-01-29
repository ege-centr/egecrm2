import store from '@/store'

export const API_URL = 'teachers'

export const CLASS_NAME = 'Teacher'

export const MODEL_DEFAULTS = {}

export const FILTERS = [
  {label: 'Предмет', field: 'subjects_ec', type: 'select', options: store.state.data.subjects, textField: 'name'},
]

export const TeacherDialog = require('./Dialog')
export const TeacherList = require('./List')