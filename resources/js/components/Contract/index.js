import store from '@/store'

export const API_URL = 'contracts'

export const MODEL_DEFAULTS = {
  subjects: [],
  payments: [],
  grade_id: null,
  year: null,
}

export const SUBJECT_STATUS = {
  active: 'active',
  to_be_terminated: 'to_be_terminated',
  terminated: 'terminated',
}

export const SUBJECT_STATUS_LABELS = [
  {id: SUBJECT_STATUS.active, title: 'активный'},
  {id: SUBJECT_STATUS.to_be_terminated, title: 'к расторжению'},
  {id: SUBJECT_STATUS.terminated, title: 'расторжен'},
]

export const FILTERS = [
  {label: '№ договора', field: 'number', type: 'input'},
  {label: 'Год', field: 'year', type: 'multiple', options: store.state.data.years},
  {label: 'Класс', field: 'grade_id', type: 'multiple', options: store.state.data.grades},
  {label: 'Версия', field: 'version', skipFacets: true, type: 'multiple', options: [
    {id: 'first', title: 'первая'},
    {id: 'last', title: 'последняя'},
  ]},
  {label: 'Пользователь', field: 'created_email_id', type: 'admin', valueField: 'email_id'},
  {label: 'Дата создания', field: 'date_timestamp', type: 'interval'},
  {label: 'Дата создания из реквизитов', field: 'created_at_timestamp', type: 'interval'},
]


export const SUBJECT_DEFAULTS = {
  status: SUBJECT_STATUS.active
}

export const DISCOUNTS = [4, 6, 8, 10, 15, 20, 30, 40, 50, 70, 90]

export const getSubjectStatusClass = function(status) {
  return {
    'grey--text': status === null,
    'orange--text': status === SUBJECT_STATUS.to_be_terminated,
    'red--text': status === SUBJECT_STATUS.terminated,
  }
}