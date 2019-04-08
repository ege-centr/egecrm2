<template>
  <div class='flex-items'>
    <BgAvatar :photo='item.teacher ? item.teacher.photo : null' :size='100' class='mr-4' />
    <div class='mr-5 pr-5'>
      <div class='item-label'>Преподаватель</div>
      <router-link v-if='item.teacher' :to="{name: 'TeacherShow', params: {id: item.teacher.id}}">
        {{ item.teacher.default_name }}
      </router-link>
      <span v-else>
        Не установлен
      </span>
      <div class='mt-3 item-label'>Уровень</div>
      <span v-if='item.level' class='text-capitalize'>{{ LEVELS.find(e => e.value == item.level).text }}</span>
      <span v-else>Не установлен</span>
    </div>
    <div class='mr-5 pr-5'>
      <div class='item-label'>Предмет и класс</div>
      <span v-if='item.subject_id' class='text-capitalize'>{{ getData('subjects', item.subject_id).name }}</span>
      <span v-if='item.grade_id'>, {{ getData('grades', item.grade_id).title }}</span>
      <div class='mt-3 item-label'>Расписание</div>
      <span>{{ item.schedule.label }}</span>
    </div>
    <div class='mr-5 pr-5'>
      <div class='item-label'>Учебный год</div>
      <span>{{ item.year }}–{{ item.year + 1 }}</span>
      <div class='mt-3 item-label'>Всего уроков</div>
      <span>{{ item.lessons.filter(e => e.status !== LESSON_STATUS.CANCELLED).length }}</span>
    </div>
    <div class='f-1 text-md-right align-center d-flex' v-if='show.edit'>
      <div>
        <v-btn @click="$emit('open', item.id)" flat icon color="black" class='ma-0'>
          <v-icon>more_horiz</v-icon>
        </v-btn>
      </div>
    </div>
  </div>
</template>

<script>
import BgAvatar from '@/components/UI/BgAvatar'
import { LEVELS } from '@/components/Group'
import DisplayOptions from '@/mixins/DisplayOptions'
import { LESSON_STATUS } from '@/components/Lesson'

export default {
  props: {
    item: {
      type: Object,
      required: true,
    }
  },

  mixins: [ DisplayOptions ],

  components: { BgAvatar },

  data() {
    return {
      LEVELS,
      LESSON_STATUS,
      defaultDisplayOptions: {
        edit: true,
      },
    }
  },
  
}
</script>

