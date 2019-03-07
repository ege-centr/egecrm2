<template>
  <div>
    <v-data-table :items='items' hide-headers hide-actions :class='config.elevationClass'>
      <template slot="items" slot-scope="{ item }">
        <td>
          <span v-if='item.review !== null'>
            отзыв {{ item.review.id }}
          </span>
          <span v-else class='grey--text'>
            не создан
          </span>
        </td>
        <td>
          <SubjectGrade :item='item' />
        </td>
        <td>
          <router-link :to="{name: 'ClientShow', params: {id: item.client_id}}" v-if='item.client_id > 0'>
            {{ item.client.names.short }}
          </router-link>
        </td>
        <td>
          <router-link :to="{name: 'TeacherShow', params: {id: item.teacher_id}}" v-if='item.teacher_id > 0'>
            {{ item.teacher.names.short }}
          </router-link>
        </td>
        <td>
          {{ item.lesson_count }} занятий
        </td>
        <td width='40' class='td-border px-1 text-md-center'>
          <span v-if='item.review !== null && getComment(item, COMMENT_TYPE.client)'>
            {{ getComment(item, COMMENT_TYPE.client).rating }}
          </span>
        </td>
        <td width='40' class='td-border px-1 text-md-center'>
          <span v-if='item.review !== null && getComment(item, COMMENT_TYPE.admin)'>
            {{ getComment(item, COMMENT_TYPE.admin).rating }}
          </span>
        </td>
        <td width='40' class='td-border px-1 text-md-center'>
          <span v-if='item.review !== null && getComment(item, COMMENT_TYPE.final)'>
            <span class='grey--text' v-if='getComment(item, COMMENT_TYPE.final).rating === -1'>
              –
            </span>
            <span v-else>
              {{ getComment(item, COMMENT_TYPE.final).rating }}
            </span>
          </span>
        </td>
        <td>
          <span v-if='item.review !== null && item.review.score > 0 && item.review.max_score > 0'>
            {{ item.review.score }} из {{ item.review.max_score }}
          </span>
        </td>
        <td>
          <span v-if='item.review !== null'>
            <span class='green--text' v-if='item.review.is_approved'>
              проверено
            </span>
            <span v-else class='red--text'>
              не проверено
            </span>
          </span>
        </td>
        <td>
          <span v-if='item.review !== null'>
            <span class='green--text' v-if='item.review.is_published'>
              опубликовано
            </span>
            <span v-else class='red--text'>
              не опубликовано
            </span>
          </span>
        </td>
        <td class='text-md-right'>
          <v-btn flat icon color="black" class='ma-0' 
            v-if='item.review !== null'
            @click='$refs.Dialog.open(item.review.id)'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
    <Dialog ref='Dialog' @updated="$emit('updated')" />
  </div>
</template>

<script>
import Dialog from './Dialog'
import { COMMENT_TYPE } from '@/components/Review'

export default {
  props: {
    items: {
      type: Array,
      required: false
    }
  },

  components: { Dialog },

  data() {
    return {
      COMMENT_TYPE,
    }
  },  

  methods: {
    getComment(item, type) {
      return item.review.comments.find(e => e.type === type)
    }
  },
}
</script>