<template lang="html">
  <div>
    <Loader v-if='loading' />
    <div class='mb-5'>
      <v-chip v-for="year in $store.state.data.years" class='pointer ml-0 mr-3'
        :class="{'primary white--text': year.value == selected_year}"
        @click='selected_year = year.value'
        :key='year.value'>{{ year.text }}</v-chip>
    </div>
    <div v-if='data !== null'>
      <div class='mt-3' style='width: 300px' v-for='grade in [9, 10, 11, 14]' :key='grade'>
          <v-text-field
            hide-details
            :label="getData('grades', grade).title"
            v-model='data[selected_year][grade]'></v-text-field>
      </div>
    </div>
    <div class='text-md-center mt-5'>
      <v-btn @click='save' :loading='saving'>сохранить</v-btn>
    </div>
  </div>
</template>

<script>

const url = 'settings'
const settings_key = 'recommended-prices'

export default {
  data() {
    return {
      // TODO: this.$store.state.data.years.slice(-1).value
      selected_year: 2018,
      data: null,
      loading: true,
      saving: false
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      axios.get(apiUrl(`${url}?key=${settings_key}&json=1`)).then(r => {
        this.data = r.data
        this.loading = false
      })
    },
    async save() {
      this.saving = true
      await axios.post(apiUrl(url), {
        key: settings_key,
        value: this.data,
        json: true
      })
      this.saving = false
    }
  }
}
</script>
