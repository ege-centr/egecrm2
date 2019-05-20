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
      <ScheduleString v-if='item.schedule.length > 0' :items='item.schedule' />
      <span v-else>Не установлено</span>
    </div>
    <div class='mr-5 pr-5'>
      <div class='item-label'>Учебный год</div>
      <span v-if='item.year'>
        {{ getData('years', item.year).title }}
      </span>
      <span v-else>
        Не установлен
      </span>
      <div class='mt-3 item-label'>Всего уроков</div>
      <span>{{ item.lessons.filter(e => e.status !== LESSON_STATUS.CANCELLED).length }}</span>
    </div>
    <div class='f-1 text-md-right align-center d-flex' v-if='show.edit'>
      <div>
        <Print ref='Print' :params="{type: 'teacher'}" />
        <v-menu left>
          <v-btn slot='activator' flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
          <v-list dense>
            <v-list-tile @click="$emit('open', item.id)">
                <v-list-tile-action>
                  <v-icon>edit</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-title>Редактировать</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
            <v-list-tile @click='$refs.Print.open({id: item.id})'>
                <v-list-tile-action>
                  <v-icon>print</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-title>Печать договора на преподавателя</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
          </v-list>
        </v-menu>
      </div>
    </div>
  </div>
</template>



<script>

import BgAvatar from '@/components/UI/BgAvatar'
import { LEVELS } from '@/components/Group'
import DisplayOptions from '@/mixins/DisplayOptions'
import { LESSON_STATUS } from '@/components/Lesson'
import Print from '@/components/Print'
import ScheduleString from '@/components/Group/ScheduleString'

export default {
  props: {
    item: {
      type: Object,
      required: true,
    }
  },

  mixins: [ DisplayOptions ],

  components: { BgAvatar, Print, ScheduleString },

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

