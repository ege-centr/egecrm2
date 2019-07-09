<template>
   <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ true ? 'Редактирование' : 'Добавление' }} теста</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark icon v-if='edit_mode' @click.native="destroy" :loading='destroying' class='mr-5' v-show='item.has_clients !== true'>
              <v-icon>delete</v-icon>
            </v-btn>
            <v-btn dark icon @click.native="storeOrUpdate" :loading='saving' v-show='item.has_clients !== true'>
              <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
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
                  <div class='vertical-inputs__input'>
                    <v-text-field 
                      :error-messages="errorMessages.minutes"
                      :hide-details="errorMessages.minutes === undefined"
                      v-model='item.minutes' 
                      v-mask="'###'"
                      label='Минут на выполнение' 
                    />
                  </div>
                </div>
              </v-flex>
              
              <v-flex md12 style='margin-top: 100px'>
                <v-stepper v-model="step" non-linear>
                  <v-stepper-header>
                    <div class='headline mb-1'>
                      Вопросы
                    </div>
                    <template v-for='(problem, index) in item.problems'>
                      <v-stepper-step editable :step="(index + 1)">
                      </v-stepper-step>
                      <v-divider></v-divider>
                    </template>
                    <v-stepper-step :step='999' editable>
                      <v-icon color='red' style='font-size: 28px'>add_circle</v-icon>
                    </v-stepper-step>
                  </v-stepper-header>
                  <v-stepper-items>
                    <v-stepper-content v-for='(problem, index) in item.problems' :step="(index + 1)" :key='index'>
                      <div class='relative'>
                        <TextEditor v-model='problem.text' />
                        <div class='custom-toolbar'>
                          <div
                            class='pointer'
                            @click='removeProblem'
                            v-show='!(step === 1 && item.problems.length <= 1)'>
                            <v-icon color='red'>remove_circle_outline</v-icon>
                          </div>
                        </div>
                      </div>
                    </v-stepper-content>
                  </v-stepper-items>
                </v-stepper>
              </v-flex>

              <v-flex md12 v-if='step > 0 && showAnswers' class='mt-5'>
                <v-stepper v-model="answerStep" non-linear>
                  <v-stepper-header>
                    <div class='headline mb-1'>
                      Ответы
                    </div>
                    <template v-for='(answer, index) in currentProblem.answers' :step="(index + 1)">
                      <v-stepper-step editable :step="(index + 1)">
                      </v-stepper-step>
                      <v-divider></v-divider>
                    </template>
                    <v-stepper-step :step='999' editable>
                      <v-icon color='red' style='font-size: 28px'>add_circle</v-icon>
                    </v-stepper-step>
                  </v-stepper-header>
                  <v-stepper-items>
                    <v-stepper-content v-for='(answer, index) in currentProblem.answers' :step="(index + 1)" :key='index'>
                      <div class='relative'>
                        <TextEditor v-model='answer.text' />
                        <div class='custom-toolbar'>
                          <div
                            class='pointer'
                            @click='removeAnswer'
                            v-show='!(answerStep === 1 && currentProblem.answers.length <= 1)'>
                            <v-icon color='red'>remove_circle_outline</v-icon>
                          </div>
                        </div>
                        <v-text-field style='width: 100px; position: absolute;top: -3px;right: 20px' 
                          class='hide-bottom-border'
                          v-mask="'###'" hide-details v-model='answer.score' label='Балл'></v-text-field>
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
import { DialogMixin } from '@/mixins'

export default {
  components: { DataSelect, TextEditor },

  mixins: [ DialogMixin ],
  
  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      step: 1,
      answerStep: 1,
      showAnswers: true,
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

    answerStep(newVal, oldVal) {
      if (newVal === 999) {
        this.answerStep = oldVal
        this.addAnswer(this.currentProblem)
      }
    },
  },

  methods: {
    beforeOpen() {
      this.step = null
    },

    afterOpen(itemId) {
      if (itemId === null) {
        this.step = 1
      }
    },

    afterLoad() {
      this.step = 1
    },

    addProblem() {
      this.item.problems.push(clone(PROBLEM_DEFAULTS))
      Vue.nextTick(() => this.step = this.item.problems.length)
    },

    addAnswer(problem) {
      problem.answers.push(clone(ANSWER_DEFAULTS))
      Vue.nextTick(() => this.answerStep = this.currentProblem.answers.length)
    },

    removeProblem() {
      const removeIndex = this.step - 1
      this.step = 1
      this.item.problems.splice(removeIndex, 1)
    },

    removeAnswer() {
      const removeIndex = this.answerStep - 1
      this.answerStep = 1
      this.currentProblem.answers.splice(removeIndex, 1)
      this.relodAnswers()
    },

    relodAnswers() {
      this.showAnswers = false
      Vue.nextTick(() => this.showAnswers = true)
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
      justify-content: flex-start;
      align-items: center;
    }

    & .v-stepper__step__step {
      margin-right: 0;
    }

    & .v-stepper__step--editable:hover {
      background: none !important;
      // & .v-ripple__container {
      //   display: none;
      // }
    }
    
    & .v-divider {
      max-width: 50px !important;
    }

    & .v-stepper__content {
      padding-left: 0;
      padding-right: 0;
    }
    & .v-stepper__header {
      & .v-stepper__step:last-of-type {
        & .v-stepper__step__step {
          display: none !important;
        }
      }
    }
    & .quillWrapper {
      min-height: 50vh;
      height: auto !important;
    }
    .custom-toolbar {
      position: absolute;
      left: 184px;
      top: 10px;
      & > div {
        margin-right: 10px;
        width: 250px;
      }
    }
  }
</style>

