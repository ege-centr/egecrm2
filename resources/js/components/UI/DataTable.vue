<template>
  <v-data-table
    :items="items"
    :class="{
      [config.elevationClass]: noElevation !== ''
    }"
    hide-actions
    hide-headers
  >
    <template slot='items' slot-scope="{ item, index }">
      <slot :item='item' :index='index'></slot>
    </template>
    <template slot='no-data'>
      <NoData square :height='200' />
    </template>
    <template slot='footer' v-if='$slots.footer'>
      <slot name='footer'></slot>
    </template>
  </v-data-table>
</template>

<script>
export default {
  
  props: {
    items: {
      type: Array,
      required: true,
    },

    // no elevation – таблица без тени
    noElevation: {
      type: String,
      required: false,
    }
  }
}
</script>

