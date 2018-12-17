<template>
  <div>
    <table class="table-journal">
      <thead>
        <tr>
          <th style="border: none !important"></th>
          <th v-for='lesson in lessons' :key='lesson.id' style="height: 70px; position: relative" :class="getTdClass(lesson)">
            <span v-if='lesson.status === LESSON_STATUS.CANCELLED' style='left: -17px'>отменено</span>
            <span v-else>{{ lesson.date | date }}</span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for='client in clients' :key='client.id'>
          <td class='contains-name'>
            <router-link :to="{ name: 'ClientShow', params: { id: client.id } }">{{ client.names.short }}</router-link>
          </td>
          <td v-for='lesson in lessons' :key='lesson.id' :class="getTdClass(lesson)">
            <SmallCircle v-if='getClientLesson(lesson, client)' 
              :class-name='getCircleClass(lesson, client)' 
              :title="getClientLesson(lesson, client).late > 0 ? 'опоздал на ' + getClientLesson(lesson, client).late + ' мин' : ''"
            />
          </td>
        </tr>
        <tr>
          <td colspan='1000'></td>
        </tr>
        <tr v-for="teacher in teachers" :key="teacher.id">
          <td class='contains-name'>
            <router-link :to="{ name: 'TeacherShow', params: { id: teacher.id } }">{{ teacher.names.abbreviation }}</router-link>
          </td>
          <td v-for='lesson in lessons' :key='lesson.id' :class="getTdClass(lesson)">
            <SmallCircle v-if='lesson.status === LESSON_STATUS.CONDUCTED && lesson.teacher_id === teacher.id' class-name='green' />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>

import SmallCircle from '@/components/UI/SmallCircle'
import { LESSON_STATUS } from '@/components/Lesson/data'

export default {
  props: {
    lessons: {
      type: Array,
      default: []
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

    getTdClass(lesson) {
      return {
        'odd-month': this.oddMonth(lesson),
        'is-cancelled': lesson.status === LESSON_STATUS.CANCELLED,
      }
    },

    oddMonth(lesson) {
      return moment(lesson.date).format('M') % 2 === 1
    },
  },

  computed: {
    clients() {
      const clients = []
      this.lessons.forEach(lesson => {
        lesson.clientLessons.forEach(clientLesson => {
          if (clients.findIndex(e => e.id === clientLesson.client.id) === -1) {
            clients.push(clientLesson.client)
          }
        })
      })
      return clients
    },

    teachers() {
      const teachers = []
      this.lessons.forEach(lesson => {
        if (teachers.findIndex(e => e.id === lesson.teacher.id) === -1) {
          teachers.push(lesson.teacher)
        }
      })
      return teachers
    }
  },
}
</script>

<style lang="scss" scoped>
.table-journal {
	width: auto !important;
  border-spacing: 0;
  border-collapse: collapse;
  font-size: 12px;
  & tr {
    & td, & th {
      width: 26px;
    }
    & td {
      &.contains-name {
        text-align: left; 
        width: 250px;
      }
    }
  }
	& th {
		font-weight: normal;
		color: black;
		& span {
			position: absolute;
      -moz-transform: rotate(-90deg);  /* FF3.5+ */
      -o-transform: rotate(-90deg);  /* Opera 10.5 */
      -webkit-transform: rotate(-90deg);  /* Saf3.1+, Chrome */
      filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=3);  /* IE6,IE7 */
      -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=3)"; /* IE8 */
      vertical-align: top !important;
      display: block;
	    left: -12px;
      top: 27px;
      white-space: nowrap;
		}
		& .lesson-cancelled-journal {
			position: absolute;
       width: 200px;
       left: -92px;
       top: 125px;
       color: #337ab7;
		}
	}
  & .odd-month {
    background: #eee;
  }
  & .is-cancelled {
    background: #9E9E9E;
    border: 1px solid #9E9E9E !important;
    color: white;
  }
	& td:last-child, & th:last-child {
    border-right: none !important;
	}
	& td, & th {
	  height: 24px;
	  padding: 0 4px !important;
	  text-align: center;
	  border: solid 1px #ddd !important;
	}
	& tr td:first-child {
		border-left: none !important;
	}
	& th {
		border-top: none !important;
	}
	& tr:last-child td {
		border-bottom: none !important;
	}
}
</style>
