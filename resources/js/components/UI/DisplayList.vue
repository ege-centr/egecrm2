<template>
  <div>
    <component :is='dialogComponent' ref='Dialog' @updated="$emit('updated')" />
    <AddBtn v-if='addBtn && items.length > 0' :label='addBtnLabel' animated absolute @click.native='add' />
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
      v-if='items.length > 0'
    >
      <template slot='items' slot-scope="{ item, index }">
        <slot name='item' :item='item' :index='index'></slot>
      </template>
    </v-data-table>
    <NoData
      v-else
      :add='add'
      :height='300'
      :class='config.elevationClass'
      style='border-radius: 0'
    />
  </div>
</template>



<script>
export default {
  props: {
    items: {
      type: Array,
      required: true
    },

    // oтображать кнопку "добавить"?
    addBtn: {
      type: Boolean,
      default: true,
    },

    addBtnLabel: {
      type: String,
      default: 'добавить',
    },

    dialogComponent: {
      type: Object,
      required: true,
    },

    modelDefaults: {
      type: Object,
      required: false,
      default: () => {},
    }
  },

  methods: {
    add() {
      this.Dialog.open(null, this.modelDefaults)
    },

    edit(id) {
      this.Dialog.open(id)
    },
  },

  computed: {
    Dialog() {
      return this.$refs.Dialog
    },
  }
}
</script>
