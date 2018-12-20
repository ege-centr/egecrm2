<template>
    <v-card class="elevation-3 mb-3">
      <v-card-text>
        <v-layout row>
          <v-flex style='width: 80%; border-right: 1px solid #9e9e9e'>
            <div class='mb-5'>
              <div class='item-label'>Комментарий</div>
              {{ item.comment }} 
              <span v-for='(phone, index) in item.phones' :key='index'><a>{{ phone.phone }}</a>{{ index === item.phones.length - 1 ? '' : ', ' }}</span>
              <span class='grey--text'>{{ item.name }}</span>
            </div>
            <Comments class-name='Request' :entity-id='item.id' :items='item.comments' />
          </v-flex>
          <v-flex style='width: 20%' class='ml-3'>
            <div class='mb-3'>
              <div class='item-label'>Статус</div>
              {{ request_statuses.find(e => e.value == item.status).text }}
            </div>
            <div class='mb-3'>
              <div class='item-label'>Ответственный</div>
              {{ item.responsibleAdmin.name }}
            </div>
            <div class='mb-3'>
              <div class='item-label'>Филиалы</div>
              <span v-for='(branch_id, index) in item.branches' :key='branch_id'>
                <span :style="{color: getData('branches', branch_id).color}">{{ getData('branches', branch_id).short }}{{ index === item.branches.length - 1 ? '' : ', ' }}</span>
              </span>
            </div>
            <div class='mb-3'>
              <div class='item-label'>Предметы и класс</div>
              <span v-for='(subject_id, index) in item.subjects' :key='subject_id'>{{ getData('subjects', subject_id).three_letters }}{{ index == (item.subjects.length - 1) ? '' : '+' }}</span>
              <span v-if='item.grade'>
                ({{ getData('grades', item.grade).title }})
              </span>
            </div>
            <div class='mb-3'>
              <div class='item-label'>Реквизиты заявки</div>
              {{ item.created_user_id ? getData('users', item.created_user_id).login : 'system' }} {{ item.created_at | date-time }}
            </div>
            <div class='mb-3'>
              <div class='item-label'>Клиенты</div>
              <div v-for='client_id in item.client_ids' :key='client_id'>
                <router-link :to="{ name: 'ClientShow', params: {id: client_id}}">
                  {{ client_id }}
                </router-link>
              </div>
            </div>
             <v-btn flat icon color="black" class='ma-0 mt-5 edit-request-button' @click="$emit('show', item.id)" >
                <v-icon>more_horiz</v-icon>
              </v-btn>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
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

  .edit-request-button {
    position: absolute; 
    bottom: 5px; 
    right: 10px;
  }
</style>
