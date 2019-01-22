export const MODEL_DEFAULTS = {
  rights: [],
  email: {},
  photo: null,
  ips: [{}],
  phones: [{phone: '', comment: ''}]
}

export const API_URL = 'admins'

export const CLASS_NAME = 'Admin\\Admin'

export const AdminList = require('./List')
export const AdminDialog = require('./Dialog')