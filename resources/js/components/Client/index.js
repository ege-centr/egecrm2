import store from '@/store'

export const API_URL = 'clients'

export const CLASS_NAME = 'Client\\Client'

export const FILTERS = [
  {label: 'Класс', field: 'grade_id', type: 'multiple', options: store.state.data.grades},
  {label: 'Текущий класс', field: 'current_grade_id', type: 'multiple', options: store.state.data.grades},
]

export const MODEL_DEFAULTS = {
  contracts: [],
  representative: {
    email: {},
    phones: [{phone: '', comment: ''}] 
  },
  email: {},
  phones: [{phone: '', comment: ''}]
}

export const ClientMap = require('./Map')
export const ClientList = require('./List')
export const ClientDialog = require('./Dialog')
export const GroupNotAssignedList = require('./GroupNotAssignedList')