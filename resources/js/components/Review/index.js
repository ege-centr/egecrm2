import store from '@/store'

export const API_URL = 'reviews'

export const MODEL_DEFAULTS = {
  
}

export const COMMENT_TYPE = {
  client: 'client',
  admin: 'admin',
  final: 'final',
}

export const FILTERS = [
  {field: 'year', type: 'multiple', label: 'Год', options: store.state.data.years},
  {field: 'subject_id', type: 'multiple', label: 'Предмет', options: store.state.data.subjects, textField: 'name'},
  {label: 'Преподаватель', field: 'teacher_id', type: 'multiple', options: store.state.data.teachers, textField: 'names.abbreviation'},
  {label: 'Пользователь', field: 'reviewer_admin_id', type: 'admin', valueField: 'email_id'},
  {field: 'is_published', type: 'select', label: 'Опубликован', options: [
    {id: 1, title: 'да'},
    {id: 0, title: 'нет'},
  ]},
  {field: 'is_approved', type: 'select', label: 'Проверен', options: [
    {id: 1, title: 'да'},
    {id: 0, title: 'нет'},
  ]}
  {label: 'Оценка ученика', field: 'client_rating', type: 'multiple', options: [
    {id: -2, title: 'отсутствует'},
    {id: 1, title: '1'},
    {id: 2, title: '2'},
    {id: 3, title: '3'},
    {id: 4, title: '4'},
    {id: 5, title: '5'},
  ]},
  {label: 'Оценка админа предварительная', field: 'admin_rating', type: 'multiple', options: [
    {id: -2, title: 'отсутствует'},
    {id: -1, title: '0'},
    {id: 1, title: '1'},
    {id: 2, title: '2'},
    {id: 3, title: '3'},
    {id: 4, title: '4'},
    {id: 5, title: '5'},
  ]},
  {label: 'Оценка админа финал', field: 'final_rating', type: 'multiple', options: [
    {id: -2, title: 'отсутствует'},
    {id: -1, title: '0'},
    {id: 1, title: '1'},
    {id: 2, title: '2'},
    {id: 3, title: '3'},
    {id: 4, title: '4'},
    {id: 5, title: '5'},
  ]},
]

export const ReviewList = require('./List')
export const ReviewDialog = require('./Dialog')