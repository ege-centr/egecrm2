<template>
    <v-card class="mb-3" :class='config.elevationClass'>
      <v-card-text>
        <v-layout row>
          <v-flex style='width: 80%; border-right: 1px solid #9e9e9e'>
            <div class='mb-5'>
              <div class='flex-items'>
                <Avatar :photo='item.created_admin_id ? item.createdAdmin.photo : null' :size='50' class='mr-3' />
                <div>
                  <div>
                    <b>{{ item.created_admin_id ? item.createdAdmin.name : 'System' }}</b>
                    <span class='d-inline-block ml-1 grey--text'>
                      {{ item.created_at | date-time }}
                    </span>
                  </div>
                  <div>
                    {{ item.comment }} 
                    <PhoneList :items='item.phones' />
                  </div>
                </div>
              </div>
            </div>
            <Comments class-name='Request' :entity-id='item.id' :items='item.comments' />
          </v-flex>
          <v-flex style='width: 20%' class='ml-3'>
            <div class='mb-3'>
              <div class='item-label'>Статус</div>
              {{ REQUEST_STATUSES.find(e => e.id == item.status).title }}
            </div>
            <div class='mb-3' v-if='item.responsibleAdmin'>
              <div class='item-label'>Ответственный</div>
              {{ item.responsibleAdmin.name }}
            </div>
            <div class='mb-3'>
              <div class='item-label'>Филиалы</div>
              <BranchList :items='item.branches' />
            </div>
            <div class='mb-3'>
              <div class='item-label'>Предметы и класс</div>
              <span v-for='(subject_id, index) in item.subjects' :key='subject_id'>{{ getData('subjects', subject_id).three_letters }}{{ index == (item.subjects.length - 1) ? '' : '+' }}</span>
              <span v-if='item.grade_id'>
                ({{ getData('grades', item.grade_id).title }})
              </span>
            </div>
            <div class='mb-3'>
              <div class='item-label'>Клиенты</div>
              <div v-if='item.client_ids.length'>
                <div v-for='client_id in item.client_ids' :key='client_id'>
                  <router-link :to="{ name: 'ClientShow', params: {id: client_id}}">
                    {{ client_id }}
                  </router-link>
                </div>
              </div>
              <div v-else>
                <a @click="$emit('openClientDialog', null, {phones: item.phones})">добавить</a>
              </div>
            </div>
             <v-btn flat icon color="black" class='ma-0 mt-5 edit-request-button' @click="$emit('openDialog', item.id)">
                <v-icon>more_horiz</v-icon>
              </v-btn>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
</template>

<script>

import { REQUEST_STATUSES } from './'
import Avatar from '@/components/UI/Avatar'
import Comments from '@/components/Comments'
import PhoneList from '@/components/Phone/List'
import BranchList from '@/components/UI/BranchList'

export default {
  components: { Avatar, Comments, PhoneList, BranchList },
  data() {
    return {
      REQUEST_STATUSES
    }
  },
  props: ['item'],
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
