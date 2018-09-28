export default {
  // Load initial data
  async loadInitial({ commit, state }) {
    await axios.get(apiUrl('load-initial')).then(response => {
      Object.entries(response.data).forEach(entry => {
        commit({
          type: 'setData',
          field: entry[0],
          data: entry[1]
        })
      })
    })
  }
}
