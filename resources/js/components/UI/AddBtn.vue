<template>
  <div class='add-btn' :class="{
    'add-btn_absolute': absolute === '',
  }">
    <v-btn icon flat color='primary'>
      <v-icon dark>{{ icon }}</v-icon>
    </v-btn>
  </div>
</template>

<script>
export default {
  props: {
    // костылёк. сделано, чтобы AddBtn был внутри List'a
    // может, потом переделаю
    absolute: {
      type: String,
      required: false,
    },

    icon: {
      type: String,
      required: false,
      default: 'add',
    }
  }
}
</script>

<style lang="scss" scoped>
.add-btn {
  & .v-btn {
    margin: 0;
  }
  &_absolute {
    position: absolute;
    right: 4px;
    top: -58px;
  }
}
</style>
