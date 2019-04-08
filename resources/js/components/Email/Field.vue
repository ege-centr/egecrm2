<template>
  <div>
    <v-text-field v-model="entity.email.email" :label="label" hide-details></v-text-field>
  </div>
</template>

<script>

export default {
  props: {
    entity: {},
    label: {
      type: String,
      required: false,
      default: 'Email'
    }
  },

  data() {
    return {

    }
  },
}
</script>
