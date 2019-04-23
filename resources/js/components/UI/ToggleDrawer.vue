<template>
  <div 
    class='show-drawer' 
    :class="{
      'show-drawer_show': isShow,
      'show-drawer_hide': !isShow,
    }"
    @click="$store.commit('toggleDrawer', isShow ? true : false)">
      <div class='show-drawer__btn'>
        <v-icon color='white'>
          {{ isShow ? 'arrow_forward_ios' : 'arrow_back_ios' }}
        </v-icon>
      </div>
  </div>
</template>



<script>
export default {
  props: {
    // стрелка в позиции "Открыть меню"
    show: {
      type: String,
    },
  },

  data() {
    return {
      hover: false,
    }
  },

  computed: {
    isShow() {
      return this.show === ''
    }
  },
}
</script>



<style lang='scss'>
  .show-drawer {
    position: fixed !important;
    height: 100%;
    z-index: 99;
    width: 20px;
    top: 0;
    &_hide {
      right: 0;
      width: 30px;
      & .show-drawer {
        &__btn {
          right: -30px;
          & .v-icon {
            right: -10px;
          }
        }
      }
      &:hover {
        .show-drawer__btn {
          right: 0;
          & .v-icon {
            opacity: 1;
          }
        }
      }
    }
    &_show {
      & .show-drawer {
        &__btn {
          left: -30px;
          background: #213846;
          & .v-icon {
            left: -2px;
          }
        }
      }
      &:hover {
        .show-drawer__btn {
          left: 0;
          & .v-icon {
            opacity: 1;
          }
        }
      }
    }
    &__btn {
      // background: #213846;
      transition: all ease-in-out .3s;
      position: relative;
      display: flex;
      align-items: center;
      height: 100%;
      cursor: pointer;
      & .v-icon {
        transition: opacity ease-in-out .7s;
        opacity: 0;
        position: relative;
      }
    }
  }
</style>