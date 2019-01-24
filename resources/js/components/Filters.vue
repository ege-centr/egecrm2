<template>
  <div>
    <v-chip v-for='(filter, index) in filters' :key='index'>
      {{ filter.item.label }}: {{ getFilterValueLabel(filter) }}
      <v-avatar class='ma-0 close-button'>
        <v-hover>
          <v-icon slot-scope="{ hover }" class='pointer'
            @click='close(index)'
            :class="hover ? 'black--text': 'grey--text text--darken-1'">cancel</v-icon>
        </v-hover>
      </v-avatar>
    </v-chip>
    <v-menu
      bottom
      origin="center center"
      transition="scale-transition"
      :close-on-content-click='false'
      v-model="menu"
    >
      <v-chip slot='activator' class='pointer white--text' color='primary'>
        <v-avatar class='pl-2'>
          <v-icon>filter_list</v-icon>
        </v-avatar>
        Добавить фильтр
      </v-chip>

      <v-list dense v-if='item === null'>
        <v-list-tile v-for='(item, index) in items' @click='select(item)' :key='index'>
          <v-list-tile-title>{{ item.label }}</v-list-tile-title>
        </v-list-tile>
      </v-list>

      <v-card style='width: 300px' v-else>
        <v-card-text>
          <!-- <div class='subheading'>
            {{ item.label }}
          </div> -->
          <div v-if="item.type === 'select' || item.type === 'multiple'">
            <v-select
              v-model="value"
              :multiple="item.type === 'multiple'"
              :items="item.options"
              :label="item.label"
              :item-value="getItemValue(item)"
              :item-text="getItemText(item)"
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
            <DatePicker label='Дата' @updated='(v) => value = v' />
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" small flat @click='back'>назад</v-btn>
          <v-btn color="blue darken-1" small flat :disabled='!value' @click='apply'>Применить</v-btn>
        </v-card-actions>
      </v-card>
    </v-menu>
  </div>
</template>

<script>

import DatePicker from '@/components/UI/DatePicker'

export default {
  props: {
    // Опции выбора фильтров
    items: {
      type: Array,
      default: () => [],
      required: true,
    },

    // Предустановленные фильтры
    // Использование: this.pre_installed_filters.push({item: this.filters[3], value: [group.grade_id]})
    // TODO: сделать удобнеее {filter_index: value}
    preInstalled: {
      type: Array,
      default: () => [],
      required: false,
    },
  },

  components: { DatePicker },

  created() {
    if (this.preInstalled.length) {
      this.filters = clone(this.preInstalled)
      this.emit()
    }
  },

  data() {
    return {
      filters: [],
      item: null,
      value: null,
      menu: false
    }
  },

  watch: {
    menu(newVal, oldVal) {
      if (newVal === false && oldVal === true) {
        setTimeout(() => this.back(), 500)
      }
    }
  },

  methods: {
    // 1 стадия выбора фильтра
    select(item) {
      this.item = clone(item)
      // проверяем, было ли выбрано значение ранее
      if (this.previouslySelectedFilter !== undefined) {
        this.value = this.previouslySelectedFilter.value
      }
    },

     // 2 стадия выбора фильтра – выбрать и применить
    apply() {
      const item = {
        item: this.item,
        value: this.value
      }
      if (this.previouslySelectedFilter === undefined) {
        this.filters.push(item)
      } else {
        this.previouslySelectedFilter = item
      }
      this.emit()
      this.menu = false
    },

    back() {
      this.item = null
      this.value = null
    },

    close(index) {
      this.filters.splice(index, 1)
      this.emit()
    },

    emit() {
      const filters = {}
      this.filters.forEach(e => {
        filters[e.item.field] = e.value
      })
      this.$emit('updated', filters)
    },

    getFilterValueLabel(filter) {
      switch(filter.item.type) {
        case 'select':
          return _.get(
            filter.item.options.find(e => e[this.getItemValue(filter.item)] == filter.value), 
            this.getItemText(filter.item)
          )
          // const label = []
          // filter.value.forEach(v => {
          //  label.push(filter.item.options.find(e => e[this.getItemValue(filter.item)] == v)[this.getItemText(filter.item)])
          // })
          // return label.join(', ')
        case 'multiple':
          const label = []
          if (Array.isArray(filter.value)) {
            filter.value.forEach(v => {
              label.push(_.get(
                filter.item.options.find(e => e[this.getItemValue(filter.item)] === v), 
                this.getItemText(filter.item)
              ))
            })
          }
          return label.join(', ')
        case 'date':
          return this.$options.filters.date(filter.value)
        default:
          return filter.value
      }
    },

    getItemValue(item) {
      return item.valueField ? item.valueField : 'id'
    },

    getItemText(item) {
      return item.textField ? item.textField : 'title'
    },
  },

  computed: {
    previouslySelectedFilter: {
      get() {
        return this.filters.find(e => e.item.field === this.item.field)
      },
      set(newValue) {
        this.filters[this.filters.findIndex(e => e.item.field === this.item.field)] = newValue
      }
    },
  }
}
</script>

<style lang='scss'>
  .close-button {
    & i {
      font-size: 20px;
      height: 20px !important;
      width: 20px !important;
      left: 10px;
      position: relative;
    }
  }
</style>
