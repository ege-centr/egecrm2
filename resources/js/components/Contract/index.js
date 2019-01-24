import store from '@/store'

export const API_URL = 'contracts'

export const MODEL_DEFAULTS = {
  subjects: [],
  payments: [],
  grade_id: null,
  year: null,
}

export const SUBJECT_STATUS_ACTIVE = 'active'
export const SUBJECT_STATUS_TO_BE_TERMINATED = 'to_be_terminated'
export const SUBJECT_STATUS_TERMINATED = 'terminated'

export const SUBJECT_STATUS_LABELS = [
  {id: SUBJECT_STATUS_ACTIVE, title: 'активный'},
  {id: SUBJECT_STATUS_TO_BE_TERMINATED, title: 'к расторжению'},
  {id: SUBJECT_STATUS_TERMINATED, title: 'расторжен'},
]

export const SUBJECT_STATUSES = [
  SUBJECT_STATUS_ACTIVE,
  SUBJECT_STATUS_TO_BE_TERMINATED,
  SUBJECT_STATUS_TERMINATED,
]

export const FILTERS = [
  {label: 'Год', field: 'year', type: 'select', options: store.state.data.years},
]


export const SUBJECT_DEFAULTS = {
  status: SUBJECT_STATUS_ACTIVE
}

export const DISCOUNTS = [4, 6, 8, 10, 15, 20, 30, 40, 50, 70, 90]

export const ContractDialog = require('./Dialog')
export const ContractList = require('./List')
