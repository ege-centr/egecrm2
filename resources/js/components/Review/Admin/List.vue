<template>
  <div>
    <v-data-table :items='items' item-key='id' hide-headers hide-actions :class='config.elevationClass'>
      <template slot="items" slot-scope="{ item }">
        <td>
          отзыв {{ item.id }}
        </td>
        <td>
          <SubjectGrade :item='item' />
        </td>
        <td>
          <router-link :to="{name: 'ClientShow', params: {id: item.client_id}}">
            {{ item.client.names.short }}
          </router-link>
        </td>
        <td>
          <router-link :to="{name: 'TeacherShow', params: {id: item.teacher_id}}">
            {{ getData('teachers', item.teacher_id).names.short }}
          </router-link>
        </td>
        <td>
          <span>
            {{ getComment(item, COMMENT_TYPE.client) ? getComment(item, COMMENT_TYPE.client).rating : '/' }}
          </span> |
          <span>
            {{ getComment(item, COMMENT_TYPE.admin) ? getComment(item, COMMENT_TYPE.admin).rating : '/' }}
          </span> |
          <span>
            {{ getComment(item, COMMENT_TYPE.final) ? getComment(item, COMMENT_TYPE.final).rating : '/' }}
          </span>
        </td>
        <td>
          <span v-if='item.score && item.max_score'>
            {{ item.score }} из {{ item.max_score }}
          </span>
        </td>
        <td>
          <span class='green--text' v-if='item.is_approved'>
            проверено
          </span>
          <span v-else class='red--text'>
            не проверено
          </span>
        </td>
        <td>
          <span class='green--text' v-if='item.is_published'>
            опубликовано
          </span>
          <span v-else class='red--text'>
            не опубликовано
          </span>
        </td>
        <td class='text-md-right'>
          <v-btn flat icon color="black" class='ma-0' @click='edit(item)'>
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
    edit(item) {
      this.$refs.Dialog.open(item.id)
    },

    getComment(item, type) {
      return item.comments.find(e => e.type === type)
    }
  },
}
</script>