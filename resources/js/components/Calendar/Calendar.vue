<template>
  <div class='calendar'>
    <div v-for='month in CALENDAR_MONTHS'>
      <div class='calendar__month-title font-weight-bold'>{{ month_labels[month] }} {{ getYear(month) }}</div>
      <table class='calendar__table'>
        <thead>
          <tr>
            <td v-for='week_day in week_days' class='grey--text font-weight-medium'>
              {{ week_day.label }}
            </td>
          </tr>
        </thead>
        <tbody>
          <tr v-for='days_by_weeks in days_by_months_and_weeks[month]'>
            <td v-for='day in days_by_weeks' class='font-weight-medium calendar-day' :title="hasSpecial(day, 'exam') ? 'экзамен' : (hasSpecial(day, 'vacation') ? 'праздник' : '')"
              :class="{
                'calendar-day_active calendar-day_has-lesson-conducted': lessonCount(day, true) > 0,
                'calendar-day_has-lesson-conducted_multiple': lessonCount(day, true) > 1,
                'calendar-day_active calendar-day_has-lesson-planned': lessonCount(day, false) > 0,
                'calendar-day_has-lesson-planned_multiple': lessonCount(day, false) > 1,
                'calendar-day_has-multiple': lessonCount(day) > 1,
                'red--text font-weight-bold': hasSpecial(day, 'vacation'), // праздник
                'calendar-day_active calendar-day_has-exam': hasSpecial(day, 'exam'),
                'calendar-day_active calendar-day_has-current-exam': hasCurrentExam(day),
              }">
              <span v-if='day !== null'>{{ day.getDate() }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>

import { CALENDAR_MONTHS } from './data'

export default {
  props: {
    year: {
      type: Number,
      required: true,
    },
    lessons: {
      type: Array,
      default() {
        return []
      },
      required: false,
    },
    specialDates: {
      type: Array,
      default() {
        return []
      },
      required: false,
    },
    withSpecialDates: {
      type: Boolean,
      required: false,
      default: false,
    },
    group: {
      default: null,
      required: false
    },
  },

  data() {
    return {
      // все дни в году, разбитые по месяцам и неделям
      CALENDAR_MONTHS,
      days_by_months_and_weeks: {},
      week_days: [
        {id: 1, label: 'ПН'},
        {id: 2, label: 'ВТ'},
        {id: 3, label: 'СР'},
        {id: 4, label: 'ЧТ'},
        {id: 5, label: 'ПТ'},
        {id: 6, label: 'СБ'},
        {id: 0, label: 'ВС'},
      ],
      month_labels: {
        1: 'Январь',
        2: 'Февраль',
        3: 'Март',
        4: 'Апрель',
        5: 'Май',
        6: 'Июнь',
        7: 'Июль',
        8: 'Август',
        9: 'Сентябрь',
        10: 'Октябрь',
        11: 'Ноябрь',
        12: 'Декабрь',
      },
    }
  },

  created() {
    this.getDaysByMonthsAndWeeks()
    if (this.withSpecialDates) {
      this.loadSpecialDates()
    }
  },

  methods: {
    loadSpecialDates() {
      axios.get(apiUrl('special-dates')).then(r => {
        this.specialDates = r.data
      })
    },

    getDaysByMonthsAndWeeks() {
      CALENDAR_MONTHS.forEach(m => {
        const month_date = moment(`${this.getYear(m)}-${m}`, 'YYYY-MM')
        const month = month_date.format('M')
        const days_in_month = []
        this.days_by_months_and_weeks[month] = []
        _.times(month_date.daysInMonth(), () => {
          // days_in_month.push(month.toDate())
          this.days_by_months_and_weeks[month].push(month_date.toDate())
          month_date.add(1, 'day')
        })

        // prepend empty (сколько пустых значений вставить, чтобы начиналось с ПН)
        let prepend_times
        let first_day = this.days_by_months_and_weeks[month][0].getDay()
        if (first_day == 0) {
          prepend_times = 6
        } else {
          prepend_times = first_day - 1
        }
        _.times(prepend_times, () => this.days_by_months_and_weeks[month].unshift(null))
        this.days_by_months_and_weeks[month] = _.chunk(this.days_by_months_and_weeks[month], 7)
      })
    },

    getYear(month) {
      return month < 7 ? this.year + 1 : this.year
    },

    lessonCount(day, is_conducted = null) {
      const date = moment(day).format('YYYY-MM-DD')
      return this.lessons.filter(e => e.date === date && !e.is_cancelled && (is_conducted === null || e.is_conducted === is_conducted)).length
    },

    hasSpecial(day, type = null) {
      return this.specialDates.findIndex(e => (e.date === moment(day).format('YYYY-MM-DD') && (type === null || e.type === type))) !== -1
    },

    hasCurrentExam(day) {
      if (this.group !== null) {
        const date = moment(day).format('YYYY-MM-DD')
        return this.specialDates.findIndex(e =>
          e.date === date &&
          e.type === 'exam' &&
          e.grade_id === this.group.grade_id
          && e.subject_id === this.group.subject_id
        ) !== -1
      }
      return false
    }
  }
}
</script>

<style lang="scss" scoped>
  .calendar {
    width: 270px;

    &__month-title {
      text-align: center;
    }

    &__table {
      width: 100%;
      margin: 10px 0 50px;
      font-size: 12px;
      & tr {
        & td {
          $size: 30px;
          height: $size;
          width: $size;
          text-align: center;
          line-height: 14px;
          &.calendar-day {
            &_active {
              & span {
                height: $size;
                width: $size;
                display: inline-block;
                padding: 8px;
                border-radius: 50%;
                position: relative;
              }
            }
            &_has-lesson-planned {
              & span {
                background: #BBDEFB;
              }
              &_multiple, &.calendar-day_has-multiple {
                & span {
                  background: #64B5F6 !important;
                }
              }
            }
            &_has-lesson-conducted {
              & span {
                background: #C8E6C9;
              }
              &_multiple {
                & span {
                  background: #81C784;
                }
              }
            }
            &_has-exam {
              & span {
                color: #9E9D24;
              }
            }
            &_has-vacation {
              & span {
                background: #d02200;
              }
            }
            &_has-current-exam {
              & span {
                background: #E6EE9C;
                color: #9E9D24;
              }
            }
          }
        }
      }
    }
  }
</style>
