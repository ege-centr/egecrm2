import store from '@/store'

// Year tabs with data
export const tabsWithData = function(items) {
  return store.state.data.years.filter(d => {
    return items.findIndex(e => e.year === d.id) !== -1
  })
}

export const objectToOptionsArray = function(obj, valueField = 'value', textField = 'text') {
  const result = []
  Object.entries(obj).forEach(e => result.push({
    [valueField]: Number(e[0]),
    [textField]: e[1]
  }))
  return result
}