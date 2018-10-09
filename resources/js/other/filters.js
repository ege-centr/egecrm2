export default {
  'date-time': value => moment(value).format('YY.MM.DD в HH:mm'),
  date: value => moment(value).format('YY.MM.DD')
}
