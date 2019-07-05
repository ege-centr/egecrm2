import EVENT_TYPE from '@/other/event-types'

export default {
  data() {
    return {
      dialog: false,
      saving: false,
      item: null,
      loading: true,
      edit_mode: true,
      destroying: false,

      // если указан, то вместо обновления списка
      // произойдет редирект на страницу новосозданной сущности
      redirectAfterStore: null,

      redirectAfterDestroy: null,

      // дополнительные параметры диалога
      // используются, например, чтобы открыть диалог в режиме просмотра 
      // для отчетов из ЛК ученика
      options: {},

      // Ошибки валидации
      errorMessages: {},
    }
  },

  watch: {
    errorMessages(errorMessages) {
      const result = []
      Object.entries(errorMessages).forEach(entry => {
        if (entry[0] === 'alert') {
          entry[1].forEach(message => result.push(message))
        }
      })
      if (result.length > 0) {
        this.$store.commit('message', {
          text: result.slice(0, 2).join('<br/>')
        })
      }
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
        if (_.isFunction(this.afterLoad)) {
          this.afterLoad()
        }
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
        if (_.isFunction(this.afterLoad)) {
          this.afterLoad()
        }
      })
    },

    destroy() {
      this.destroying = true
      axios.delete(apiUrl(this.API_URL, this.item.id))
      .then(r => {
        if (this.redirectAfterDestroy !== null) {
          this.$router.push({ name: this.redirectAfterDestroy })
        } else {
          this.emitUpdated(EVENT_TYPE.destroyed, this.item)
          this.dialog = false
          this.waitForDialogClose(() => this.destroying = false)
        }
      })
      .catch(e => this.errorMessages = e.response.data.errors)
      .then(() => this.destroying = false)
    },

    async storeOrUpdate() {
      this.saving = true
      this.errorMessages = {}
      if (this.item.id) {
        await axios.put(apiUrl(this.API_URL, this.item.id), this.item)
          .then(r => {
            this.item = r.data
            this.emitUpdated(EVENT_TYPE.updated, this.item)
          })
          .catch(e => this.errorMessages = e.response.data.errors)
      } else {
        await axios.post(apiUrl(this.API_URL), this.item)
          .then(r => {
            if (this.redirectAfterStore !== null) {
              return this.$router.push({ name: this.redirectAfterStore, params: { id: r.data.id }})
            }
            this.item = r.data
            this.emitUpdated(EVENT_TYPE.created, this.item)
          })
          .catch(e => this.errorMessages = e.response.data.errors)
      }
      if (this.noErrors) {
        this.dialog = false
        this.waitForDialogClose(() => this.saving = false)
      } else {
        this.saving = false
      }
    },

    emitUpdated(event, item = null) {
      this.$emit('updated', { event, item })
    },
  },

  computed: {
    noErrors() {
      return Object.keys(this.errorMessages).length === 0
    },
  }
}