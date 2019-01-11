import Loader from '@/components/UI/Loader'
import Avatar from '@/components/UI/Avatar'
import AddBtn from '@/components/UI/AddBtn'

export const GlobalPlugin = {
  install(Vue, options) {
    Vue.prototype.getData = function(field, id) {
      return this.$store.state.data[field].find(e => e.id == id)
    }

    Vue.prototype.config = {
      elevationClass: 'elevation-3',
    }

    Vue.prototype.withNullOption = function(items, value = 'value', text = 'text') {
      return [
        {[value]: null, [text]: 'не установлено'},
        ...items
      ]
    }

    // TODO: замалым
    Vue.prototype.withNullOption2 = function(items) {
      return [
        {id: null, names: {abbreviation: 'не установлено'}},
        ...items
      ]
    }

    // Vue.prototype.toggleEnum = function(obj, field, statuses) {
    //   let status = statuses.indexOf(obj[field])
    //   console.log('from', statuses[status], status)
    //   status++
    //   if (status >= statuses.length) {
    //     status = 0
    //   }
    //   console.log('to', statuses[status], status)
    //   Vue.set(obj, field, statuses[status])
    // }

    Vue.component('Loader', Loader)
    Vue.component('Avatar', Avatar)
    Vue.component('AddBtn', AddBtn)
  }
}
