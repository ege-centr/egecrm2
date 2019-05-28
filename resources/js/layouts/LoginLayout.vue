<template>
  <div>
    <Loader v-if='backgroundLoading' />
    <center autocomplete="off" class="login-form animated fadeIn" v-else>
        <div class="login-logo group">
            <Avatar v-if='lastLoggedUser' :photo='lastLoggedUser.photo' :size='54' />
            <img v-else src="/img/svg/logo.svg" />
        </div>
        <div class="input-groups" v-if='!forgotPasswordScreen'>
            <div class="group" v-if='lastLoggedUser'>
                <input :disabled="true" type="text" :value='lastLoggedUser.default_name' autocomplete="username">
            </div>
            <div class="group" v-else>
                <input :disabled="sms_verification" type="text" placeholder="логин"
                  ref="login" v-model="credentials.login" autocomplete="username" @keyup.enter="imitateSubmit">
            </div>
            <div class="group">
                <input :disabled="sms_verification" type="text" placeholder="пароль" ref="password" class='password-input'
                  v-model="credentials.password" autocomplete="new-password" @keyup.enter="imitateSubmit">
            </div>
            <div class="group" v-show="sms_verification">
                <input type="text" id="sms-code" placeholder="sms code" @keyup.enter="imitateSubmit"
                  v-model="credentials.code" autocomplete="off">
            </div>
            <div class="group">
              <div class="btn btn-submit" :class="{'btn--disabled': loading}" ref='submit'>
                <button @click="callback">войти</button>
              </div>
            </div>
        </div>
        <ForgotPassword
          @message='setMessage'
          @back='forgotPasswordScreen = false' 
        v-else />
        <div class='login-bottom-links' v-if='!forgotPasswordScreen'>
          <a v-if='lastLoggedUser' @click='forgetLastUser'>другой пользователь</a>
          <a v-else @click='forgotPasswordScreen = true'>забыли пароль?</a>
        </div>
        <div v-if="message !== null" class="login-message" :class="{
          'login-message_success': message.type === 'success',
          'login-message_error': message.type === 'error',
        }" v-html='message.text'></div>
        
        <span v-if='backgroundAllowed && background.admin !== null' class="wallpaper-by animated fadeInRight">
          <span v-if='background.title'>
            {{ background.title }} –
          </span>
          by {{ background.admin.default_name }}
        </span>
    </center>
  </div>
</template>

<script>
  import gRecaptcha from '@finpo/vue2-recaptcha-invisible';
  import Cookies from 'js-cookie';
  import ForgotPassword from '@/components/ForgotPassword'

  const TMP_CREDENTIALS_KEY = 'tmp-credentials'
  const API_URL = 'login'
  const LAST_LOGGED_USER_KEY = 'lastLoggedUser'

  export default {
    components: { gRecaptcha, ForgotPassword },

    data() {
      return {
        credentials: {},
        loading: false,
        backgroundLoading: false,
        sms_verification: false,
        message: null,
        lastLoggedUser: null,
        forgotPasswordScreen: false,
        backgroundAllowed: false,
      }
    },

    created() {
      if (LAST_LOGGED_USER_KEY in localStorage) {
        this.lastLoggedUser = JSON.parse(localStorage.getItem(LAST_LOGGED_USER_KEY))
        this.credentials.login = this.lastLoggedUser.email.email
        this.backgroundAllowed = true
        this.loadBackground()
      } else {
        $('body').css({
          backgroundImage: null
        })
      }

      this.MIX_RECAPTCHA_SITE = process.env.MIX_RECAPTCHA_SITE
      const tmp_credentials = Cookies.getJSON(TMP_CREDENTIALS_KEY)
      if (tmp_credentials) {
        this.credentials = tmp_credentials
        this.sms_verification = true
      }
    },

    destroyed() {
      $('body').css({'background-image': ''})
    },

    methods: {
      validate() {
        if (! this.credentials.login) {
          this.$refs.login.focus()
          return false
        }
        if (! this.credentials.password) {
          this.$refs.password.focus()
          return false
        }
        return true
      },

      callback(token) {
        this.loading = true
        this.message = null
        axios.post(apiUrl(API_URL), {
          credentials: this.credentials,
          token
        }).then(response => {
          switch(response.status) {
            // подтверждение по смс
            case 202:
              this.sms_verification = true
              this.loading = false
              var {login, password} = this.credentials
              Cookies.set(TMP_CREDENTIALS_KEY, {login, password} , { expires: 1 / (24 * 60) * 2, path: '/' })
              break
            default:
              Cookies.remove(TMP_CREDENTIALS_KEY)
              if (window.location.search.substr(1).indexOf('url=') === 0) {
                // логин с другого сайта
                // отработает Sso middleware и редеректнет куда надо
                location.reload()
              } else {
                // логин локально
                // this.$store.commit('setUser', response.data)
                localStorage.setItem('lastLoggedUser', JSON.stringify(response.data))
                // this.$router.push(window.location.pathname)
                // this.loading = false
                setTimeout(() => {
                  location.reload()
                }, 300);
              }
          }
        }).catch(e => {
          // this.setMessage(e.response.data)
          this.setMessage('в доступе отказано')
          this.loading = false
        })
      },

      imitateSubmit() {
        this.$refs.submit.querySelector('button').click()
      },

      forgetLastUser() {
        localStorage.removeItem(LAST_LOGGED_USER_KEY)
        this.credentials = {}
        this.lastLoggedUser = null
      },

      setMessage(text, type = 'error') {
        if (text === null) {
          this.message = null
        } else {
          this.message = { text, type }
        }
      },

      loadBackground() {
        this.backgroundLoading = true
        let img = new Image
        img.addEventListener('load', () => {
          $('body').css({
            backgroundImage: this.background.url
          })
          this.backgroundLoading = false
        })
        img.src = this.background.src
      },
    },

    computed: {
      background() {
        return this.$store.state.data.background
      }
    }
  }
</script>

<style lang="scss">
  @import "~sass/login";
</style>
