<template>
  <v-card style='width: 300px'>
    <v-card-text>
      <!-- <div class='subheading'>
        {{ item.label }}
      </div> -->
      <!-- <div v-if="item.type === 'select'">
        <v-list dense>
          <v-list-tile @click='' v-for='(option, index) in item.options' :key='index'>
            <v-list-tile-title>{{ option[item.textField ? item.textField : 'title'] }}</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </div> -->
      <div v-if="item.type === 'multiple' || item.type === 'select'">
        <v-select
          v-model="value"
          :multiple="item.type === 'multiple'"
          :items="item.options"
          :label="item.label"
          :item-value="item.valueField ? item.valueField : 'id'"
          :item-text="item.textField ? item.textField : 'title'"
          hide-details
        ></v-select>
      </div>
      <div v-if="item.type === 'text'">
        <v-text-field
          v-model="value"
          :label="item.label"
          hide-details
          @keyup.enter='apply'
        ></v-text-field>
      </div>
      <div v-if="item.type === 'date'">
        <DatePicker label='Дата' v-model='value' />
      </div>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="grey" v-if='filterValue === null' small flat @click='back'>назад</v-btn>
      <v-btn color="blue darken-1" small flat :disabled='!value' @click='apply'>Применить</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>

import DatePicker from '@/components/UI/DatePicker'

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

  components: { DatePicker },

  data() {
    return {
      value: null,
    }
  },

  created() {
    if (this.filterValue !== null) {
      this.value = this.filterValue
    }
  },
  
  methods: {
    apply() {
      this.$emit('selected', _.pick(this, ['item', 'value']))
      this.value = null
    },

    back() {
      this.$emit('back')
      this.value = null
    },
  }
}
</script>
