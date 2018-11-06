<template>
  <div class='flex-items'>
    <div class="bars flex-items" v-for="(bars, i) in groupBars">
      <div  v-for="(bar, j) in bars" class="bars__item"
        :class="{
          'bars__item_current': bar !== null,
          'bars__item_filled': clientBars[i][j] !== null
        }"
      ></div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['groupBars', 'clientBars']
}
</script>

<style lang="scss">
.bars {
  $max-bar-height: 20px;
  margin-right: 6px;
  height: $max-bar-height;
  align-items: center;
  &__item {
    height: #{$max-bar-height - 6};
    width: 9px;
    margin-right: 2px;
    background-color: #C1D2DD;
    &_filled {
      background-color: #3A5162;
    }
    &_current {
      @extend .bars__item_filled;
      height: $max-bar-height;
    }
  }
}
</style>
