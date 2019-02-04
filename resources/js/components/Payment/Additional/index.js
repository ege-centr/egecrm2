import store from '@/store'

export const API_URL = 'payment-additionals'

export const MODEL_DEFAULTS = {
  year: store.state.data.academic_year,
}

export const PaymentAdditionalList = require('./List')
export const PaymentAdditionalDialog = require('./Dialog')