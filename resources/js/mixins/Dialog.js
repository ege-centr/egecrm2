export default {
  data() {
    return {
      dialog: false,
      saving: false,
      item: null,
      edit_mode: false,
      loading: true,
      edit_mode: true,
      destroying: false,

      // дополнительные параметры диалога
      // используются, например, чтобы открыть диалог в режиме просмотра 
      // для отчетов из ЛК ученика
      options: {},
    }
  },

  methods: {
    open(item_id = null, defaults = {}, options = {}) {
      this.options = options
      if (_.isFunction(this.beforeOpen)) {
        this.beforeOpen(item_id, defaults, options)
      }
      this.item = null
      this.dialog = true
      if (item_id !== null) {
        this.edit_mode = true
        this.loadData(item_id)
      } else {
        this.edit_mode = false
        this.item = {...this.MODEL_DEFAULTS, ...defaults }
        this.loading = false
      }
      if (_.isFunction(this.afterOpen)) {
        this.afterOpen(item_id, defaults, options)
      }
    },

    async loadData(item_id) {
      this.loading = true
      await axios.get(apiUrl(this.API_URL, item_id)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    destroy() {
      this.destroying = true
      axios.delete(apiUrl(this.API_URL, this.item.id)).then(r => {
        this.emitUpdated()
        this.dialog = false
        this.waitForDialogClose(() => this.destroying = false)
      })
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(this.API_URL, this.item.id), this.item).then(r => this.item = r.data)
      } else {
        await axios.post(apiUrl(this.API_URL), this.item).then(r => this.item = r.data)
      }
      this.emitUpdated(this.item)
      this.dialog = false
      this.waitForDialogClose(() => this.saving = false)
    },

    // надо передавать с небольшой задержкой, 
    // потому что иногда подтягиваются старые данные
    // (наблюдается диссинхрон: обновление сущносить -> reload -> отдаются старые данные)
    emitUpdated(item = null) {
      this.$emit('updated', item)
    }
  }
}