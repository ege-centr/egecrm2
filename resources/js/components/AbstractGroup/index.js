import store from '@/store'

export const API_URL = 'abstract-groups'

export const FILTERS = [
  {field: 'year', type: 'multiple', label: 'Год', options: _.clone(store.state.data.years)},
  {field: 'subject_id', type: 'multiple', label: 'Предмет', options: _.clone(store.state.data.subjects), textField: 'name'},
  {field: 'grade_id', type: 'multiple', label: 'Класс', options: _.clone(store.state.data.grades)},
];

export const AbstractGroupList = require('./List')