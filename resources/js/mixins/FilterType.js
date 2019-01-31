export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
    filterValue: {
      required: false,
      default: null,
    },
  },

  data() {
    return {
      value: null,
      applyEnabled: false,
    }
  },

  created() {
    if (this.filterValue !== null) {
      this.value = clone(this.filterValue)
    }
  },

  methods: {
    apply() {
      this.$emit('selected', _.pick(this, ['item', 'value']))
      // this.value = _.isArray(this.value) ? [] : null
    },
  },

  computed: {
    titleField() {
      return this.item.textField || 'title'
    },

    idField() {
      return this.item.valueField || 'id'
    },
  }
}