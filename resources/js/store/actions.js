export default {
  // Loads static data if not loaded yet
  // fields = array
  async requireData({ commit, state }, fields) {
    const fields_not_loaded_yet = []

    fields.forEach(field => {
      if (! state.data.hasOwnProperty(field)) {
        fields_not_loaded_yet.push(field)
      }
    })

    if (fields_not_loaded_yet.length) {
      const response = await axios.post(apiUrl(`data/static`), { fields: fields_not_loaded_yet })
      fields_not_loaded_yet.forEach(field => {
        commit({
          type: 'setData',
          data: response.data[field],
          field
        })
      })
    }
  },

  // Load all users
  loadUsers({commit, state}) {
    if (! state.users) {
      axios.get(apiUrl('users')).then(response => {
        commit('setUsers', response.data)
      })
    }
  }
}
