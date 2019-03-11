import store from '@/store'

export const API_URL = 'teachers'

export const CLASS_NAME = 'Teacher'

export const MODEL_DEFAULTS = {}

export const FILTERS = [
  {label: 'Предмет', field: 'subjects_ec', type: 'multiple', options: store.state.data.subjects, textField: 'name'},
  {label: 'Статус', field: 'in_egecentr', type: 'multiple', options: [
    {id: 1, title: 'преподаватели запаса'},
    {id: 2, title: 'ведут занятия сейчас'},
    {id: 3, title: 'ранее работал'},
  ]}
]

export const TeacherDialog = require('./Dialog')
export const TeacherList = require('./List')