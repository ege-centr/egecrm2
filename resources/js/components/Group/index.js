export const API_URL = 'groups'
export const GROUP_CLIENTS_API_URL = 'group-clients'
export const GROUP_ACTS_API_URL = 'group-acts'

// export const FILTERS = [
//   {label: 'Ответственный', field: 'responsible_admin_id', type: 'select', options: store.state.data.admins, valueField: 'id', textField: 'name'}
// ]

export const model_defaults = {
  is_ready_to_start: false,
  is_archived: false,
  clients: [],
  lessons: []
}

export const LEVELS = [
  {text: 'низкий', value: 'low'},
  {text: 'средний', value: 'mid'},
  {text: 'высокий', value: 'high'},
  {text: 'спец. группа', value: 'special'}
]

export const GroupSchedule = require('./Schedule')
export const GroupList = require('./List')
export const GroupDialog = require('./Dialog')
export const MoveClientDialog = require('./MoveClientDialog')
export const GroupActList = require('./Act/List')
export const GroupActDialog = require('./Act/Dialog')

export const asd = ''