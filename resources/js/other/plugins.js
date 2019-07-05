import Loader from '@/components/UI/Loader'
import Avatar from '@/components/UI/Avatar'
import BgAvatar from '@/components/UI/BgAvatar'
import AddBtn from '@/components/UI/AddBtn'
import AddBtnAnimated from '@/components/UI/AddBtnAnimated'
import NoData from '@/components/UI/NoData'
import DataTable from '@/components/UI/DataTable'
import PersonName from '@/components/UI/PersonName'
import ClearableSelect from '@/components/UI/ClearableSelect'
import SubjectGrade from '@/components/UI/SubjectGrade'
import Credentials from '@/components/UI/Credentials'
import Placeholder from '@/components/UI/Placeholder'
import YearTabs from '@/components/UI/YearTabs'
import TitleCredentials from '@/components/UI/TitleCredentials'
import DivBlocker from '@/components/UI/DivBlocker'
import USER_TYPES from '@/other/user-types'

export const GlobalPlugin = {
  install(Vue, options) {
    Vue.prototype.getData = function(field, id = null) {
      const data = this.$store.state.data[field]
      if (id !== null) {
        return data.find(e => e.id == id)
      }
      return data
    }

    Vue.prototype.config = {
      elevationClass: 'elevation-3',
    }

    Vue.prototype._ = window._

    Vue.prototype.waitForDialogClose = (f) => {
      Vue.nextTick(() => f(), 200)
    }

    Vue.prototype.userTypes = USER_TYPES

    Vue.prototype.isAdmin = function() {
      return this.$store.state.user.entity_type === USER_TYPES.admin  
    }

    const pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
      cluster: 'eu'
    })

    Vue.prototype.pusher = pusher.subscribe('app')

    Vue.prototype.pusher.on = (event, callback) => {
      Vue.prototype.pusher.bind("App\\Events\\" + event, callback)
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
    Vue.component('BgAvatar', BgAvatar)
    Vue.component('ClearableSelect', ClearableSelect)
    Vue.component('NoData', NoData)
    Vue.component('PersonName', PersonName)
    Vue.component('AddBtnAnimated', AddBtnAnimated)
    Vue.component('DataTable', DataTable)
    Vue.component('Credentials', Credentials)
    Vue.component('SubjectGrade', SubjectGrade)
    Vue.component('Placeholder', Placeholder)
    Vue.component('YearTabs', YearTabs)
    Vue.component('TitleCredentials', TitleCredentials)
    Vue.component('DivBlocker', DivBlocker)
  }
}
