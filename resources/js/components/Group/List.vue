<template>
  <div>
    <data-table :items='items'>
      <template slot-scope="{ item }">
        <tr @click='handleRowClick(item)' v-if="'id' in item">
          <td width='1' v-if='selectable' class='pr-0'>
            <v-radio-group class='ma-0' v-model='selected_group_id' @change="$emit('update:selected_group_id', selected_group_id)" hide-details>
              <v-radio :value='item.id' class='ma-0'></v-radio>
            </v-radio-group>
          </td>
          <td width='200'>
            <router-link 
              :to="{ name: 'GroupShow', params: {id: item.id}}"
              :class="'subject_status' in item ? getSubjectStatusClass(item.subject_status) : {}"
            >
              Группа {{ item.id }}
            </router-link>
          </td>
          <td v-if='show.teacher' width='300'>
            <span v-if='item.teacher_id > 0'>
              <router-link :to="{ name: 'TeacherShow', params: {id: item.teacher_id} }">
                {{ getData('teachers', item.teacher_id).default_name }}
              </router-link>
            </span>
          </td>
          <td>
            <SubjectGrade :item='item' />
          </td>
          <td>
            <ScheduleString :items='item.schedule' />
          </td>
          <td>
            <span v-if='item.client_ids.length > 0'>
              {{ item.client_ids.length }} ученика
            </span>
            <span v-else>нет учеников</span>
          </td>
          <td>
            <span v-if='item.lessons_count > 0 && item.lessons_conducted_count > 0'>
              {{ item.lessons_conducted_count }} из {{ item.lessons_count }} уроков
            </span>
            <span v-if='item.lessons_count > 0 && item.lessons_conducted_count === 0'>
              старт {{ item.first_lesson_date | day-month }}
            </span>
          </td>
        </tr>
        <!-- абстрактная -->
        <tr v-else>
          <td width='200'>
            Без группы
          </td>
          <td width='300'></td>
          <td colspan='3'>
            {{ getData('subjects', item.subject_id).three_letters }}
          </td>
          <td class='text-md-right pa-0' width='180'>
            <v-btn slot='activator' small class='btn-td' flat
              color='primary' @click.native='() => $refs.MoveClientDialog.open(item, $route.params.id)'>присвоить группу</v-btn>
          </td>
        </tr>
      </template>
    </data-table>
    <component 
      v-if='abstract' 
      ref='MoveClientDialog' 
      :is='MoveClientDialog' 
      @moved="$emit('updated')" 
    />
  </div>
</template>

<script>

import DisplayOptions from '@/mixins/DisplayOptions'
import ScheduleString from '@/components/Group/ScheduleString'
import { getSubjectStatusClass } from '@/components/Contract'

export default {
  props: {
    items: {
      type: Array,
      default: null,
      required: false
    },
    selectable: {
      type: Boolean,
      default: false,
      required: false,
    },
    abstract: {
      type: Boolean,
      default: false,
    }
  },

  components: { ScheduleString },

  mixins: [ DisplayOptions ],

  data() {
    return {
      selected_group_id: null,
      defaultDisplayOptions: {
        teacher: true,
      }
    }
  },

  methods: {
    getSubjectStatusClass,

    handleRowClick(item) {
      if (this.selectable) {
        this.selected_group_id = item.id
        this.$emit('update:selected_group_id', item.id)
      }
    }
  },

  computed: {
    // dynamic components loading
    // https://itnext.io/vue-a-pattern-for-idiomatic-performant-component-registration-you-might-not-know-about-9f3c091846f5
    // https://webpack.js.org/guides/code-splitting/
    MoveClientDialog() {
      return require('@/components/Group/MoveClientDialog')
    }
  }
}
</script>
