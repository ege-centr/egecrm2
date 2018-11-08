export const API_URL = 'clients'

export const CLASS_NAME = 'Client\\Client'

export const MODEL_DEFAULTS = {
  markers: [],
  contracts: [],
  passport: {},
  email: {},
  phones: [{phone: '', comment: ''}]
}

export const ClientMap = require('./Map')
export const GroupNotAssignedList = require('./GroupNotAssignedList')
