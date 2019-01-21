<template>
  <div style='display: inline-block; cursor: pointer'>
    <v-btn fab dark small color="red" class='ml-0'>
      <v-icon dark>add</v-icon>
    </v-btn>
    <span v-if='label !== null'>{{ label }}</span>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: null,
      required: false,
    }
  }
}
</script>

