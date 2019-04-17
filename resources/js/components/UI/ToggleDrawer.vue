<template>
  <div 
    class='show-drawer' 
    :class="{
      'show-drawer_show': isShow,
      'show-drawer_hide': !isShow,
    }"
    @mouseover="hover = true"
    @mouseleave="hover = false"
    @click="$store.commit('toggleDrawer', isShow ? true : false)">
    <transition
      :enter-active-class="isShow ? 'animated slideInLeft' : 'animated slideInRight'"
      :leave-active-class="isShow ? 'animated slideOutLeft' : 'animated slideOutRight'"
    >
        <div class='show-drawer__btn' v-if='hover'>
          <v-icon color='white'>
            {{ isShow ? 'arrow_forward_ios' : 'arrow_backward_ios' }}
          </v-icon>
        </div>
    </transition>
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
          padding-left: 6px;
        }
      }
    }
    &_show {
      & .show-drawer {
        &__btn {
          background: #213846;
        }
      }
    }
    &__btn {
      animation-duration: .3s;
      // background: #213846;
      display: flex;
      align-items: center;
      height: 100%;
      cursor: pointer;
      & .v-icon {
        animation-duration: .5s;
        position: relative;
        left: -2px;
      }
    }
  }
</style>