<template>
  <div class='lesson-status-circles'>
     <div class='lesson-status-circles__item' :class="{
        'blue': item.status === LESSON_STATUS.PLANNED,
        'green': item.status === LESSON_STATUS.CONDUCTED,
        'grey': item.status === LESSON_STATUS.CANCELLED,
      }"></div>

      <div class='lesson-status-circles__item orange' 
        title='первое занятие в группе'
        v-if='item.is_first_in_group'
      ></div>

      <div class='lesson-status-circles__item red'
        title='не зарегистрировано'
        v-if='item.is_not_registered'
      ></div>

      <div class='lesson-status-circles__item purple'
        title='не запланировано'
        v-if='item.is_unplanned'
      ></div>
  </div>
</template>



<script>
import { LESSON_STATUS } from '@/components/Lesson'

export default {
  props: {
    item: {
      type: Object,
      required: true,
    }
  },

  data() {
    return {
      LESSON_STATUS
    }
  },

  // created() {
  //   this.item.is_unplanned = true
  //   this.item.is_not_registered = true
  //   this.item.is_first_in_group = true
  // }
}
</script>


<style lang='scss' scoped>
.lesson-status-circles {
  display: flex;
  &__item {
    border-radius: 50%;
    height: 8px;
    width: 8px;
    &:not(:last-child) {
      margin-right: 3px;
    }
  }
}
</style>
