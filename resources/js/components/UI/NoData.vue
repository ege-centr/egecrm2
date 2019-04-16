<template>
  <div class='no-data border-radius grey--text darken-3' 
    :class="{
      'no-data_transparent': transparent === ''
    }"
    :style='{ height: `${ height }px` }'>
    <div class='headline font-weight-bold'>
      {{ title }}
    </div>
    <div class='no-data__text' :class="{'pb-0': add === undefined}">
      {{ text }}
    </div>
    <v-btn :color='color' dark round v-if='add !== undefined' @click='add'>
      <v-icon class='mr-1' style='position'>add</v-icon>
      добавить
    </v-btn>
  </div>
</template>

<script>

export default {
  props: {
    title: {
      type: String,
      default: 'Нет данных',
      required: false,
    },

    text: {
      type: String,
      default: 'Данные отсутствуют. Вы можете добавить их',
      required: false,
    },

    add: {
      type: Function,
      required: false,
    },

    height: {
      type: Number,
      default: 500,
    },

    transparent: {
      type: String,
      required: false,
    },

    isTab: {
      type: Boolean,
      default: false,
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
  &_transparent {
    background: transparent;
  }
  &__text {
    padding: 10px 0 24px;
    max-width: 400px;
  }
}
</style>