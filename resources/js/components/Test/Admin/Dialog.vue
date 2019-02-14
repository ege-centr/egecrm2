<template>
   <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ true ? 'Редактирование' : 'Добавление' }} теста</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ true ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative test-dialog'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
              <v-flex md12 class='mb-4'>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.title' label='Название теста'></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect v-model='item.subject_id' type='subjects' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect v-model='item.grade_id' type='grades' />
                  </div>
                </div>
              </v-flex>
              
              <v-flex md12>
                <v-stepper v-model="step" non-linear>
                  <v-stepper-header>
                    <template v-for='(problem, index) in item.problems'>
                      <v-stepper-step editable :step="(index + 1)">
                        Вопрос {{ index + 1 }}
                      </v-stepper-step>
                      <v-divider></v-divider>
                    </template>
                    <v-stepper-step :step='999' editable>
                      Добавить
                    </v-stepper-step>
                  </v-stepper-header>
                  <v-stepper-items>
                    <v-stepper-content v-for='(problem, index) in item.problems' :step="(index + 1)" :key='index'>
                      <div class='relative'>
                        <TextEditor v-model='problem.text' />
                        <div class='custom-toolbar'>
                          <v-btn small 
                            @click='removeProblem'
                            :disabled='step === 1 && item.problems.length <= 1'>удалить вопрос</v-btn>
                        </div>
                      </div>
                    </v-stepper-content>
                  </v-stepper-items>
                </v-stepper>
              </v-flex>

              <v-flex md12 v-if='step > 0 && show_answers' class='mt-5'>
                <v-stepper v-model="answer_step" non-linear>
                  <v-stepper-header>
                    <template v-for='(answer, index) in currentProblem.answers' :step="(index + 1)">
                      <v-stepper-step editable :step="(index + 1)">
                        Ответ {{ index + 1 }}
                      </v-stepper-step>
                      <v-divider></v-divider>
                    </template>
                    <v-stepper-step :step='999' editable>
                      Добавить
                    </v-stepper-step>
                  </v-stepper-header>
                  <v-stepper-items>
                    <v-stepper-content v-for='(answer, index) in currentProblem.answers' :step="(index + 1)" :key='index'>
                      <div class='relative'>
                        <TextEditor v-model='answer.text' />
                        <div class='custom-toolbar flex-items align-center'>
                          <v-text-field style='width: 100px' v-mask="'###'" hide-details v-model='answer.score' label='Балл'></v-text-field>
                          <v-btn small 
                            @click='removeAnswer'
                            :disabled='answer_step === 1 && currentProblem.answers.length <= 1'>удалить ответ</v-btn>
                        </div>
                      </div>
                    </v-stepper-content>
                  </v-stepper-items>
                </v-stepper>
              </v-flex>

              <!-- <v-card class='grey lighten-4 mb-2' v-for='(answer, index) in problem.answers' :key='index' :class='config.elevationClass'>
                <v-card-text>
                  <v-layout wrap>
                    <v-flex md12>
                      <div class="vertical-inputs">
                        <div class='vertical-inputs__input'>
                          <v-text-field v-mask="'###'" hide-details v-model='answer.score' label='Балл'></v-text-field>
                        </div>
                      </div>
                    </v-flex>
                  </v-layout>
                  <TextEditor style='height: 400px !important' class='mb-5' v-model='answer.text' />
                </v-card-text>
              </v-card> -->
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
   </v-layout>
</template>

<script>

import { DataSelect, TextEditor } from '@/components/UI'
import { MODEL_DEFAULTS, PROBLEM_DEFAULTS, ANSWER_DEFAULTS, API_URL } from '@/components/Test'

export default {
  components: { DataSelect, TextEditor },
  
  data() {
    return {
      dialog: false,
      loading: false,
      saving: false,
      item: MODEL_DEFAULTS,
      step: 1,
      answer_step: 1,
      show_answers: true,
    }
  },

  watch: {
    step(newVal, oldVal) {
      if (newVal === 999) {
        this.step = oldVal
        this.addProblem()
      }
      this.relodAnswers()
    },

    answer_step(newVal, oldVal) {
      if (newVal === 999) {
        this.answer_step = oldVal
        this.addAnswer(this.currentProblem)
      }
    },
  },

  methods: {
    open(id = null) {
      this.dialog = true
      this.step = null
      if (id !== null) {
        this.loadData(id)
      } else {
        this.item = MODEL_DEFAULTS
        this.step = 1
        this.loading = false
      }
    },

    loadData(id) {
      this.loading = true
      axios.get(apiUrl(API_URL, id)).then(r => {
        this.item = r.data
        if (this.item.problems.length) {
          this.step = 1
        }
        this.loading = false
      })
    },

    addProblem() {
      this.item.problems.push(clone(PROBLEM_DEFAULTS))
      Vue.nextTick(() => this.step = this.item.problems.length)
    },

    addAnswer(problem) {
      problem.answers.push(clone(ANSWER_DEFAULTS))
      Vue.nextTick(() => this.answer_step = this.currentProblem.answers.length)
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(`${API_URL}/${this.item.id}`), this.item).then(r => {
          // this.item = r.data
        })
      } else {
        await axios.post(apiUrl(API_URL), this.item).then(r => {
          // this.item = r.data
        })
      }
      this.$emit('updated')
      this.dialog = false
      setTimeout(() => this.saving = false, 300)
    },

    removeProblem() {
      const removeIndex = this.step - 1
      this.step = 1
      this.item.problems.splice(removeIndex, 1)
    },

    removeAnswer() {
      const removeIndex = this.answer_step - 1
      this.answer_step = 1
      this.currentProblem.answers.splice(removeIndex, 1)
      this.relodAnswers()
    },

    relodAnswers() {
      this.show_answers = false
      Vue.nextTick(() => this.show_answers = true)
    }
  },

  computed: {
    currentProblem() {
      return this.item.problems[this.step - 1]
    }
  }
}
</script>

<style lang='scss'>
  .test-dialog {
    & .v-stepper, .v-stepper__header {
      box-shadow: none;
    }
    & .v-stepper__content {
      padding: 0 18px;
    }
    & .v-stepper__header {
      & .v-stepper__step:last-of-type {
        & .v-stepper__step__step {
          display: none !important;
        }
      }
    }
    & .quillWrapper {
      height: 50vh !important;
    }
    .custom-toolbar {
      position: absolute;
      right: 8px;
      top: -5px;
      & > div {
        margin-right: 10px;
        width: 250px;
      }
    }
  }
</style>

