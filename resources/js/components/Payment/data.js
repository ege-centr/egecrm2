export const url = 'payments'

export const enums = {
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

export const model_defaults = {
  category: 'study',
}
