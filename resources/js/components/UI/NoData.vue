<template>
  <div 
    class='no-data grey--text darken-3' 
    :style='{height: heightValue}'
    :class="{
      'no-data_transparent': transparent === '',
      'no-data_square': square === '',
      'no-data_fullheight': fullheight,
    }"
  >
    <div class='no-data__text'>
      {{ label }}
    </div>
    <v-btn icon flat color='primary' v-if='add' @click.native='add'>
      <v-icon>add</v-icon>
    </v-btn>
    <slot></slot>
  </div>
</template>

<script>

export default {
  props: {
    add: {
      type: Function,
      required: false,
    },

    label: {
      type: String,
      default: 'нет данных',
    },

    transparent: {
      type: String,
      required: false,
    },

    square: {
      type: String,
      required: false,
    },

    fullheight: {
      type: Boolean,
      default: false,
    },

    color: {
      type: String,
      default: 'red',
      required: false,
    },

    height: {
      type: Number,
      default: null,
    },
  },

  computed: {
    heightValue() {
      if (this.height === null && this.fullheight) {
        return '85vh';
      }
      return this.height === null ? '200px' : this.height + 'px';
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
  width: 100%;
  border-radius: 4px;
  height: 200px;
  &_square {
    background: white;
    border-radius: 0;
  }
  &_transparent {
    background: transparent;
    height: 800px;
  }
  &__text {
    max-width: 400px;
    margin: 14px 0;
  }
}
</style>