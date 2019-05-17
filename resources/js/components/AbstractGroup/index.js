import store from '@/store'

export const API_URL = 'abstract-groups'

export const FILTERS = [
  {field: 'year', type: 'multiple', label: 'Год', options: store.state.data.years},
  {field: 'subject_id', type: 'multiple', label: 'Предмет', options: store.state.data.subjects, textField: 'name'},
  {field: 'grade_id', type: 'multiple', label: 'Класс', options: store.state.data.grades},
];

export const AbstractGroupList = require('./List')