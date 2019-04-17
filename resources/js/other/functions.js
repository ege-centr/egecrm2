import store from '@/store'

// Year tabs with data
export const tabsWithData = function(items) {
  return store.state.data.years.filter(d => {
    return items.findIndex(e => e.year === d.id) !== -1
  })
}

export const objectToOptionsArray = function(obj) {
  const result = []
  Object.entries(obj).forEach(e => result.push({
    value: Number(e[0]),
    text: e[1]
  }))
  return result
}