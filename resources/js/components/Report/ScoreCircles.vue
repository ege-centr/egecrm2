<template>
  <div v-if='item !== null' class='d-block nowrap'>
    <v-tooltip bottom>
      <ScoreCircle slot='activator' :score='item.homework_score' />
      <span>{{ getCategoryTitle(CATEGORY.homework) }}</span>
    </v-tooltip>
    <v-tooltip bottom>
      <ScoreCircle slot='activator' :score='item.activity_score' />
      <span>{{ getCategoryTitle(CATEGORY.activity) }}</span>
    </v-tooltip>
    <v-tooltip bottom>
      <ScoreCircle slot='activator' :score='item.behavior_score' />
      <span>{{ getCategoryTitle(CATEGORY.behavior) }}</span>
    </v-tooltip>
    <v-tooltip bottom>
      <ScoreCircle slot='activator' :score='item.learning_ability_score' />
      <span>{{ getCategoryTitle(CATEGORY.learningAbility) }}</span>
    </v-tooltip>
    <v-tooltip bottom>
      <ScoreCircle slot='activator' :score='item.knowledge_score' />
      <span>{{ getCategoryTitle(CATEGORY.knowledge) }}</span>
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
