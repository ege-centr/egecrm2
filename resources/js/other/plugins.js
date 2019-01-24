import Loader from '@/components/UI/Loader'
import Avatar from '@/components/UI/Avatar'
import AddBtn from '@/components/UI/AddBtn'
import ClearableSelect from '@/components/UI/ClearableSelect'

export const GlobalPlugin = {
  install(Vue, options) {
    Vue.prototype.getData = function(field, id) {
      return this.$store.state.data[field].find(e => e.id == id)
    }

    Vue.prototype.config = {
      elevationClass: 'elevation-3',
    }

    Vue.prototype.waitForDialogClose = (f) => {
      Vue.nextTick(() => f(), 200)
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
    Vue.component('ClearableSelect', ClearableSelect)
  }
}
