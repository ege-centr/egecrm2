<template>
  <div style='overflow: hidden' class='relative width-100 px-3'>
    <div class='flex-items mb-3'>
      <v-btn small flat class='ml-0' 
        @click='setMode(mode.teacher)'
        :class="{'primary': selectedMode === mode.teacher}">
        {{ getData('teachers', item.teacher_id).default_name }}
      </v-btn>
      <v-btn small flat 
        @click='setMode(mode.cabinet)'
        :class="{'primary': selectedMode === mode.cabinet}">
        ПО КАБИНЕТАМ
      </v-btn>
      <v-btn small flat 
        @click='setMode(mode.group)'
        :class="{'primary': selectedMode === mode.group}">
        ТЕКУЩАЯ ГРУППА {{ group.id }}
      </v-btn>
    </div>
    <Loader transparent v-if='loading' />
    <div v-else>

      <!-- CABINET -->
      <div class='flex-items' style='overflow: scroll' v-if='selectedMode === mode.cabinet'>
        <div v-for="cabinet in $store.state.data.cabinets" 
          :key='cabinet.id' 
          class='mr-2 grey--text'>
            <div style='font-size: 11px; white-space: nowrap; text-align: center; width: 50px'>
              {{ cabinet.title }}
            </div>

            <div v-for='(items, index) in items[cabinet.id]' :key='index'>
              <TimelineDay  
                current-class='red'
                :show-date='true'
                :items="items" 
              />
            </div>
        </div>
      </div>

      <!-- GROUP -->
      <div v-if='selectedMode === mode.group'>
        <TimelineWeek regular :items='items.regular' :show-weekday='true' class='mb-3' />

        <div v-for='(weekItems, index) in items.detailed' :key='index' class='mb-2'>
          <TimelineWeek 
            current-class='red'
            :items='weekItems' 
            :show-date='true'
          />
        </div>
      </div>

      <!-- TEACHER -->
      <div v-if='selectedMode === mode.teacher'>
        <TimelineWeek :items='teacherFreetime' color='amber' :show-weekday='true'  class='mb-3'  />
        
        <TimelineWeek regular :items='items.regular' class='mb-3' />

        <div v-for='(weekItems, index) in items.detailed' :key='index' class='mb-2'>
          <TimelineWeek 
            current-class='red'
            :items='weekItems' 
            :show-date='true'
          />
        </div>
      </div>
    </div>
  </div>
</template>


<script>

import { API_URL } from '@/components/Timeline'
import { API_URL as TEACHER_FREETIME_API_URL } from '@/components/Teacher/Freetime'
import TimelineDay from '@/components/Timeline/Day'
import TimelineWeek from '@/components/Timeline/Week'

const mode = {
  cabinet: 'cabinet',
  teacher: 'teacher',
  group: 'group'
}

export default {
  props: ['item', 'group'],

  components: { TimelineDay, TimelineWeek },

  data() {
    return {
      mode,
      selectedMode: mode.cabinet,
      items: null,
      loading: false,
      teacherFreetime: null,
    }
  },

  created() {
    this.loadData()
  },
  
  methods: {
    setMode(mode) {
      if (this.selectedMode !== mode) {
        this.selectedMode = mode
        this.loadData()
      }
    },

    loadData() {
      // if (this.item.cabinet_id && this.item.date) {
      // все параметры должны быть заполнены
      if (this.current !== null) {
        this.loading = true

        axios.post(apiUrl(API_URL, this.selectedMode), {
          current: this.current,
          year: this.group.year,
          group_id: this.group.id,
        }).then(r => {
          this.items = r.data
          this.loading = false
        })

        if (this.teacherFreetime === null) {
          axios.get(apiUrl(TEACHER_FREETIME_API_URL), {
            params: {
              teacher_id: this.item.teacher_id
            }
          }).then(r => {
            this.teacherFreetime = r.data.data
            this.loading = false
          })
        } 
      }
    },

  },

  computed: {
    current() {
      // текущее время только в том случае, если заполнены все параметры текущего времени
      if (
        this.item.date && 
        this.item.time.length === 5 && 
        this.item.duration > 0 &&
        this.item.cabinet_id > 0 &&
        this.item.teacher_id > 0
      ) {
        return _.pick(this.item, ['id', 'date', 'time', 'duration', 'cabinet_id', 'teacher_id'])
      }
      return null
    }
  }
}
</script>
