<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card v-if='dialog_model !== null'>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ dialog_model.id ? 'Редактирование' : 'Добавление' }} пользователя</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='loading.dialog'>{{ dialog_model.id ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <v-container grid-list-xl class="pa-0 ma-0" fluid>
            <v-layout wrap row>
              <v-flex md12 class='headline'>
                Основное
              </v-flex>

              <div class='mr-4 mb-5'>
                <v-flex>
                  <AvatarLoader class-name='Admin\Admin' :entity-id='dialog_model.id' :photo='dialog_model.photo' @photoChanged='photoChanged' />
                </v-flex>
              </div>

              <v-flex d-flex md8>
                <v-layout row wrap>
                  <v-flex md3>
                    <v-text-field v-model="dialog_model.first_name" label="Имя"></v-text-field>
                  </v-flex>
                  <v-flex md3>
                    <v-text-field v-model="dialog_model.last_name" label="Фамилия"></v-text-field>
                  </v-flex>
                  <v-flex md3>
                    <v-text-field v-model="dialog_model.first_name" label="Отчество"></v-text-field>
                  </v-flex>
                  <v-flex md3>
                    <v-text-field v-model="dialog_model.email.email" label="Email"></v-text-field>
                  </v-flex>
                  <Phones :item='dialog_model' />
                </v-layout>
              </v-flex>


              <v-flex md12 class='headline'>
                IP адреса
              </v-flex>
              <v-flex md12 py-0>
                  <div v-for="(ip, index) in dialog_model.ips" :key='ip.id' class='ip-item'>
                    <v-btn flat icon color="red" class='ma-0 mr-3' @click='dialog_model.ips.splice(index, 1)'>
                      <v-icon>remove</v-icon>
                    </v-btn>
                    <v-text-field v-model="ip.ip_from"
                      placeholder='0.0.0.0' style='width: 150px'></v-text-field>
                    <span class='mx-2'>–</span>
                    <v-text-field v-model="ip.ip_to" placeholder='255.255.255.255' style='width: 150px'></v-text-field>
                    <v-switch class='ml-3'
                     v-model="ip.confirm_by_sms"
                     label="подтверждение по sms"
                     color="success"
                     hide-details
                   ></v-switch>
                </div>
              </v-flex>
              <v-flex md12>
                <v-btn color='blue white--text darken-1' small class='ma-0' @click="dialog_model.ips.push({})">
                  <v-icon class="mr-1">add</v-icon>
                  добавить IP
                </v-btn>
              </v-flex>

              <v-flex md12 class='headline'>
                Права
              </v-flex>
              <v-flex md4>
                <v-subheader>ЕГЭ-Репетитор</v-subheader>
                <div v-for='right in rights.groups.EGEREP' :key='right'>
                  <v-switch class='ml-3'
                   :label="rights.all[right]"
                   color="success"
                   hide-details
                   :input-value='dialog_model.rights.indexOf(right) !== -1'
                 ></v-switch>
                </div>
              </v-flex>
              <v-flex md4>
                <v-subheader>Общее</v-subheader>
                <div v-for='right in rights.groups.COMMON' :key='right'>
                  <v-switch class='ml-3'
                   :label="rights.all[right]"
                   color="success"
                   hide-details
                   :input-value='dialog_model.rights.indexOf(right) !== -1'
                 ></v-switch>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { model_defaults } from './data'
import VueCropper from 'vue-cropperjs'
import AvatarLoader from '@/components/AvatarLoader'
import Phones from '@/components/Phones'

export default {
  data() {
    return {
      dialog: false,
      crop_dialog: false,
      cropping: false,
      dialog_model: {},
      loading: false,
      rights: null
    }
  },

  components: { AvatarLoader, Phones },

  created() {
    axios.get(apiUrl('rights')).then(r => {
      this.rights = r.data
    })
  },

  methods: {
    add() {
      this.dialog = true
      this.dialog_model = {...model_defaults}
    },
    async storeOrUpdate() {
      this.loading = true
      if (this.dialog_model.id) {
        await axios.put(apiUrl(`admins/${this.dialog_model.id}`), this.dialog_model)
      } else {
        await axios.post(apiUrl('admins'), this.dialog_model)
      }
      this.$emit('saved')
      this.loading = false
      this.dialog = false
    },
    show(id) {
      axios.get(apiUrl(`admins/${id}`)).then(r => {
        this.dialog_model = r.data
        this.dialog = true
      })
    },
    photoChanged(new_photo) {
      this.dialog_model.photo = new_photo
    }
  }
}
</script>


<style lang='scss' scoped>
  .ip-item {
    & > * {
      display: inline-block;
    }
  }
</style>
