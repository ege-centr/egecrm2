export const API_URL = 'payments'

export const ENUMS = {
  methods: [
    {text: 'карта', value: 'card'},
    {text: 'наличные', value: 'cash'},
    {text: 'счет', value: 'bill'},
    {text: 'карта онлайн', value: 'card_online'}
  ],
  types: [
    {text: 'платеж', value: 'payment'},
    {text: 'возврат', value: 'return'}
  ],
  categories: [
    {text: 'обучение', value: 'study'},
    {text: 'профориентация', value: 'career_guidance'},
    {text: 'пробный ЕГЭ', value: 'ege_trial'}
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
export const PaymentList = require('./ListNew')
