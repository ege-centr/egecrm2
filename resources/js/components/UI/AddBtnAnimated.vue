<template>
  <div class='add-btn-animated'>
    <v-btn fab dark small color="red" class='ma-0'>
      <v-icon dark>add</v-icon>
    </v-btn>
    <span class='add-btn-animated__title'>{{ label }}</span>
    <div class='add-btn-animated__blocker'></div>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: null,
      required: false,
    }
  }
}
</script>

<style lang="scss" scoped>
.add-btn-animated {
  display: inline-block;
  // background: rgba(#ff4c45, .4);
  border-radius: 40px;
  padding-right: 14px;
  position: relative;
  height: 40px;
  display: inline-flex;
  align-items: center;
  width: auto;
  cursor: pointer;
  &__title {
    position: relative;
    top: 1px;
    padding-left: 47px;
  }
  & button {
    position: absolute;
    left: calc(100% - 40px);
    transition: all ease-in .25s;
    z-index: 1;
    &:after {
      content: '';
    }
  }
  &__blocker {
    position: absolute;
    left: 0;
    width: 100%;
    height: 40px;
    background: #f2f2f2;
    border-radius: 40px;
    transition: all ease-in .25s;
  }
  &:hover {
    & button {
      left: 0%;
      // transform: rotate(-90deg);
    }
    & .add-btn-animated__blocker {
      width: 0%;
    }
  }
}


</style>
