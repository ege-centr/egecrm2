<template>
  <div>
    <div v-if='client !== null'>
      <v-container grid-list-xl class="pa-0 ma-0" fluid>
        <v-layout wrap>
          <v-flex md12 class='headline'>
            Ученик
          </v-flex>

          <div class='mr-4 mb-5'>
            <v-flex>
              <AvatarLoader class-name='Client\Client' :entity-id='client.id' :photo='client.photo' @photoChanged='photoChanged' />
              <div class='mt-5 flex-items align-center link' @click='openMap'>
                <v-icon color='primary' class='mr-1'>location_on</v-icon>
                <span>Метки ({{ client.markers.length }})</span>
              </div>
            </v-flex>
          </div>

          <v-flex d-flex md9>
            <v-layout row wrap>
              <v-flex md3>
                <v-text-field v-model="client.first_name" label="Имя"></v-text-field>
              </v-flex>
              <v-flex md3>
                <v-text-field v-model="client.last_name" label="Фамилия"></v-text-field>
              </v-flex>
              <v-flex md3>
                <v-text-field v-model="client.middle_name" label="Отчество"></v-text-field>
              </v-flex>
              <v-flex md3>
                <v-select clearable
                  v-model="client.grade"
                  :items="$store.state.data.grades"
                  item-value='id'
                  item-text='title'
                  label="Класс"
                ></v-select>
              </v-flex>
              <v-flex md3>
                <v-select clearable
                  v-model="client.year"
                  :items="$store.state.data.years"
                  label="Год"
                ></v-select>
              </v-flex>
              <v-flex md3>
                <v-select multiple
                  v-model="client.branches"
                  :items="$store.state.data.branches"
                  item-value='id'
                  item-text='full'
                  label="Филиалы"
                ></v-select>
              </v-flex>
              <v-flex md3>
                <v-text-field v-model="client.email.email" label="Email"></v-text-field>
              </v-flex>
              <v-flex md12 v-for="(phone, index) in client.phones" :key='index'>
                <v-layout>
                  <v-flex md3>
                    <v-text-field
                      placeholder='+7 (###) ###-##-##'
                      v-mask="'+7 (###) ###-##-##'"
                      v-model="client.phones[index].phone" :label="`Телефон ${index + 1}`"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex md3>
                    <v-text-field v-model="client.phones[index].comment"
                      :label="`Комментарий к телефону ${index + 1}`">
                    </v-text-field>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex md12 pt-0>
                <v-btn color="blue darken-1" class="ma-0 pl-1" flat @click="client.phones.push({phone: '', comment: ''})">
                  <v-icon class="mr-1">add</v-icon>
                  добавить телефон
                </v-btn>
              </v-flex>
            </v-layout>
          </v-flex>
        </v-layout>

        <v-layout wrap>
          <v-flex md12 class='headline'>
            Представитель
          </v-flex>
          <v-flex md3>
            <v-text-field v-model="client.passport.first_name" label="Имя"></v-text-field>
          </v-flex>
          <v-flex md3>
            <v-text-field v-model="client.passport.last_name" label="Фамилия"></v-text-field>
          </v-flex>
          <v-flex md3>
            <v-text-field v-model="client.passport.middle_name" label="Отчество"></v-text-field>
          </v-flex>
          <v-flex md12 class='headline'>
            Паспорт
          </v-flex>
          <v-flex>
            <v-text-field v-model="client.passport.series" label="Серия"></v-text-field>
          </v-flex>
          <v-flex>
            <v-text-field v-model="client.passport.number" label="Номер"></v-text-field>
          </v-flex>
          <v-flex>
            <v-text-field v-model="client.passport.code" label="Код подразделения"></v-text-field>
          </v-flex>
          <v-flex>
            <v-menu
              ref="birthday"
              :close-on-content-click="false"
              :nudge-right="40"
              :return-value.sync="client.passport.birthday"
              lazy
              transition="scale-transition"
              offset-y
              full-width
              min-width="290px"
            >
            <v-text-field
              slot="activator"
              v-model="client.passport.birthday"
              label="Дата рождения"
              prepend-icon="event"
              readonly
            ></v-text-field>
            <v-date-picker
              v-model="client.passport.birthday"
              @input="$refs.birthday.save(client.passport.birthday)">
            </v-date-picker>
            </v-menu>
          </v-flex>
          <v-flex>
            <v-menu
              ref="issued_date"
              :close-on-content-click="false"
              :nudge-right="40"
              :return-value.sync="client.passport.issued_date"
              lazy
              transition="scale-transition"
              offset-y
              full-width
              min-width="290px"
            >
            <v-text-field
              slot="activator"
              v-model="client.passport.issued_date"
              label="Дата выдачи"
              prepend-icon="event"
              readonly
            ></v-text-field>
            <v-date-picker
              v-model="client.passport.issued_date"
              @input="$refs.issued_date.save(client.passport.issued_date)">
            </v-date-picker>
            </v-menu>
          </v-flex>
          <v-flex md12>
            <v-text-field v-model="client.passport.issued_by" label="Выдан"></v-text-field>
          </v-flex>
          <v-flex md12>
            <v-textarea v-model="client.passport.address" label="Адрес"></v-textarea>
          </v-flex>
        </v-layout>

        <v-layout wrap>
          <v-flex md12 class='headline'>
            Договоры
          </v-flex>
          <v-flex md12 v-if='client.contracts.length'>
            <v-data-table
              :items="client.contracts"
              class="elevation-1"
              hide-actions
              hide-headers
            >
              <template slot='items' slot-scope="{ item }">
                <td>
                  <span v-if='item.id'>
                    №{{ item.number }} версия {{ item.version }}
                  </span>
                </td>
                <td>
                  от {{ item.date | date }}
                </td>
                <td>
                  {{ item.sum }} руб.
                </td>
                <td>
                  <span v-if='item.payments.length'>
                    {{ item.payments.length }} платежа
                  </span>
                  <span v-else>
                    платежей нет
                  </span>
                </td>
                <td>
                  {{ getData('grades', item.grade).title }}
                </td>
                <td>
                  <span v-for='subject in item.subjects' :class="{
                    'error--text': subject.status == subject_statuses[2],
                    'orange--text': subject.status == subject_statuses[1]
                  }">
                    {{ getData('subjects', subject.subject_id).three_letters }}
                    <span class='grey--text'>{{ subject.lessons }}</span>
                  </span>
                </td>
                <td>
                  <span v-if='item.id'>
                    {{ getData('admins', item.created_admin_id).name }}
                    {{ item.created_at | date-time }}
                  </span>
                </td>
                <td class='text-md-right'>
                  <v-menu left>
                    <v-btn slot='activator' flat icon color="black" class='ma-0'>
                      <v-icon>more_horiz</v-icon>
                    </v-btn>
                    <v-list dense>
                      <v-list-tile @click='openContract(item)'>
                          <v-list-tile-action>
                            <v-icon>edit</v-icon>
                          </v-list-tile-action>
                          <v-list-tile-content>
                            <v-list-tile-title>Редактировать</v-list-tile-title>
                          </v-list-tile-content>
                      </v-list-tile>
                      <v-list-tile @click='addContractVersion(item)'>
                          <v-list-tile-action>
                            <v-icon>file_copy</v-icon>
                          </v-list-tile-action>
                          <v-list-tile-content>
                            <v-list-tile-title>Добавить версию</v-list-tile-title>
                          </v-list-tile-content>
                      </v-list-tile>
                      <v-list-tile @click='openContract(item)'>
                          <v-list-tile-action>
                            <v-icon>print</v-icon>
                          </v-list-tile-action>
                          <v-list-tile-content>
                            <v-list-tile-title>Печать</v-list-tile-title>
                          </v-list-tile-content>
                      </v-list-tile>
                    </v-list>
                  </v-menu>
                </td>
              </template>
            </v-data-table>
          </v-flex>
          <v-flex md12 py-0>
            <v-btn color="blue darken-1" class="ma-0 pl-1" flat @click='openContract()'>
              <v-icon class="mr-1">add</v-icon>
              добавить
            </v-btn>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-flex md12 justify-center class='text-md-center'>
            <v-btn @click='storeOrUpdate' :loading='saving'>
              {{ client.id ? 'сохранить' : 'добавить' }}
            </v-btn>
          </v-flex>
        </v-layout>
      </v-container>

      <v-layout row justify-center @shown="refreshMap">
        <v-dialog v-model="map_dialog" persistent max-width="1200px">
          <v-card>
            <v-card-text>
              <GmapMap ref='map' @click='mapClick'
                  :center="{lat: 55.7387, lng: 37.6032}"
                  :zoom="12"
                  :options="{
                    disableDefaultUI: true
                  }"
                  style="width: 100%; height: 700px"
                >
                <GmapMarker
                  v-for="(m, index) in client.markers"
                  :key="index"
                  :position="{lat: m.lat, lng: m.lng}"
                  :clickable="true"
                  @dblclick='deleteMarker(index)'
                />
              </GmapMap>
            </v-card-text>
            <v-card-actions class='justify-center'>
              <v-btn color="blue darken-1" flat @click.native="map_dialog = false">Закрыть</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>

      <ContractDialog ref='ContractDialog' :item='contract' @saved='contractSaved' />

    </div>
  </div>
</template>

<script>

import AvatarLoader from '@/components/AvatarLoader'
import ContractDialog from '@/components/Contract/ContractDialog'
import { model_defaults as contract_model_defaults, subject_statuses } from '@/components/Contract/data'
import { model_defaults as client_model_defaults } from '@/components/Client/data'

export default {
  data() {
    return {
      loading: true,
      saving: false,
      client: null,
      map_dialog: false,
      contract: contract_model_defaults,
      subject_statuses
    }
  },

  components: {  AvatarLoader, ContractDialog },

  created() {
    if (this.$route.params.id) {
      this.loadData()
    } else {
      this.client = client_model_defaults
    }
  },

  methods: {
    openMap() {
      this.map_dialog = true
      setTimeout(() => {
        Vue.$gmapDefaultResizeBus.$emit('resize')
        console.log('RESIZE')
      }, 1000)
    },
    refreshMap() {
      Vue.$gmapDefaultResizeBus.$emit('resize')
    },
    deleteMarker(index) {
      this.client.markers.splice(index, 1)
    },
    mapClick(event) {
      this.client.markers.push({
        lat: event.latLng.lat(),
        lng: event.latLng.lng()
      })
    },
    loadData() {
      axios.get(apiUrl(`clients/${this.$route.params.id}`)).then(r => {
        this.client = r.data
        this.loading = false
      })
    },
    openContract(contract) {
      if (contract === undefined) {
        contract = clone(contract_model_defaults)
      }
      this.contract = clone(contract)
      this.$refs.ContractDialog.dialog = true
    },
    addContractVersion(contract) {
      this.openContract({
        ...clone(contract),
        id: undefined
      })
    },
    photoChanged(new_photo) {
      this.client.photo = new_photo
    },
    contractSaved(contract) {
      if (contract.id) {
        const index = this.client.contracts.findIndex(e => e.id == contract.id)
        Vue.set(this.client.contracts, index, contract)
      } else {
        this.client.contracts.push(contract)
      }
    },
    async storeOrUpdate() {
      this.saving = true
      if (this.client.id) {
        await axios.put(apiUrl(`clients/${this.client.id}`), this.client).then(r => {
          this.client = r.data
        })
      } else {
        await axios.post(apiUrl('clients'), this.client).then(r => {
          this.$router.push({ name: 'ClientEdit', params: { id: r.data }})
        })
      }
      this.saving = false
    },
  }
}
</script>
