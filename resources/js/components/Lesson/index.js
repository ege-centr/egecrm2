export const API_URL = 'lessons'

export const CLIENT_LESSONS_API_URL = 'client-lessons'

export const LESSON_STATUS = {
  PLANNED: 'planned',
  CANCELLED: 'cancelled',
  CONDUCTED: 'conducted',
}

export function  indexSkippingCancelledLessons(index, items) {
  const cancelled_lessons_count = _.chain(items).sortBy('date').take(index + 1).filter(e => e.status === LESSON_STATUS.CANCELLED).value().length
  return index + 1 - cancelled_lessons_count
}