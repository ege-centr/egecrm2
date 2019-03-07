<template>
  <div class='flex-items'>
    <v-avatar v-if='item.teacher' :size='100' class='bg-avatar mr-4' :style="{backgroundImage: `url(${item.teacher.photo_url})`}"></v-avatar>
    <v-avatar v-else size='100' class='mr-4'>
      <img src="/img/no-profile-img.jpg">
    </v-avatar>
    <div class='mr-5 pr-5'>
      <div class='item-label'>Преподаватель</div>
      <router-link v-if='item.teacher' :to="{name: 'TeacherShow', params: {id: item.teacher.id}}">
        {{ item.teacher.names.full }}
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
      <span>{{ item.lessons.length }}</span>
    </div>
    <div>
      <div class='item-label'>Статус</div>
      <span>{{ item.is_archived ? 'Заархивирована' : 'Активная' }}</span>
      
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

import { LEVELS } from '@/components/Group'
import DisplayOptions from '@/mixins/DisplayOptions'

export default {
  props: {
    item: {
      type: Object,
      required: true,
    }
  },

  mixins: [ DisplayOptions ],

  data() {
    return {
      LEVELS,
      defaultDisplayOptions: {
        edit: true,
      },
    }
  },
  
}
</script>

