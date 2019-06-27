const today = moment()
const dateFormat = 'YYYY-MM-DD'

export default class PaymentsAutofill {
  constructor(paymentCount, lessonCount, discountedSum) {
    this.paymentCount = paymentCount
    this.lessonCount = lessonCount
    this.year = Number(today.format('YYYY'))
    
    this.pricePerLesson = Math.round(discountedSum / lessonCount)
    this.lessonsPerPayment = Math.floor(lessonCount / paymentCount)
    this.remainder = lessonCount % paymentCount
  }

  getPayments() {
    if (this.paymentCount > 0) {
      // массив дат платежей, начиная со второго платежа
      // (первый платеж всегда "сегодня" – в день платежа)
      // если paymentDats остался null, значит, не подошло 
      // ни одно из условий и ссылку нужно скрывать
      let paymentDates = null
  
      switch(this.paymentCount) {
        case 1: 
          paymentDates = []
          break
  
        case 2:
          switch(Number(today.format('M'))) {
            case 12: 
              paymentDates = [`${this.year + 1}-03-14`]
              break
            case 1: 
              paymentDates = [`${this.year + 1}-03-21`]
              break
          }
          break
  
        case 3:
          if (this.todayIsBetween('04-01', '08-09')) {
            paymentDates = [`${this.year}-11-14`, `${this.year + 1}-02-14`]
          } else if (this.todayIsBetween('08-10', '09-07')) {
            paymentDates = [`${this.year}-11-21`, `${this.year + 1}-02-21`]
          } else if (this.todayIsBetween('09-08', '09-25')) {
            paymentDates = [`${this.year}-11-28`, `${this.year + 1}-02-28`]
          } else if (this.todayIsBetween('10-16', '10-31')) {
            paymentDates = [`${this.year}-12-14`, `${this.year + 1}-03-14`]
          } else if (this.todayIsBetween('11-01', '11-31')) {
            paymentDates = [`${this.year}-12-21`, `${this.year + 1}-03-21`]
          }
          break
        
        // 4 и более
        default:
            if (this.todayIsBetween('04-01', '09-15')) {
              paymentDates = []
              const date = moment(`${this.year}-09-15`)
              _.times(this.paymentCount - 1, () => {
                paymentDates.push(date.add(1, 'month').format(dateFormat))
              })
            } else {
              paymentDates = []
              _.times(this.paymentCount - 1, (n) => {
                paymentDates.push(today.clone().add(n + 1, 'month').format(dateFormat))
              })
            }
      }
  
      if (paymentDates !== null) {
        // добавляем нынчашнюю дату. первый платеж всегда нынче
        paymentDates.unshift(today.format(dateFormat))
  
        return paymentDates.map((date, index) => {
          return {
            date,
            ...this.getSumAndLessons(index)
          }
        })
      }
    }

    // если не прошло ни по одному из условий,
    // возвращаем null и скрываем ссылку
    return null
  }

  getSumAndLessons(index) {
    index = index + 1
    const lessons = this.remainder < index ? this.lessonsPerPayment : this.lessonsPerPayment + 1
    return {
      lessons,
      sum: lessons * this.pricePerLesson,
    }
  }

  todayIsBetween(start, end) {
    const date = today.format('MM-DD')
    return (date >= start && date <= end)
  }
}