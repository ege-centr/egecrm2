<template>
  <div class='content-overlay'>
    <v-container fluid>
      <slot></slot>
    </v-container>
  </div>
</template>


<style lang='scss' scoped>
@import '~sass/_variables';

.content-overlay {
  position: fixed;
  left: 250px;
  top: 0;
  height: 100%;
  width: calc(100% - 250px);
  background: $grey-background !important;
  z-index: 99;
  overflow-y: scroll;
}
</style>