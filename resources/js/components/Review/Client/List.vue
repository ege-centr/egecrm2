<template>
  <div>
    <data-table :items='items'>
      <tr slot-scope="{ item }">
        <td>
          <SubjectGrade :item='item' />
        </td>
        <td>
          {{ item.lesson_count }} занятий
        </td>
        <td>
          <span v-if='item.teacher_id'>
            {{ getData('teachers', item.teacher_id).default_name }}
          </span>
        </td>
        <td>
          <ScoreCircle 
            v-if='item.review' 
            :score='item.review.comments.find(e => e.type === COMMENT_TYPE.client).rating' 
          />
        </td>
        <td class='text-md-right pa-0' width='180'>
          <v-btn flat color='primary' small v-if='item.review === null'
            @click='add(item)' class='btn-td'>добавить отзыв</v-btn>
          <v-btn flat color='primary' small v-else
            @click='edit(item)' class='btn-td'>редактировать</v-btn>
        </td>
      </tr>
    </data-table>
    <Dialog ref='Dialog' @updated="$emit('updated')" />
  </div>
</template>

<script>
import Dialog from './Dialog'
import { COMMENT_TYPE } from '@/components/Review'
import ScoreCircle from '@/components/UI/ScoreCircle'

export default {
  props: {
    items: {
      type: Array,
      required: false
    }
  },

  components: { Dialog, ScoreCircle },

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