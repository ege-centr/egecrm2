<template lang="html">
  <div>
    <v-card class='elevate-5 grey lighten-4'>
      <v-card-text class='pb-2 task'>
        <div v-html='item.text'></div>
        <div class='flex-items align-center mt-2'>
          <div class='caption mr-3' :class="status.class">
            {{ status.text }}
          </div>
          <div v-if='item.responsible_admin_id' class='caption'>
            Ответственный: {{ getData('admins', item.responsible_admin_id).name }}
          </div>
          <div class='text-md-right f-1'>
            <v-btn flat icon color="black" class='ma-0' @click="$emit('edit', item)">
              <v-icon>more_horiz</v-icon>
            </v-btn>
          </div>
        </div>
        <Comments class='mt-3' class-name='Task' :entity-id='item.id' />
      </v-card-text>
    </v-card>
  </div>
</template>

<script>

import { statuses } from './data'
import Comments from '@/components/Comments'

export default {
  props: ['item'],
  components: { Comments },
  computed: {
    status() {
      return statuses.find(e => e.value == this.item.status)
    }
  }
}
</script>

<style lang='scss'>
  .task {
    &.v-card__text > div:first-of-type {
      & img {
        max-width: 50%;
      }
      & p {
        margin: 0;
        padding: 0;
      }
    }
  }
</style>
