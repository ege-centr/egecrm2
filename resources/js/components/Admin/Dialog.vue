<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card v-if='item !== null'>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ item.id ? 'Редактирование' : 'Добавление' }} пользователя</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='loading.dialog'>{{ item.id ? 'Сохранить' : 'Добавить' }}</v-btn>
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
                  <AvatarLoader class-name='Admin\Admin' :entity-id='item.id' :photo='item.photo' @photoChanged='photoChanged' />
                </v-flex>
              </div>

              <v-flex d-flex md8>
                <v-layout row wrap>
                  <v-flex md3>
                    <v-text-field v-model="item.first_name" label="Имя"></v-text-field>
                  </v-flex>
                  <v-flex md3>
                    <v-text-field v-model="item.last_name" label="Фамилия"></v-text-field>
                  </v-flex>
                  <v-flex md3>
                    <v-text-field v-model="item.first_name" label="Отчество"></v-text-field>
                  </v-flex>
                  <!-- <v-flex md3>
                    <v-text-field v-model="item.email.email" label="Email"></v-text-field>
                  </v-flex> -->
                  <PhoneEdit :item='item' />
                </v-layout>
              </v-flex>


              <v-flex md12 class='headline'>
                IP адреса
              </v-flex>
              <v-flex md12 py-0>
                  <div v-for="(ip, index) in item.ips" :key='ip.id' class='ip-item'>
                    <v-btn flat icon color="red" class='ma-0 mr-3' @click='item.ips.splice(index, 1)'>
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
                <v-btn color='primary' small class='ma-0' @click="item.ips.push({})">
                  <v-icon class="mr-1">add</v-icon>
                  добавить IP
                </v-btn>
              </v-flex>

              <v-flex md12 class='headline'>
                Права
              </v-flex>
              <v-flex md4>
                <v-subheader>ЕГЭ-Центр</v-subheader>
                <div v-for='right in rights.groups.LK2' :key='right'>
                  <v-switch class='ml-3'
                   :label="rights.all[right]"
                   color="success"
                   hide-details
                   @change='toggleRight(right)'
                   :input-value='item.rights.indexOf(right) !== -1'
                 ></v-switch>
                </div>
              </v-flex>
              <v-flex md4>
                <v-subheader>ЕГЭ-Репетитор</v-subheader>
                <div v-for='right in rights.groups.EGEREP' :key='right'>
                  <v-switch class='ml-3'
                   :label="rights.all[right]"
                   color="success"
                   hide-details
                   :input-value='item.rights.indexOf(right) !== -1'
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
                   :input-value='item.rights.indexOf(right) !== -1'
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

import { MODEL_DEFAULTS } from './'
import VueCropper from 'vue-cropperjs'
import AvatarLoader from '@/components/AvatarLoader'
import PhoneEdit from '@/components/Phone/Edit'

export default {
  data() {
    return {
      dialog: false,
      crop_dialog: false,
      cropping: false,
      item: {},
      loading: false,
      rights: null
    }
  },

  components: { AvatarLoader, PhoneEdit },

  created() {
    axios.get(apiUrl('rights')).then(r => {
      this.rights = r.data
    })
  },

  methods: {
    add() {
      this.dialog = true
      this.item = {...MODEL_DEFAULTS}
    },
    async storeOrUpdate() {
      this.loading = true
      if (this.item.id) {
        await axios.put(apiUrl(`admins/${this.item.id}`), this.item)
      } else {
        await axios.post(apiUrl('admins'), this.item)
      }
      this.$emit('saved')
      this.loading = false
      this.dialog = false
    },
    show(id) {
      axios.get(apiUrl(`admins/${id}`)).then(r => {
        this.item = r.data
        this.dialog = true
      })
    },
    photoChanged(new_photo) {
      this.item.photo = new_photo
    },
    toggleRight(right) {
      const index = this.item.rights.indexOf(right)
      if (index === -1) {
        this.item.rights.push(right)
      } else {
        this.item.rights.splice(index, 1)
      }
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
