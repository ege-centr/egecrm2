<template>
  <div>
    <div class='visits' v-if='group.lessons.length > 0'>
      <div class='visits__dates'>
        <div></div>
        <div v-for='(lesson, index) in group.lessons' :key='lesson.id' :class="getClass(index)">
          <span>{{ lesson.date | date }}</span>
        </div>
      </div>
      <div class="visits__items" v-for='client in clients' :key='client.id'>
        <div>
          <router-link :to="{ name: 'ClientShow', params: { id: client.id } }">{{ client.names.short }}</router-link>
        </div>
        <div v-for='(lesson, index) in group.lessons' :key='lesson.id' :class="getClass(index)">
          <SmallCircle v-if='getClientLesson(lesson, client)' 
            :class-name='getCircleClass(lesson, client)' 
            :title="getClientLesson(lesson, client).late > 0 ? 'опоздал на ' + getClientLesson(lesson, client).late + ' мин' : ''"
          />
        </div>
      </div>
      <div class="visits__items" v-for="(teacher, index) in teachers" :key="teacher.id" :class="{'first-teacher-item': index === 0}">
        <div>
          <router-link :to="{ name: 'TeacherShow', params: { id: teacher.id } }">{{ teacher.names.abbreviation }}</router-link>
        </div>
        <div v-for='(lesson, index) in group.lessons' :key='lesson.id' :class="getClass(index)">
          <SmallCircle v-if='lesson.status === LESSON_STATUS.CONDUCTED && lesson.teacher_id === teacher.id' class-name='green' />
        </div>
      </div>
    </div>
    <NoData v-else />
  </div>
</template>

<script>

import SmallCircle from '@/components/UI/SmallCircle'
import { LESSON_STATUS } from '@/components/Lesson'

export default {
  props: {
    group: {
      type: Object,
      required: true,
    }
  },

  components: { SmallCircle },

  data() {
    return {
      LESSON_STATUS,
    }
  },

  methods: {
    // TODO: поэкспериментировать с кешированием
    getClientLesson(lesson, client) {
      return lesson.clientLessons.find(e => e.client.id === client.id)
    },

    getCircleClass(lesson, client) {
      const clientLesson = this.getClientLesson(lesson, client)
      if (clientLesson.is_absent) {
        return 'red'
      }
      if (clientLesson.late > 0) {
        return 'orange'  
      }
      return 'green'
    },

    getClass(index) {
      return {
        // 'odd-month': this.oddMonth(lesson),
        'is-cancelled': this.group.lessons[index].status === LESSON_STATUS.CANCELLED,
        'years-separator': this.yearsSeparator(index),
      }
    },

    // DEPRICATED
    oddMonth(lesson) {
      return moment(lesson.date).format('M') % 2 === 1
    },

    yearsSeparator(index) {
      if (index === 0) {
        return false
      }
      return moment(this.group.lessons[index].date).format('Y') != moment(this.group.lessons[index - 1].date).format('Y')
    },
  },

  computed: {
    clients() {
      const clients = []
      this.group.lessons.forEach(lesson => {
        lesson.clientLessons.forEach(clientLesson => {
          if (clients.findIndex(e => e.id === clientLesson.client.id) === -1) {
            clients.push(clientLesson.client)
          }
        })
      })
      // клиенты, у которых еще не было ни одного занятия
      this.group.clients.forEach(client => {
        if (clients.findIndex(e => e.id === client.id) === -1) {
          clients.push(client)
        }
      })
      return clients
    },

    teachers() {
      const teachers = []
      this.group.lessons.forEach(lesson => {
        if (lesson.teacher !== null && teachers.findIndex(e => e.id === lesson.teacher.id) === -1) {
          teachers.push(lesson.teacher)
        }
      })
      return teachers
    },

    
  },
}
</script>

<style lang="scss" scoped>
  $border-color: #ddd;
  $cell-width: 25px;
  .visits {
    font-size: 12px;
    overflow-x: scroll;
    & > div {
      display: flex;
      text-align: center;
      & > div {
        min-width: $cell-width;
        border-right: 1px solid $border-color;
        border-bottom: 1px solid $border-color;
        &:first-child {
          width: 250px;
          min-width: 250px;
          text-align: left;
        }
        &.is-cancelled {
          background: $border-color;
        }
        &.years-separator {
          margin-left: #{$cell-width + 2px};
          border-left: 1px solid $border-color;
          width: #{$cell-width + 2px};
          min-width: #{$cell-width + 2px};
        }
      }
    }
    &__dates {
      & > div {
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        & span {
            writing-mode: tb;
            display: inline-block;
            transform: rotate(180deg);
        }
      }
    }

    &__items {
      & > div {
        padding: 3px 5px;
      }
      &.first-teacher-item {
        margin-top: #{$cell-width + 2px};
        & > div {
          border-top: 1px solid $border-color;
        }
      }
    }
  }
</style>
