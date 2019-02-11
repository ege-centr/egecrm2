import store from '@/store'
import router from '@/router'

const API_URL = 'preview'
const LOCAL_STORAGE_KEY = 'preview_return_url'

export default class Preview {
  static login(entity_id, class_name) {
    axios.get(apiUrl(API_URL + queryString({ 
      entity_id, 
      class_name,
    }))).then(r => {
      store.commit('setUser', r.data)
      router.push({name: 'GroupIndex'})
      localStorage.setItem(LOCAL_STORAGE_KEY, window.location.pathname)
    })
  }

  static exit() {
    axios.get(apiUrl(API_URL, 'exit')).then(r => {
      store.commit('setUser', r.data)
      router.push({path: localStorage.getItem(LOCAL_STORAGE_KEY)})
    })
  }
}