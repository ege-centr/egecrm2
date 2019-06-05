<template>
  <div v-if='item !== null' class='d-block nowrap'>
    <v-tooltip bottom>
      <ScoreCircle slot='activator' :score='item.homework_score' />
      <span>
        <h4>{{ getCategoryTitle(CATEGORY.homework) }}</h4>
        {{ item.homework_comment }}
      </span>
    </v-tooltip>
    <v-tooltip bottom>
      <ScoreCircle slot='activator' :score='item.learning_ability_score' />
      <span>
        <h4>{{ getCategoryTitle(CATEGORY.learningAbility) }}</h4>
        {{ item.learning_ability_comment }}
      </span>
    </v-tooltip>
    <v-tooltip bottom>
      <ScoreCircle slot='activator' :score='item.knowledge_score' />
      <span>
        <h4>{{ getCategoryTitle(CATEGORY.knowledge) }}</h4>
        {{ item.knowledge_comment }}
      </span>
    </v-tooltip>
  </div>
</template>


<script>
import { getCategoryTitle, CATEGORY } from '@/components/Report'
import ScoreCircle from '@/components/UI/ScoreCircle'

export default {
  props: {
    item: {
      required: true
    }
  },

  components: { ScoreCircle },

  data() {
    return {
      CATEGORY
    }
  },

  methods: {
    getCategoryTitle
  }
}
</script>
