export default {
  data() {
    return {
      dialog: false,
      loading: false
    }
  },
  methods: {
    async storeOrUpdate() {
      this.loading = true
      if (this.item.id) {
        await axios.put(apiUrl(`${this.url}/${this.item.id}`), this.item)
      } else {
        await axios.post(apiUrl(this.url), this.item)
      }
      this.$emit('saved')
      this.loading = false
      this.dialog = false
    }
  }
}
