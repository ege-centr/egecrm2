<template>
  <div class='loader-wrapper'>
    <v-progress-circular :size="50" color="primary" indeterminate></v-progress-circular>
  </div>
</template>

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
  }
</style>
