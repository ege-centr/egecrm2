export const API_URL = 'groups'

export const GROUP_CLIENTS_API_URL = 'group-clients'

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

export const FILTERS = [
  {label: 'Уровень', field: 'status', type: 'select', options: LEVELS},
];

export const GroupSchedule = require('./Schedule')
