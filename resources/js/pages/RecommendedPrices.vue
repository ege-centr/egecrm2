<template lang="html">
  <div>
    <Loader v-if='loading' />
    <div class='mb-3'>
      <v-chip v-for="year in $store.state.data.years" class='pointer ml-0 mr-3'
        :class="{'primary white--text': year.value == selected_year}"
        @click='selected_year = year.value'
        :key='year.value'>{{ year.text }}</v-chip>
    </div>
    <v-card v-if='data !== null'>
      <v-card-text>
        <div class='mt-3' style='width: 300px' v-for='grade in [9, 10, 11, 14]' :key='grade'>
          <v-text-field
            hide-details
            :label="grade == 14 ? 'экстернат' : grade + ' класс'"
            v-model='data[selected_year][grade]'>
          </v-text-field>
        </div>
        <div class='text-md-center mt-5'>
          <v-btn @click='save' :loading='saving'>сохранить</v-btn>
        </div>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import Settings from '@/other/settings'

const key = 'recommended-prices'

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
      Settings.get(key, true).then(r => {
        this.data = r.data
        this.loading = false
      })
    },
    async save() {
      this.saving = true
      await Settings.set(key, this.data, true)
      this.saving = false
    }
  }
}
</script>
