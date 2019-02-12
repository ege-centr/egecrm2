import store from '@/store'
import { ROLES } from '@/config'

export const API_URL = 'payments'

export const ENUMS = {
  methods: [
    {title: 'карта', id: 'card'},
    {title: 'наличные', id: 'cash'},
    {title: 'счет', id: 'bill'},
    {title: 'карта онлайн', id: 'card_online'}
  ],
  types: [
    {title: 'платеж', id: 'payment'},
    {title: 'возврат', id: 'return'}
  ],
  categories: [
    {title: 'обучение', id: 'study'},
    {title: 'профориентация', id: 'career_guidance'},
    {title: 'пробный ЕГЭ', id: 'ege_trial'}
  ]
}

export const FILTERS = [
  {label: 'Тип', field: 'type', type: 'multiple', options: ENUMS.types},
  {label: 'Метод', field: 'method', type: 'multiple', options: ENUMS.methods},
  {label: 'Год', field: 'year', type: 'multiple', options: store.state.data.years},
  {label: 'Категория', field: 'category', type: 'multiple', options: ENUMS.categories},
  {label: 'От кого', field: 'entity_type', type: 'multiple', options: [
    {id: ROLES.TEACHER, title: 'преподаватель'},
    {id: ROLES.CLIENT, title: 'клиент'},
  ]},
  {label: 'Пользователь', field: 'created_admin_id', type: 'select', options: store.state.data.admins, textField: 'name'},
  {label: 'Дата', field: 'date', type: 'date'},
]

export const SORT = [
  {field: 'created_at', type: 'desc', label: 'по дате проводки', selected: true},
  {field: 'date', type: 'desc', label: 'по дате', selected: false},
]

export const MODEL_DEFAULTS = {}

export const PaymentDialog = require('./Dialog')
export const PaymentList = require('./List')
