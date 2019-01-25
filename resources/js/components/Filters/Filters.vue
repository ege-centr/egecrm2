<template>
  <div>
    <v-menu v-for='(filter, index) in filters' :key='filter.item.field'
      bottom
      origin="center center"
      transition="scale-transition"
      :close-on-content-click='false'
      v-model="used_filter_menu[filter.item.field]"
    >
      <v-chip slot='activator'>
        {{ filter.item.label }}: {{ getFilterValueLabel(filter) }}
        <v-avatar class='ma-0 close-button'>
          <v-hover>
            <v-icon slot-scope="{ hover }" class='pointer'
              @click='close(index)'
              :class="hover ? 'black--text': 'grey--text text--darken-1'">cancel</v-icon>
          </v-hover>
        </v-avatar>
      </v-chip>

      <SelectFilterDialog 
        v-if='used_filter_menu[filter.item.field]' 
        :item='filter.item' 
        :filter-value='filter.value' 
        @selected='apply' />
    </v-menu>

    <v-menu
      v-show='notUsedFilters.length'
      bottom
      origin="center center"
      transition="scale-transition"
      :close-on-content-click='false'
      v-model="menu"
    >
      <v-chip slot='activator' class='pointer white--text' color='primary' @click='item = null'>
        <v-avatar class='pl-2'>
          <v-icon>filter_list</v-icon>
        </v-avatar>
        Добавить фильтр
      </v-chip>

      <v-list dense v-if='item === null'>
        <v-list-tile v-for='(item, index) in notUsedFilters' @click='select(item)' :key='index'>
          <v-list-tile-title>{{ item.label }}</v-list-tile-title>
        </v-list-tile>
      </v-list>

      <SelectFilterDialog :item='item' @selected='apply' @back='item = null' v-else />
    </v-menu>
  </div>
</template>

<script>

import SelectFilterDialog from './SelectFilterDialog'

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

  components: { SelectFilterDialog },

  created() {
    if (this.preInstalled.length) {
      this.filters = clone(this.preInstalled)
      this.emit()
    }
  },

  data() {
    return {
      filters: [],
      // выбранный фильтр
      item: null,
      value: null,
      menu: false,
      used_filter_menu: {},
    }
  },

  // watch: {
  //   menu(newVal, oldVal) {
  //     if (newVal === false && oldVal === true) {
  //       setTimeout(() => this.back(), 500)
  //     }
  //   }
  // },

  methods: {
    // 1 стадия выбора фильтра
    select(item) {
      this.item = clone(item)
    },

     // 2 стадия выбора фильтра – выбрать и применить
    apply(item) {
      // был ли фильтр выбран ранее?
      const index = this.filters.findIndex(e => e.item.field === item.item.field)
      colorLog("Index " + index)
      if (index === -1) {
        this.filters.push(item)
      } else {
        this.filters.splice(index, 1, item)
      }
      this.emit()
      this.menu = false
      this.used_filter_menu = {}
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
    notUsedFilters() {
      const not_used_filters = []
      this.items.forEach(item => {
        let filter_used = false
        this.filters.forEach(filter => {
          if (filter_used) {
            return
          }
          if (filter.item.field === item.field) {
            filter_used = true
          }
        })
        if (!filter_used) {
          not_used_filters.push(item)
        }
      })
      return not_used_filters
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
