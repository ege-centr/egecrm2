export default {
  'date-time': value => moment(value).format('DD.MM.YY в HH:mm'),
  date: value => moment(value).format('DD.MM.YY'),
  year: (value, year) => value.filter(e => e.year == year),
}
