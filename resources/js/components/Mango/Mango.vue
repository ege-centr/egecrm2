<template>
  <div>
    <v-fade-transition>
      <div class='mango' :class='config.elevationClass' v-if='dialog'>
        <div class='mango__title'>
          <div class='flex-items'>
            <div class='mango__header-item'>
              <div class='font-weight-medium mb-1'>абонент</div>
              <div class='title'>+{{ mango.from.number }}</div>
            </div>
            <v-icon color='white' class='mx-3 pt-4'>trending_flat</v-icon>
            <div class='mango__header-item'>
              <div class='font-weight-medium mb-1'>шлюз</div>
              <div class='title'>+{{ mango.to.number }}</div>
            </div>
            <v-slide-x-transition>
              <div class='flex-items' v-if='answered'>
                <v-icon color='white' class='mx-3 pt-4'>trending_flat</v-icon>
                <div class='mango__header-item'>
                  <div class='invisible mb-1'>-</div>
                  <div class='title'>разговор</div>
                </div>
              </div>
            </v-slide-x-transition>
          </div>
          <v-spacer></v-spacer>
          <v-btn class='ma-0' icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
        </div>
        <div class='mango__content'>
          <Loader v-if='result === null' />
          <div v-else>
            <!-- Заявки -->
            <div class='mango__result' v-if='result.requests.length > 0'>
              <div class='body-2'>Заявки:</div>
              <div v-for='item in result.requests'>
                <div class='flex-items'>
                  <router-link :to="{ name: 'RequestShow', params: {id: item.id}}">
                    {{ item.id }}
                  </router-link>
                  <div class='ml-3 grey--text'>
                    {{ item.relative_ids.join(', ') }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Клиенты -->
            <div class='mango__result mt-3' v-if='result.clients.length > 0'>
              <div class='body-2'>Клиенты:</div>
              <div v-for='item in result.clients'>
                <router-link :to="{ name: 'ClientShow', params: {id: item.id}}">
                  {{ item.id }}
                </router-link>
              </div>
            </div>

            <!-- Преподаватели -->
            <div class='mango__result mt-3' v-if='result.tutors.length > 0'>
              <div class='body-2'>Преподаватели:</div>
              <div v-for='item in result.tutors'>
                <router-link :to="{ name: 'TeacherShow', params: {id: item.id}}">
                  {{ item.id }}
                </router-link>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </v-fade-transition>
  </div>
</template>

<script>
import { API_URL } from '@/components/Search'

export default {
  data() {
    return {
      dialog: false,
      result: null,
      answered: false,
      mango: null,
      timeoutId: null,
    }
  },

  created() {
    this.pusher.on('Mango\\IncomingCall', (data) => {
      this.mango = data.data
      this.loadData()
      this.dialog = true
    })

    this.pusher.on('Mango\\AnsweredCall', (data) => {
      this.answered = true
      this.closeDialogTimeout(3)
    })
  },
  
  methods: {
    loadData(text) {
      this.closeDialogTimeout(20)
      this.result = null
      this.answered = false
      axios.get(apiUrl(API_URL), {
        params: {
          text: this.mango.from.number
        }
      }).then(r => {
        this.result = r.data
      })
    },

    closeDialogTimeout(seconds) {
      clearTimeout(this.timeoutId)
      this.timeoutId = setTimeout(() => this.dialog = false, seconds * 1000)
    }
  }
}
</script>

<style lang='scss'>
  .mango {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1500;
    width: 650px;
    &__title {
      padding: 20px 25px;
      display: flex;
      color: white;
      align-items: center;
      background: #69b881;
    }
    &__header-item {
      & > div {
        &:first-child {
          opacity: .6;
        }
      }
    }
    &__content {
      background: white;
      padding: 25px;
      position: relative;
      min-height: 150px;
      overflow: hidden;
    }

    &__result {

    }
  }
</style>