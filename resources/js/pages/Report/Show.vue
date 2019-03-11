<template>
  <div>
    <Loader transparent v-if='loading' />
    <div v-else>
      <div class='headline mb-4'>
        Отчёт преподавателя по 
        {{ getData('subjects', item.subject_id).dative }}
        <PersonName :item='item.teacher' />
      </div>
      
      <v-card>
        <v-card-text>
          <div v-for='categoryName in CATEGORY' :key='categoryName' class='mb-5'>
            <div class='font-weight-medium mb-2'>
              {{ getCategoryTitle(categoryName) }}
            </div>
            <div class='mb-2'>
              <div class='flex-items align-center'>
                <span class='caption mr-3 input-label'>Оценка</span>
                <v-rating dense readonly v-model="item[categoryName + '_score']"></v-rating>
              </div>
            </div>
            <div>
              {{ item[categoryName + '_comment'] }}
            </div>
          </div>
          <div class='mb-5'>
            <div class='font-weight-medium mb-2'>
              Рекомендации родителям
            </div>
            <div>
              {{ item.recommendation }}
            </div>
          </div>
          <div class='flex-items font-italic'>
            <BgAvatar :photo='item.teacher.photo' :size='100' class='mr-3' />
            <div>
              <div>
                Преподаватель по {{ getData('subjects', item.subject_id).dative }}
              </div>
              <div>
                <PersonName :item='item.teacher' />
              </div>
              <div>
                Дата составления данного отчета: 
                {{ item.date | date }}
              </div>
            </div>
          </div>
        </v-card-text>
      </v-card>
      <div class='text-md-center mt-3 caption'>
        Если у Вас есть вопросы, пожалуйста, звоните по единому номеру ЕГЭ-Центра (495) 646-85-92
      </div>
    </div>
  </div>
</template>



<script>
import BgAvatar from '@/components/UI/BgAvatar'
import { API_URL, CATEGORY, getCategoryTitle } from '@/components/Report'

export default {
  components: { BgAvatar },

  data() {
    return {
      CATEGORY,
      loading: true,
      item: null,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    getCategoryTitle, 

    loadData() {
      axios.get(apiUrl(`${API_URL}/${this.$route.params.id}`)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },
  }
}
</script>