<template>
  <v-hover>
    <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 8 : 3}`" class='mb-3'>
      <v-card-text>
        <v-layout row>
          <v-flex style='width: 60%'>
            <div class='request-info'>
              <v-avatar :size="54">
                <img src="http://placekitten.com/g/200/200">
              </v-avatar>
              <div>
                <div>
                  <b class='d-inline-block mr-2'>
                    {{ item.created_user_id ? getData('users', item.created_user_id).login : 'system' }}
                    | заявка {{ item.id }}
                  </b>
                  <span class='grey--text lighten-2'>{{ item.created_at | date-time }}</span>
                </div>
                <div>
                  <span v-for='branch_id in item.branches' :key='branch_id'>
                    {{ getData('branches', branch_id).short }},
                  </span>
                  <span v-if='item.grade'>
                    {{ getData('grades', item.grade).title }},
                  </span>
                  <span v-for='(subject_id, index) in item.subjects' :key='subject_id'>
                    {{ getData('subjects', subject_id).name }}{{ index == (item.subjects.length - 1) ? '.' : ',' }}
                  </span>
                  <span>
                    {{ item.name }}
                  </span>
                </div>
                <div v-for='(phone, index) in item.phones' :key='index'>
                  {{ phone.phone }}
                </div>
              </div>
            </div>
          </v-flex>
          <v-flex style='width: 30%'>
            <div v-if='item.responsibleAdmin' class='request-info'>
              <Avatar :size='54' :photo='item.responsibleAdmin.photo' />
              <div>
                <b class='d-block'>{{ item.responsibleAdmin.name }}</b>
                <span>ответственный</span>
              </div>
            </div>
          </v-flex>
          <v-flex style='width: 10%'>
            <v-layout column>
              <v-flex>
                {{ request_statuses.find(e => e.value == item.status).text }}
              </v-flex>
              <v-flex class='text-md-right'>
                <v-btn flat icon color="black" class='ma-0 mt-5' @click="$emit('show', item.id)">
                  <v-icon>more_horiz</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
          </v-flex>
        </v-layout>

        <Comments class-name='Request' :entity-id='item.id' />
      </v-card-text>
    </v-card>
  </v-hover>
</template>

<script>

import { request_statuses } from './data'
import Avatar from '@/components/UI/Avatar'
import Comments from '@/components/Comments'

export default {
  components: { Avatar, Comments },
  data() {
    return {
      request_statuses
    }
  },
  props: ['item']
}
</script>

<style scoped lang='scss'>
  .request-info {
    display: flex;
    & > div {
      margin-right: 14px;
    }
  }
</style>
