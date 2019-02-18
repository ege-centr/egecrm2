<template>
  <div>
    <v-data-table
      v-if='items.length > 0'
      :items="items"
      :class='config.elevationClass'
      hide-actions
      hide-headers
    >
      <template slot='items' slot-scope="{ item, index }">
        <slot :item='item' :index='index'></slot>
      </template>
    </v-data-table>
    <NoData v-else />
  </div>
</template>

<script>
export default {
  props: ['items'],
}
</script>

