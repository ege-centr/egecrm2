<template>
  <div>
    <v-data-table :items='items' item-key='id' hide-headers hide-actions :class='config.elevationClass'>
      <template slot="items" slot-scope="{ item }">
        <td>
          <SubjectGrade :item='item' />
        </td>
        <td>
          {{ item.lesson_count }} занятий
        </td>
        <td>
          <span v-if='item.teacher_id'>
            {{ getData('teachers', item.teacher_id).names.short }}
          </span>
        </td>
        <td>
          <span v-if='item.review'>
            <v-rating 
              dense
              small
              readonly
              :value='item.review.comments.find(e => e.type === COMMENT_TYPE.client).rating' />
          </span>
        </td>
        <td class='text-md-right pa-0' width='180'>
          <v-btn flat color='primary' small v-if='item.review === null'
            @click='add(item)' class='btn-td'>добавить отзыв</v-btn>
          <v-btn flat color='primary' small v-else
            @click='edit(item)' class='btn-td'>редактировать</v-btn>
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
    add(item) {
      this.$refs.Dialog.open(null, {
        ..._.pick(item, [
          'teacher_id', 'client_id', 'grade_id', 'subject_id', 'year'
        ]),
        ...{
          comments: [
            {
              rating: null,
              text: '',
              type: 'client'
            }
          ]
        }
      })
    },

    edit(item) {
      this.$refs.Dialog.open(item.review.id)
    }
  },
}
</script>