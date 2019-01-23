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
  {label: 'Метод', field: 'methods', type: 'select', options: ENUMS.methods},
  {label: 'Категория', field: 'category', type: 'select', options: ENUMS.categories},
]

export const SORT = [
  {field: 'created_at', type: 'desc', label: 'по дате проводки', selected: true},
  {field: 'date', type: 'desc', label: 'по дате', selected: false},
]

export const MODEL_DEFAULTS = {}

export const PaymentDialog = require('./Dialog')
export const PaymentList = require('./List')
