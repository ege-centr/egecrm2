<template>
  <div>
    <div v-if='started'>
      <Loader v-if='finishing' />
      <div v-else>
        <div class='mb-2'>
          <span class='title' v-if='client_test.is_finished'>
            Результаты теста: {{ client_test.results.score }} из {{ client_test.results.max_score }}
          </span>
          <div class='flex-items align-center' v-else>
            <div class='flex-items align-center'>
              <v-icon class='mr-1'>access_time</v-icon>
              <span class='mr-1'>Времени осталось:</span>
              <TestCountDown 
                :minutes='test.minutes'
                :from='client_test.started_at' 
                @end='end()' />
            </div>
            <v-spacer></v-spacer>
            <v-btn small color='primary' @click='end()'>Завершить тестирование</v-btn>
          </div>
        </div>
        <v-stepper v-model="step" non-linear class='test-process' :class="{'box-shadow-none': client_test.is_finished}">
          <v-stepper-header>
            <template v-for='(problem, index) in test.problems'>
              <v-stepper-step editable :step="(index + 1)" :class="{
                unanswered: !submittedAnswers.hasOwnProperty(problem.id)
              }"></v-stepper-step>
              <v-divider v-if="index + 1 < test.problems.length"></v-divider>
            </template>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content v-for='(problem, index) in test.problems' :step="(index + 1)" :key='index'>
              <div class='mb-2 font-weight-bold' style="font-size: 28px">Вопрос {{ index + 1 }}</div>
              <div v-html='problem.text' class='client-problem'></div>

              <div class='mt-5 font-weight-bold flex-items' style="font-size: 28px; align-items: flex-end">
                <span class='mr-2'>Варианты ответа</span>
                <div class='font-weight-medium grey--text' style='font-size: 14px; font-weight: normal !important; top: -7px; position: relative;'>
                  Кол-во баллов за верный ответ: {{ getProblemMaxScore(problem) }}
                </div>
              </div>
              <v-radio-group v-model='answers[problem.id]' hide-details>
                <div class='flex-items mb-3' v-for='(answer, index) in problem.answers' :key='index'>
                  <v-radio class='ma-0' hide-details color="primary" :value='answer.id' :disabled='client_test.is_finished'></v-radio>
                  <div v-html='answer.text' class='client-answer'></div>
                  <div class='ml-3 font-weight-bold' style='font-size: 14px; position: relative; top: 2px' v-if='client_test.is_finished && (showAllAnswers || answers[problem.id] === answer.id)'
                    :class="{
                      'green--text': answer.score === getProblemMaxScore(problem),
                      'orange--text': answer.score !== getProblemMaxScore(problem) && answer.score > 0,
                      'red--text': answer.score === 0,
                    }">{{ answer.score }} баллов</div>
                </div>
              </v-radio-group>
              <div v-if='!client_test.is_finished' class='mt-2 flex-items align-center justify-space-between'>
                <v-btn 
                  small 
                  color='primary' 
                  class='mx-0' 
                  :disabled="isSubmitAnswerDisabled(problem.id)" 
                  :loading='submittingAnswer' 
                  @click='submitAnswer(problem.id, index === test.problems.length - 1)'>
                  {{ (problem.id in submittedAnswers) ? 'Изменить ответ' : 'Подтвердить ответ' }}
                </v-btn>
                <v-btn class='mx-0' 
                  :disabled="index === test.problems.length - 1"
                  @click='step++'
                  small color='gray'>пропустить вопрос и вернуться к нему позже</v-btn>
              </div>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </div>
    </div>
    <div v-else>
      <Loader v-if='loading' />
      <div v-else>
        <!-- <div class='intro-text' v-html="intro_text"></div>  -->
        <div class='test-intro__text'>
          <div style='font-size: 36px'>Добро пожаловать на тестирование!</div>
          <p>На этом вводном этапе мы расскажем вам о том, какие задачи вас ждут, и дадим рекомендации по прохождению теста.</p>

          <div>Цель подобного тестирования</div>
          <p>Определение приблизительного уровня знаний для дальнейшего распределения ученика в группу соответствующего уровня.</p>

          <div>Пояснения к тестированию</div>
          <p>Тест возможно пройти всего один раз. Пожалуйста, выполняйте тестирование самостоятельно, не прибегая к помощи других.</p>

          <div>Время тестирования</div>
          <p>Для прохождения этого теста вам потребуется <span class='green font-weight-medium darken-4 white--text' style='padding: 2px 4px; border-radius: 2px' v-if='test'>{{ test.minutes }} минут</span>. Во время прохождения теста вас никто не должен отвлекать. Тест можно пройти только 1 раз.</p>
        </div>
        <div class='test-intro'>
           <v-tooltip bottom>
            <div slot='activator' 
              style='top: 32px; left: 258px'
              class='test-intro__hint'></div>
            <span>
              Это количество времени, которое у вас осталось. Тест нельзя поставить на паузу. Когда время закончится, тест будет завершен автоматически
            </span>
          </v-tooltip>
          <v-tooltip bottom>
            <div slot='activator' 
              style='top: 25px; left:820px'
              class='test-intro__hint'></div>
            <span>
              Тест можно пройти всего 1 раз. Если у вас осталось время - используйте его для дополнительной проверки ответов
            </span>
          </v-tooltip>
          <v-tooltip bottom>
            <div slot='activator' 
              style='top: 240px; left:486px'
              class='test-intro__hint'></div>
            <span>
              Начисленное количество баллов за верный ответ. Кстати, верных вариантов ответа может быть несколько
            </span>
          </v-tooltip>
          <v-tooltip bottom>
            <div slot='activator' 
              style='top: 352px; left:104px'
              class='test-intro__hint'></div>
            <span>
              Выбрать можно только 1 вариант ответа. После выбора варианта нажмите "Подтвердить ответ"
            </span>
          </v-tooltip>
          <v-tooltip bottom>
            <div slot='activator' 
              style='top: 460px; left:248px'
              class='test-intro__hint'></div>
            <span>
              После выбора ответа нажмите "подтвердить ответ". Позже вы сможете вернуться к вопросу и изменить ответ
            </span>
          </v-tooltip>
          <v-tooltip bottom>
            <div slot='activator' 
              style='top: 460px; left:686px'
              class='test-intro__hint'></div>
            <span>
              Если вы не знаете или не уверены как ответить правильно, нажмите на эту кнопку и вернитесь к вопросу позже. Это позволит вам выиграть время и проходить тест более эффективно
            </span>
          </v-tooltip>
          <v-tooltip bottom>
            <div slot='activator' 
              style='top: 70px; left:303px'
              class='test-intro__hint'></div>
            <span>
              Так отмечаются вопросы с установленным вариантом ответа
            </span>
          </v-tooltip>
          <v-tooltip bottom>
            <div slot='activator' 
              style='top: 70px; left:364px'
              class='test-intro__hint'></div>
            <span>
              Так отмечаются вопросы с пропущенными ответами
            </span>
          </v-tooltip>
          <img class='test-intro__screen' src='/img/test-intro-screen.png' />
        </div>
        <div class='test-intro__text mt-3'>
          <div>Всё понятно? Теперь можете перейти к тестированию. Удачи!</div>
        </div>
        <div class='mt-4'>
          <v-btn class='ma-0' color='primary' :loading='starting' @click='beginTest'>Начать тестирование</v-btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Settings from '@/other/settings'
import { 
  SETTINGS_KEY, 
  API_URL, 
  CLIENT_TESTS_API_URL, 
  CLIENT_TEST_ANSWERS_API_URL,
  stepCookieKey,
} from '@/components/Test'
import TestCountDown from '@/components/Test/CountDown'

export default {
  props: {
    options: {
      type: Object,
      required: false,
      default() {
        return {}
      }
    },

    showAllAnswers: {
      type: Boolean,
      default: false,
    }
  },

  components: { TestCountDown },
  
  data() {
    return {
      intro_text: null,
      test: null,
      data: null,
      loading: true,
      starting: false,
      finishing: false,
      submittingAnswer: false,
      started: false,
      answers: {},
      submittedAnswers: {},
      step: 0,
      client_test: null,
    }
  },

  async created() {
    $('body').addClass('test-page')
    await this.loadTest()
    this.pusher.on('ClientTestDestroyed', (data) => {
      if (data.client_id === this.$store.state.user.id) {
        this.$router.push({name: 'TestIndex'})
      }
    })
    await Settings.get(SETTINGS_KEY).then(r => {
      this.intro_text = r.data
    })
    await axios.get(apiUrl(CLIENT_TESTS_API_URL, this.testId) + queryString({
      client_id: this.clientId,
      // started: 1,
    })).then(r => this.client_test = r.data)
    if (this.client_test.started_at !== null) {
      await this.loadAnswers()
      this.start()
    }
    this.loading = false
  },

  watch: {
    step(newVal) {
      Cookies.set(stepCookieKey(this.testId), newVal)
    },
  },

  destroyed() {
    $('body').removeClass('test-page')
  },

  methods: {
    async beginTest() {
      this.starting = true
      await this.loadTest()
      await axios.put(apiUrl(CLIENT_TESTS_API_URL, this.testId), {
        started_at: moment().format('YYYY-MM-DD HH:mm:ss')
        // debug
        // started_at: moment().subtract(29, 'minutes').subtract(40, 'seconds').format('YYYY-MM-DD HH:mm:ss')
      })
        .then(r => this.client_test = r.data)
        .catch(e => this.$router.push({name: 'TestIndex'}))
      this.starting = false
      Cookies.remove(stepCookieKey(this.testId))
      this.step = 0
      this.start()
    },

    async loadTest() {
      await axios.get(apiUrl(API_URL, this.testId)).then(r => {
        this.test = r.data
      })
    },

    async loadAnswers() {
      // TODO: тут подгружаются все ответы, это неправильно
      await axios.get(apiUrl(CLIENT_TEST_ANSWERS_API_URL) + queryString({
        client_id: this.clientId,
      })).then(r => {
        this.answers = (r.data === [] ? {} : r.data)
        this.submittedAnswers = _.clone(this.answers)
      })
    },

    start() {
      this.step = Cookies.get(stepCookieKey(this.testId)) || 0
      if (this.client_test.is_finished) {
        this.step = 0
      }
      Vue.nextTick(() => this.started = true)
    },

    end() {
      this.finishing = true
      axios.put(apiUrl(CLIENT_TESTS_API_URL, this.testId), {
        is_finished_manually: true
      })
      .then(r => {
        this.client_test = r.data
        this.finishing = false
        this.step = 0
        Cookies.remove(stepCookieKey(this.testId))
      })
    },

    getProblemMaxScore(problem) {
      let max_score = 0
      problem.answers.forEach(answer => {
        if (answer.score > max_score) {
          max_score = answer.score
        }
      })
      return max_score
    },

    submitAnswer(problem_id, is_last_answer) {
      this.submittingAnswer = true
      axios.post(apiUrl(CLIENT_TEST_ANSWERS_API_URL), {
        client_id: this.clientId,
        client_test_id: this.client_test.id,
        test_problem_answer_id: this.answers[problem_id],
      }).then(r => {
        this.submittingAnswer = false
        this.submittedAnswers[problem_id] = r.data.test_problem_answer_id
        if (is_last_answer) {
          // this.end()
        } else {
          this.step++
        }
      }).catch(e => this.submittingAnswer = false)
    },

    isSubmitAnswerDisabled(problemId) {
      return !this.answers.hasOwnProperty(problemId)
        || ((problemId in this.submittedAnswers) && this.submittedAnswers[problemId] === this.answers[problemId])
    }
  },

  computed: {
    clientId() {
      return this.options.clientId || this.$store.state.user.id
    },

    testId() {
      return this.options.testId || this.$route.params.id
    },
  }
}
</script>

<style lang="scss"> 

  .test-page {
    & .v-tooltip__content {
      background: #1B5E20 !important;
      color: white !important;
    }
  }

  .client-problem, .client-answer {
    & img {
      max-width: 100%;
      zoom: 50%;
    }
  }

  .intro-text img {
    max-width: 100%;
    zoom: 50%;
  }

  .client-answer {
    & p {
      margin-bottom: 0;
      font-size: 14px !important;
      top: 2px;
      position: relative;
    }
  }

  .test-results {
    height: 300px;
    display: flex;
    align-items: center;
  }

  .test-process {
    .v-stepper__header {
      box-shadow: none !important;
    }
    & .v-stepper__step__step {
      margin-right: 0;
    }
    & .unanswered:not(.v-stepper__step--active) {
      & > .v-stepper__step__step {
        background: white !important;
        color: #9e9e9e;
        border: 1px solid #9e9e9e;
      }
    }
    & .v-stepper__step--editable:hover {
      background: none !important;
    }
  }

  .test-intro {
    position: relative;
    &__screen {
      width: 1000px;
      margin-left: 50px;
    }
    &__hint {
      background: #1B5E20;
      color: white;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 10px;
      width: 10px;
      border-radius: 50%;
      position: absolute;
      &:hover {
        background: #43A047;
      }
    }
    &__text {
      & div {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 5px;
      }
      & p {
        margin-bottom: 25px;
      }
    }
  }
</style>

