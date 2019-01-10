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
        <v-card-text class='relative'>
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
                <div>
                  <v-btn fab dark small color="red" class='ml-0' @click='addProblem'>
                    <v-icon dark>add</v-icon>
                  </v-btn>
                  <span>добавить вопрос</span>
                </div>
              </v-flex>
              
              <v-flex md12 v-if='item.problems.length'>
                <v-stepper v-model="step">
                  <v-stepper-header>
                    <template v-for='(problem, index) in item.problems'>
                      <v-stepper-step editable :complete="step > index" :step="(index + 1)">
                        Вопрос {{ index + 1 }}
                      </v-stepper-step>
                      <v-divider v-if="index + 1 < item.problems.length"></v-divider>
                    </template>
                  </v-stepper-header>
                  <v-stepper-items>
                    <v-stepper-content v-for='(problem, index) in item.problems' :step="(index + 1)" :key='index'>
                      <VueEditor style='height: 400px' class='mb-5'
                        :editorOptions="editorSettings"
                        v-model='problem.text'
                      />
                      <AddBtn @click.native="addAnswer(problem)" label='добавить ответ' class='d-inline-block my-3'></AddBtn>
                      <v-card class='elevate-3 grey lighten-4 mb-2' v-for='(answer, index) in problem.answers' :key='index'>
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
                          <VueEditor style='height: 400px' class='mb-5'
                            :editorOptions="editorSettings"
                            v-model='answer.text'
                          />
                        </v-card-text>
                      </v-card>
                    </v-stepper-content>
                  </v-stepper-items>
                </v-stepper>
              </v-flex>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
   </v-layout>
</template>

<script>

import { DataSelect, AddBtn } from '@/components/UI'
import { MODEL_DEFAULTS, PROBLEM_DEFAULTS, ANSWER_DEFAULTS, API_URL } from '@/components/Test'
import { VueEditor, Quill } from 'vue2-editor'
import { ImageDrop } from 'quill-image-drop-module'

Quill.register('modules/imageDrop', ImageDrop)

export default {
  components: { DataSelect, AddBtn, VueEditor },
  
  data() {
    return {
      dialog: false,
      loading: false,
      saving: false,
      item: MODEL_DEFAULTS,
      step: null,
      editorSettings: {
        modules: {
          imageDrop: true
        }
      },
    }
  },

  methods: {
    open(id = null) {
      this.dialog = true
      this.step = null
      if (id !== null) {
        this.loadData(id)
      } else {
        this.item = MODEL_DEFAULTS
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
    }
  },
}
</script>
