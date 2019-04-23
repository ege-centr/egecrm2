import store from '@/store'
import { objectToOptionsArray } from '@/other/functions'

export const API_URL = 'teachers'

export const CLASS_NAME = 'Teacher'

export const MODEL_DEFAULTS = {}

export const STATUS = {
  0: 'не активен в системе',
  1: 'преподаватели запаса',
  2: 'ведут занятия сейчас',
  3: 'ранее работал',
  4: 'готов к собеседованию',
  5: 'закрыто'
}

export const FILTERS = [
  {label: 'Предмет', field: 'subjects_ec', type: 'multiple', options: store.state.data.subjects, textField: 'name'},
  {label: 'Статус', field: 'in_egecentr', type: 'multiple', options: objectToOptionsArray(STATUS, 'id', 'title')}
]

export const TeacherDialog = require('./Dialog')
export const TeacherList = require('./List')