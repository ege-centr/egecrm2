<template>
  <div>
    <div class="input-groups" v-if='!passwordChanged'>
      <div v-if='!codeVerified'>
        <div class="group">
          <input type="text" :disabled='codeSent' v-model='email' autocomplete="off" placeholder='email'>
        </div>
        <div class="group" v-if='codeSent'>
          <input type="text" v-model='code' autocomplete="off" placeholder='код из email'>
        </div>
      </div>
      <div v-else>
        <div class="group">
          <input type="password" :disabled='passwordChanged' v-model='newPassword' autocomplete="off" placeholder='новый пароль'>
        </div>
        <div class="group">
          <input type="password" :disabled='passwordChanged' v-model='newPasswordConfirmation' autocomplete="off" placeholder='повторите пароль'>
        </div>
      </div>
      <div class="group">
        <div class="btn btn-submit" 
          :class="{
            'btn--disabled': !correctEmail || loading
          }" 
        >
          <button @click='next'>далее</button>
        </div>
      </div>
    </div>

    <div v-else class='login-success-text font-weight-medium'>
      пароль успешно изменён
    </div>

    <div class='login-bottom-links'>
      <a @click="$emit('message', null); $emit('back')">назад</a>
    </div>
  </div>
</template>

<script>

const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
const API_URL = 'reset-password'

export default {
  data() {
    return {
      email: '',
      code: '',
      message: null,
      loading: false,

      codeSent: false,
      codeVerified: false,
      passwordChanged: false,

      newPassword: '',
      newPasswordConfirmation: ''
    }
  },
  
  methods: {
    async next() {
      this.$emit('message', null)
      this.loading = true
      if (! this.codeSent) {
        await this.sendCode()
      } else if (! this.codeVerified) {
        await this.verifyCode()
      } else if (! this.passwordChanged) {
        await this.changePassword()
      }
      this.loading = false
    },

    async sendCode() {
      await axios.post(apiUrl(API_URL, 'send-code'), { email: this.email }).then(r => {
        this.codeSent = true
        this.$emit('message', r.data.text, 'success')
      }).catch(e => {
        this.$emit('message', e.response.data.error)
      })
    },

    async verifyCode() {
      await axios.post(apiUrl(API_URL, 'verify-code'), {
        email: this.email,
        code: this.code,
      }).then(r => {
        this.codeVerified = true
        this.$emit('message', 'введите новый пароль', 'success')
      }).catch(e => {
        this.$emit('message', e.response.data.error)
      })
    },

    async changePassword() {
      await axios.post(apiUrl(API_URL, 'change-password'), {
        email: this.email,
        new_password: this.newPassword,
        new_password_confirmation: this.newPasswordConfirmation,
      }).then(r => {
        this.passwordChanged = true
      }).catch(e => {
        this.$emit('message', 'не удалось изменить пароль')
      })
    },
  },

  computed: {
    correctEmail() {
      return pattern.test(this.email)
    }
  }
}
</script>

<style lang="scss" scoped>
  @import "~sass/login";
</style>