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
          <ContractList :items='client.contracts' :editable='true' />
        </v-layout>

        <v-layout>
          <v-flex md12 justify-center class='text-md-center'>
            <v-btn @click='storeOrUpdate' :loading='saving'>
              {{ client.id ? 'сохранить' : 'добавить' }}
            </v-btn>
          </v-flex>
        </v-layout>
      </v-container>

      <ClientMap :items='client.markers' :editable='true' ref='ClientMap' />
    </div>
  </div>
</template>

<script>

import AvatarLoader from '@/components/AvatarLoader'
import ContractList from '@/components/Contract/List'
import ClientMap from '@/components/Client/Map'
import { model_defaults } from '@/components/Client/data'

export default {
  data() {
    return {
      loading: true,
      saving: false,
      client: null,
      map_dialog: false,
    }
  },

  components: {  AvatarLoader, ContractList, ClientMap },

  created() {
    if (this.$route.params.id) {
      this.loadData()
    } else {
      this.client = model_defaults
    }
  },

  methods: {
    loadData() {
      axios.get(apiUrl(`clients/${this.$route.params.id}`)).then(r => {
        this.client = r.data
        this.loading = false
      })
    },
    photoChanged(new_photo) {
      this.client.photo = new_photo
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
    openMap() {
      this.$refs.ClientMap.openMap()
    }
  }
}
</script>
