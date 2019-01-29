export default {
  'date-time': value => moment(value).format('DD.MM.YY в HH:mm'),
  'day-month': value => moment(value).format('DD.MM'),
  date: value => moment(value).format('DD.MM.YY'),
  year: (value, year) => value.filter(e => e.year == year),
}
