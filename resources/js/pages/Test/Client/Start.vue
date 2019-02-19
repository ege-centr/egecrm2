<template>
  <div>
    <div v-if='started'>
      <Loader v-if='finishing' />
      <div v-else>
        <!-- <div v-if='client_test.results === null'> -->
        <div>
          <h2 class='text-md-center mb-3'>
            <span v-if='!finished'>
              <v-icon>access_time</v-icon> {{ time_left.format("mm:ss") }}
            </span>
            <span class='headline' v-else>
              Результаты теста: {{ client_test.results.score }}</b> из {{ client_test.results.max_score }}
            </span>
          </h2>
          <v-stepper v-model="step" non-linear class='test-process'>
            <v-stepper-header>
              <template v-for='(problem, index) in test.problems'>
                <v-stepper-step editable :step="(index + 1)">
                </v-stepper-step>
                <v-divider v-if="index + 1 < test.problems.length"></v-divider>
              </template>
            </v-stepper-header>
            <v-stepper-items>
              <v-stepper-content v-for='(problem, index) in test.problems' :step="(index + 1)" :key='index'>
                <div class='headline mb-3'>Вопрос {{ index + 1 }}</div>
                <div v-html='problem.text' class='client-problem'></div>

                <div class='headline mb-3 mt-5'>Варианты ответа</div>
                <div class='font-weight-medium caption'>максимальный балл: {{ getProblemMaxScore(problem) }}</div>
                <v-radio-group v-model='answers[problem.id]' hide-details>
                  <div class='flex-items mb-3' v-for='(answer, index) in problem.answers' :key='index'>
                    <v-radio class='ma-0' hide-details color="primary" :value='answer.id' :disabled='finished'></v-radio>
                    <div v-html='answer.text' class='client-answer'></div>
                    <div class='ml-3' v-if='finished'
                      :class="{
                        'green--text': answer.score === getProblemMaxScore(problem),
                        'orange--text': answer.score !== getProblemMaxScore(problem) && answer.score > 0,
                        'red--text': answer.score === 0,
                      }">{{ answer.score }} баллов</div>
                  </div>
                </v-radio-group>
                <div class='text-md-center' v-if='!finished'>
                  <v-btn color='primary' :disabled="!answers.hasOwnProperty(problem.id)"
                    @click='submitAnswer(problem.id, index === problem.answers.length - 1)'>
                    {{ index === problem.answers.length - 1 ? 'завершить тест' : 'ответить' }}
                  </v-btn>
                </div>
              </v-stepper-content>
            </v-stepper-items>
          </v-stepper>
        </div>
      </div>
    </div>
    <div v-else>
      <Loader v-if='loading' />
      <div v-else>
        <div class='intro-text' v-html="intro_text"></div>
        <div class='text-md-center mt-5'>
          <v-btn color='primary' :loading='starting' @click='beginTest'>начать</v-btn>
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
} from '@/components/Test'

const STEP_COOKIE_KEY = 'test_step'

export default {
  props: {
    options: {
      type: Object,
      required: false,
      default() {
        return {}
      }
    }
  },

  data() {
    return {
      intro_text: null,
      test: null,
      data: null,
      loading: true,
      starting: false,
      finishing: false,
      started: false,
      answers: {},
      step: 0,
      now: moment(),
      client_test: null,
    }
  },

  async created() {
    await Settings.get(SETTINGS_KEY).then(r => {
      this.intro_text = r.data
    })
    await axios.get(apiUrl(CLIENT_TESTS_API_URL, this.testId) + queryString({
      client_id: this.clientId,
      // started: 1,
    })).then(r => this.client_test = r.data)
    if (this.client_test.started_at !== null) {
      await this.loadTest()
      await this.loadAnswers()
      this.start()
    }
    this.loading = false
  },

  watch: {
    step(newVal) {
      Cookies.set(STEP_COOKIE_KEY, newVal)
    },
  },

  methods: {
    async beginTest() {
      this.starting = true
      await this.loadTest()
      await axios.put(apiUrl(CLIENT_TESTS_API_URL, this.test.id), {started_at: moment().format('YYYY-MM-DD HH:mm:ss')}).then(r => this.client_test = r.data)
      this.starting = false
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
        this.answers = r.data
      })
    },

    start() {
      this.step = Cookies.get(STEP_COOKIE_KEY) || 0
      if (this.finished) {
        this.step = 0
      }
      setInterval(() => this.now = moment(), 1000)
      Vue.nextTick(() => this.started = true)
    },

    end() {
      this.finishing = true
      axios.put(apiUrl(CLIENT_TESTS_API_URL, this.test.id), {started_at: '0000-00-00 00:00:00'}).then(r => {
        this.client_test = r.data
        this.finishing = false
        this.step = 0
        Cookies.remove(STEP_COOKIE_KEY)
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
      axios.post(apiUrl(CLIENT_TEST_ANSWERS_API_URL), {
        client_id: this.clientId,
        test_problem_answer_id: this.answers[problem_id],
      })
      if (is_last_answer) {
        this.end()
      } else {
        this.step++
      }
    },
  },

  computed: {
    time_left() {
      const time_started = moment(this.client_test.started_at).toDate().getTime()
      const time_now = this.now.toDate().getTime()
      const mins_30 = moment.duration(30, 'minute').valueOf()
      const timestamp = mins_30 - (time_now - time_started)
      if (timestamp < 1000) {
        this.end()
      }
      return moment(timestamp).utcOffset(-180)
    },

    finished() {
      return this.client_test.results !== null
    },

    clientId() {
      return this.options.clientId || this.$store.state.user.id
    },

    testId() {
      return this.options.testId || this.$route.params.id
    }
  }
}
</script>

<style lang="scss"> 
  .client-problem {
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
    & .v-stepper__step--editable:hover {
      background: none !important;
    }
  }
</style>

