export const IP_MODEL_DEFAULTS = {
  ip_from: '',
  ip_to: '',
  confirm_by_sms: false,
}

export const MODEL_DEFAULTS = {
  rights: [],
  email: {},
  photo: null,
  ips: [IP_MODEL_DEFAULTS],
  phones: [{phone: '', comment: ''}]
}

export const FILTERS = [
  {label: 'Имя', field: 'name', type: 'text'},
]

export const API_URL = 'admins'

export const CLASS_NAME = 'Admin\\Admin'

export const AdminList = require('./List')
export const AdminDialog = require('./Dialog')