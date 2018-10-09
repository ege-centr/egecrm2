import Loader from '@/components/UI/Loader'

export const GlobalPlugin = {
  install(Vue, options) {
    Vue.prototype.getData = function(field, id) {
      return this.$store.state.data[field].find(e => e.id == id)
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
  }
}
