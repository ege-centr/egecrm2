export default {
  computed: {
    open(itemId = null, defaults = {}) {
      return this.$refs.DialogForm.open(itemId, defaults)
    },

    item() {
      return this.$refs.DialogForm.item
    }
  }
}