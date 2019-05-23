<template>
  <div class='loader-wrapper' :class="{
    'loader-wrapper_transparent': transparent,
    'loader-wrapper_block': block
  }">
    <v-progress-circular :size="50" color="#1976d2" indeterminate></v-progress-circular>
  </div>
</template>

<script>
export default {
  props: {
    transparent: Boolean,
    block: Boolean,
  }
}
</script>



<style lang="scss" scoped>
  @import '~sass/_variables';

  .loader-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    height: 101%;
    width: 100%;
    display: flex;
    z-index: 1;
    justify-content: center;
    align-items: center;
    background: rgba(white, .8);
    border-radius: $border-radius;
    &_block {
      height: 100px;
      background: none;
      position: relative;
    }
    &_transparent {
      min-height: 300px;
      background: none;
    }
    &_halfscreen-dialog {
      height: calc(80vh - 64px);
      top: 10px;
    }
    &_fullscreen-dialog {
      height: calc(100vh - 64px);
      top: 10px;
    }
  }
</style>
