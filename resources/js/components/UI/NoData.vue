<template>
  <div 
    class='no-data grey--text darken-3' 
    :style='{height: heightValue}'
    :class="{
      'no-data_transparent': transparent === '',
      'no-data_square': square === '',
      'no-data_box': box,
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

    box: {
      type: Boolean,
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
      if (this.height !== null && this.box) {
        return this.height + 'px';
      } 
      if (this.height === null && this.box) {
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
  background: #f6f7f8;
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
    font-weight: 500;
  }
  &_box {
    background: #e0e0e0;
    border-radius: 28px;
    width: calc(100% - 8px);
    margin: 0 auto;
  }
}
</style>