<template>
  <div>
    <div v-if='started'>
      <h2 class='text-md-center mb-3'>
        <v-icon>access_time</v-icon> {{ time_left.format("mm:ss") }}
      </h2>
      <v-stepper v-model="step">
        <v-stepper-header>
          <template v-for='(problem, index) in test.problems'>
            <v-stepper-step editable :complete="step > index" :step="(index + 1)">
              <!-- Вопрос {{ index + 1 }} -->
            </v-stepper-step>
            <v-divider v-if="index + 1 < test.problems.length"></v-divider>
          </template>
        </v-stepper-header>
        <v-stepper-items>
          <v-stepper-content v-for='(problem, index) in test.problems' :step="(index + 1)" :key='index'>
            <v-card class='grey lighten-4 mb-4' :class='config.elevationClass'>
              <v-card-text>
                <div v-html='problem.text' class='client-problem'></div>
              </v-card-text>
            </v-card>
            <v-radio-group v-model='answers[problem.id]' hide-details>
              <div class='flex-items mb-3' v-for='(answer, index) in problem.answers' :key='index'>
                <v-radio class='ma-0' hide-details color="primary" :value='answer.id'></v-radio>
                <div v-html='answer.text' class='client-answer'></div>
              </div>
            </v-radio-group>
            <div class='text-md-center'>
              <v-btn color='primary' :disabled="!answers.hasOwnProperty(problem.id)" 
                @click='submitAnswer(problem.id)'>
                {{ index === problem.answers.length - 1 ? 'завершить тест' : 'ответить' }}
              </v-btn>
            </div>
          </v-stepper-content>
        </v-stepper-items>
      </v-stepper>
    </div>
    <div v-else>
      <Loader v-if='loading' />
      <div v-else>
        <div v-html="intro_text"></div>
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
  data() {
    return {
      intro_text: null,
      test: null,
      data: null,
      loading: true,
      starting: false,
      started: false,
      answers: {},
      step: 0,
      started_at: null,
      now: moment(),
    }
  },

  async created() {
    await Settings.get(SETTINGS_KEY).then(r => {
      this.intro_text = r.data
    })
    await axios.get(apiUrl(CLIENT_TESTS_API_URL, this.$route.params.id) + queryString({
      client_id: this.$store.state.user.id,
      started: 1,
    })).then(r => {
      if (r.data) {
        this.started_at = r.data.started_at
      }
    })
    if (this.started_at !== null) {
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
      await axios.put(apiUrl(CLIENT_TESTS_API_URL, this.test.id)).then(r => this.started_at = r.data.started_at)
      this.starting = false
      this.step = 0
      this.start()
    },

    async loadTest() {
      await axios.get(apiUrl(API_URL, this.$route.params.id)).then(r => {
        this.test = r.data
      })
    },

    async loadAnswers() {
      // TODO: тут подгружаются все ответы, это неправильно
      await axios.get(apiUrl(CLIENT_TEST_ANSWERS_API_URL) + queryString({
        client_id: this.$store.state.user.id,
      })).then(r => {
        this.answers = r.data
      })
    },

    start() {
      this.step = Cookies.get(STEP_COOKIE_KEY) || 0
      setInterval(() => this.now = moment(), 1000)
      Vue.nextTick(() => this.started = true)
    },

    end() {

    },

    submitAnswer(problem_id) {
      this.step++
      axios.post(apiUrl(CLIENT_TEST_ANSWERS_API_URL), {
        client_id: this.$store.state.user.id,
        test_problem_answer_id: this.answers[problem_id],
      })
    },
  },

  computed: {
    time_left() {
      const time_started = moment(this.started_at).toDate().getTime()
      const time_now = this.now.toDate().getTime()
      const mins_30 = moment.duration(30, 'minute').valueOf()
      const timestamp = mins_30 - (time_now - time_started)
      if (timestamp < 1000) {
        this.end()
      }
      return moment(timestamp).utcOffset(-180)
      // return moment.utc(moment(this.started_at).diff(this.now) - moment.duration(30, 'minute').valueOf())
    }
  }
}
</script>

<style lang="scss"> 
  .client-problem {
    & img {
      width: 50%;
    }
  }

  .client-answer {
    & p {
      margin-bottom: 0;
    }
  }
</style>

