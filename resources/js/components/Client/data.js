export const API_URL = 'clients'

export const CLASS_NAME = 'Client\\Client'

export const FILTERS = [
  {label: 'Имя', field: 'name', type: 'text'}
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
export const GroupNotAssignedList = require('./GroupNotAssignedList')
