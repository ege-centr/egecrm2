import Loader from '@/components/UI/Loader'

export const GlobalPlugin = {
  install(Vue, options) {
    Vue.prototype.getData = function(field, id) {
      return this.$store.state.data[field].find(e => e.id == id)
    }

    Vue.component('Loader', Loader)
  }
}
