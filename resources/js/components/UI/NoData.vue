<template>
  <div class='no-data grey--text darken-3' 
    :class="{
      'no-data_transparent': transparent === '',
      'no-data_square': square === '',
    }"
  >
    <v-btn icon flat color='primary' v-if='add' @click.native='add'>
      <v-icon>add</v-icon>
    </v-btn>
    <div v-else class='no-data__text'>
      нет данных
    </div>
  </div>
</template>

<script>

export default {
  props: {
    add: {
      type: Function,
      required: false,
    },
    transparent: {
      type: String,
      required: false,
    },

    square: {
      type: String,
      required: false,
    },

    color: {
      type: String,
      default: 'red',
      required: false,
    }
  }
}

</script>

<style lang='scss'>
.no-data {
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  flex-direction: column;
  background: #f6f7f8;
  width: 100%;
  border-radius: 4px;
  height: 200px;
  &_square {
    background: white;
    border-radius: 0;
    height: 200px !important;
  }
  &_transparent {
    background: transparent;
    height: 800px;
  }
  &__text {
    max-width: 400px;
    font-weight: 500;
  }
}
</style>