<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} пользователя</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark icon @click.native="storeOrUpdate" :loading='saving'>
              <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout wrap row>
              <v-flex md12 class='headline'>
                Основное
              </v-flex>

              <div class='mr-4 mb-5'>
                <v-flex>
                  <AvatarLoader 
                    :entity-type='ROLES.ADMIN' 
                    :item='item'
                  />
                </v-flex>
              </div>

              <v-flex d-flex md8>
                <v-layout row wrap>
                  <v-flex md12 class='pb-0'>
                    <div class='vertical-inputs flex-items'>
                      <div class='vertical-inputs__input mr-3'>
                        <v-text-field hide-details v-model="item.first_name" label="Имя"></v-text-field>
                      </div>
                      <div class='vertical-inputs__input mr-3'>
                        <v-text-field hide-details v-model="item.last_name" label="Фамилия"></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field hide-details v-model="item.middle_name" label="Отчество"></v-text-field>
                      </div>
                    </div>
                  </v-flex>
                  <v-flex md12>
                    <div class='vertical-inputs'>
                      <div class='vertical-inputs__input'>
                        <v-text-field hide-details v-model="item.nickname" label="Никнейм"></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field hide-details 
                          hide-details
                          label='Email (используется в качестве логина)' 
                          v-model='item.email'
                        />
                      </div>
                    </div>
                  </v-flex>
                  <v-flex md12 class='py-0'>
                    <PhoneEdit :item='item' :max='1' />
                  </v-flex>
                </v-layout>
              </v-flex>


              <v-flex md12 class='headline'>
                IP адреса
              </v-flex>
              <v-flex md12>
                <div style='width: 700px'>
                  <v-data-table
                    class='relative-table'
                    :headers="[
                      {text: 'IP от', sortable: false, width: 150},
                      {text: 'IP до', sortable: false, width: 150},
                      {text: 'Подтверждение по СМС', sortable: false, width: 200},
                      {text: '', sortable: false},
                    ]"
                    :items='item.ips'
                    hide-actions
                  >
                    <template v-slot:items='{ item, index }'>
                      <tr>
                        <td>
                          <v-edit-dialog
                            :return-value.sync="item.ip_from"
                            lazy
                          > 
                            <v-btn small flat v-if="!item.ip_from" fab class='edit-inside-table'>
                              <v-icon>edit</v-icon>
                            </v-btn>
                            <span v-else>{{ item.ip_from }}</span>
                            <v-text-field
                              slot="input"
                              v-model="item.ip_from"
                              single-line
                              placeholder='0.0.0.0'
                            ></v-text-field>
                          </v-edit-dialog>
                        </td>
                        <td>
                          <v-edit-dialog
                            :return-value.sync="item.ip_to"
                            lazy
                          > 
                            <v-btn small flat v-if="!item.ip_to" fab class='edit-inside-table'>
                              <v-icon>edit</v-icon>
                            </v-btn>
                            <span v-else>{{ item.ip_to }}</span>
                            <v-text-field
                              slot="input"
                              placeholder='255.255.255.255'
                              v-model="item.ip_to"
                              single-line
                            ></v-text-field>
                          </v-edit-dialog>
                        </td>
                        <td>
                          <v-switch class='ml-3'
                            v-model="item.confirm_by_sms"
                            color="success"
                            hide-details
                          ></v-switch>
                        </td>
                        <td class='text-sm-right'>
                          <v-btn flat icon color="red" class='ma-0' @click='removeIp(index)'>
                            <v-icon>remove</v-icon>
                          </v-btn>
                        </td>
                      </tr>
                    </template>
                    <template v-slot:footer>
                      <tr>
                        <td colspan='3'></td>
                        <td class='text-sm-right'>
                          <v-btn color='primary' flat icon class='ma-0' @click='addIp()'>
                            <v-icon>add</v-icon>
                          </v-btn>
                        </td>
                      </tr>
                    </template>
                  </v-data-table>
                </div>
              </v-flex>
              


              <v-flex md12 class='headline'>
                Права
              </v-flex>
              <v-flex md4 v-if='rights !== null'>
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
              <v-flex md4 v-if='rights !== null'>
                <v-subheader>ЕГЭ-Репетитор</v-subheader>
                <div v-for='right in rights.groups.EGEREP' :key='right'>
                  <v-switch class='ml-3'
                  :label="rights.all[right]"
                  color="success"
                  hide-details
                  @change='toggleRight(right)'
                  :input-value='item.rights.indexOf(right) !== -1'
                ></v-switch>
                </div>
              </v-flex>
              <v-flex md4 v-if='rights !== null'>
                <v-subheader>Общее</v-subheader>
                <div v-for='right in rights.groups.COMMON' :key='right'>
                  <v-switch class='ml-3'
                  :label="rights.all[right]"
                  color="success"
                  hide-details
                  @change='toggleRight(right)'
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

import { ROLES } from '@/config'
import { MODEL_DEFAULTS, IP_MODEL_DEFAULTS, API_URL } from './'
import VueCropper from 'vue-cropperjs'
import AvatarLoader from '@/components/AvatarLoader'
import PhoneEdit from '@/components/Phone/Edit'
import { DialogMixin } from '@/mixins'

export default {
  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      ROLES,
      rights: null,
    }
  },

  mixins: [ DialogMixin ],

  components: { AvatarLoader, PhoneEdit },

  created() {
    axios.get(apiUrl('rights')).then(r => {
      this.rights = r.data
    })
  },

  methods: {
    add() {
      this.dialog = true
      this.item = _.clone(MODEL_DEFAULTS)
    },
    
    addIp() {
      this.item.ips.push(_.clone(IP_MODEL_DEFAULTS))
    },

    removeIp(index) {
      this.item.ips.splice(index, 1)
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
