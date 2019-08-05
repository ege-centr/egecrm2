<template lang="html">
  <div>
    <v-card :class='config.elevationClass'>
      <v-card-text class='pb-2 task'>
        <div class='mb-2 flex-items align-center'>
          <b class='mr-1'>Задача {{ item.id }}</b>
          <span class='caption' style='text-transform: lowercase' :class='priority.class'>{{ priority.title }} приоритет</span>
        </div>
        <div class='task__text' v-html='item.text'></div>
        <div class='flex-items align-center mt-2'>
          <div class='caption mr-3' :class="status.class">
            {{ status.title }}
          </div>
          <div v-if='item.responsible_admin_id' class='caption mr-3'>
            Ответственный: {{ getData('admins', item.responsible_admin_id).default_name }}
          </div>
          <div v-if='item.createdUser' class='caption mr-3'>
            Автор: {{ item.createdUser.default_name }}
          </div>
          <div class='text-md-right f-1'>
            <v-btn flat icon color="black" class='ma-0' @click="$emit('edit', item.id)">
              <v-icon>more_horiz</v-icon>
            </v-btn>
          </div>
        </div>
        <div v-if='item.files.length' class='mt-1 grey--text small caption flex-items '>
          <a v-for='(file, index) in item.files' :key='index' class='mr-2 flex-items align-center' target="_blank" :href="`/storage/img/upload/${file.name}`">
            <v-icon style='font-size: 14px' class='mr-1'>attach_file</v-icon>
            <span class='grey--text'>{{ file.original_name }}</span>
          </a>
        </div>
        <Comments class='mt-3' :class-name='CLASS_NAME' :entity-id='item.id' :items='item.comments' />
      </v-card-text>
    </v-card>
  </div>
</template>

<script>

import { STATUSES, PRIORITY, CLASS_NAME } from './'
import Comments from '@/components/Comments'

export default {
  props: ['item'],

  components: { Comments },

  data() {
    return {
      CLASS_NAME,
    }
  },
  
  computed: {
    status() {
      return STATUSES.find(e => e.id == this.item.status)
    },

    priority() {
      return PRIORITY.find(e => e.id == this.item.priority)
    },
  }
}
</script>

<style lang='scss'>
  .task {
    &__text {
      & img {
        max-width: 100%;
        zoom: 50%;
      }
      & p {
        margin: 0;
        padding: 0;
      }
    }
  }
</style>
