<template>
  <div>
    <data-table :items='items'>
      <tr slot-scope="{ item }">
        <td>
          <span v-if='item.review !== null'>
            {{ item.review.id }}
          </span>
          <span v-else class='grey--text'>
            не создан
          </span>
        </td>
        <td v-if='show.teacher'>
          <router-link :to="{name: 'TeacherShow', params: {id: item.teacher_id}}" v-if='item.teacher_id > 0'>
            {{ getData('teachers', item.teacher_id).default_name }}
          </router-link>
        </td>
        <td v-if='show.client'>
          <router-link :to="{name: 'ClientShow', params: {id: item.client_id}}" v-if='item.client_id > 0'>
            {{ item.client.default_name }}
          </router-link>
        </td>
        <td>
          <SubjectGrade :item='item' />
        </td>
        <td>
          {{ item.lesson_count }} занятий
        </td>
        <td>
          <span v-if='item.review !== null'>
            <v-tooltip bottom v-if='item.client_rating !== null'>
              <ScoreCircle slot='activator' :score='item.client_rating' />
              <span>оценка ученика</span>
            </v-tooltip>
            <v-tooltip bottom v-if='item.admin_rating !== null'>
              <ScoreCircle slot='activator' :score='item.admin_rating' />
              <span>предварительная оценка</span>
            </v-tooltip>
            <v-tooltip bottom v-if='item.final_rating !== null'>
              <ScoreCircle slot='activator' :score='item.final_rating' />
              <span>финальная оценка</span>
            </v-tooltip>
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
            @click='createOrEdit(item)'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </tr>
    </data-table>
    <Dialog ref='Dialog' @updated="$emit('updated')" />
  </div>
</template>

<script>
import Dialog from './Dialog'
import { COMMENT_TYPE } from '@/components/Review'
import DisplayOptions from '@/mixins/DisplayOptions'
import ScoreCircle from '@/components/UI/ScoreCircle'

export default {
  props: {
    items: {
      type: Array,
      required: false
    }
  },

  mixins: [ DisplayOptions ],

  components: { Dialog, ScoreCircle },

  data() {
    return {
      COMMENT_TYPE,
      defaultDisplayOptions: {
        teacher: true,
        client: true,
      },
    }
  },  

  methods: {
    getComment(item, type) {
      return item.review.comments.find(e => e.type === type)
    },

    createOrEdit(item) {
      if (item.review === null) {
        console.log('adding', item)
        this.$refs.Dialog.open(null, {
          ..._.pick(item, [
            'teacher_id', 'client_id', 'grade_id', 'subject_id', 'year'
          ]),
          ...{
            comments: [
              {
                rating: null,
                text: '',
                type: COMMENT_TYPE.client
              },
              {
                rating: null,
                text: '',
                type: COMMENT_TYPE.admin
              },
              {
                rating: null,
                text: '',
                type: COMMENT_TYPE.final
              }
            ]
          }
        })
      } else {
        this.$refs.Dialog.open(item.review.id)
      }
    }
  },
}
</script>