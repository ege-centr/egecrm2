<template>
  <div>
    <div v-if='client !== null'>
      <v-container grid-list-xl class="pa-0 ma-0" fluid>
        <v-layout wrap>
          <v-flex md12 class='headline'>
            Ученик
          </v-flex>

          <v-flex md12>
            <div class='vertical-inputs'>
              <div class='vertical-inputs__input'>
                <v-text-field v-model="client.first_name" label="Имя" hide-details></v-text-field>
                <!-- <div class='vertical-inputs__input__message'>123</div> -->
              </div>
              <div class='vertical-inputs__input'>
                <v-text-field v-model="client.last_name" label="Фамилия" hide-details></v-text-field>
              </div>
              <div class='vertical-inputs__input'>
                <v-text-field v-model="client.middle_name" label="Отчество" hide-details></v-text-field>
              </div>
              <div class='vertical-inputs__input'>
                <v-select hide-details
                  v-model="client.grade_id"
                  :items="withNullOption($store.state.data.grades, 'id', 'title')"
                  item-value='id'
                  item-text='title'
                  label="Класс"
                ></v-select>
              </div>
              <div class='vertical-inputs__input'>
                <v-select hide-details
                  v-model="client.year"
                  :items="withNullOption($store.state.data.years)"
                  label="Год"
                ></v-select>
              </div>
              <div class='vertical-inputs__input'>
                <v-select multiple hide-details
                  v-model="client.branches"
                  :items="$store.state.data.branches"
                  item-value='id'
                  item-text='full'
                  label="Филиалы"
                ></v-select>
              </div>
              <div class='vertical-inputs__input'>
                <EmailField :entity='client' />
              </div>
              <div>
                <Phones :item='client' />
              </div>
            </div>
          </v-flex>
        </v-layout>

        <v-layout wrap>
          <v-flex md12 class='headline'>
            Представитель
          </v-flex>
          <v-flex md12>
            <div class='vertical-inputs'>
              <div class='vertical-inputs__input'>
                <v-text-field v-model="client.representative.first_name" label="Имя" hide-details></v-text-field>
              </div>
              <div class='vertical-inputs__input'>
                <v-text-field v-model="client.representative.last_name" label="Фамилия" hide-details></v-text-field>
              </div>
              <div class='vertical-inputs__input'>
                <v-text-field v-model="client.representative.middle_name" label="Отчество" hide-details></v-text-field>
              </div>
              <div class='vertical-inputs__input'>
                <v-text-field v-model="client.representative.series" label="Серия" hide-details></v-text-field>
              </div>
              <div class='vertical-inputs__input'>
                <v-text-field v-model="client.representative.number" label="Номер" hide-details></v-text-field>
              </div>
              <div class='vertical-inputs__input'>
                <v-text-field v-model="client.representative.code" label="Код подразделения" hide-details></v-text-field>
              </div>
              <div class='vertical-inputs__input'>
                <v-menu
                  ref="birthday"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  :return-value.sync="client.representative.birthday"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  min-width="290px"
                >
                  <v-text-field hide-details
                    slot="activator"
                    v-model="client.representative.birthday"
                    label="Дата рождения"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker
                    v-model="client.representative.birthday"
                    @input="$refs.birthday.save(client.representative.birthday)">
                  </v-date-picker>
                </v-menu>
              </div>
              <div class='vertical-inputs__input'>
                <v-menu
                  ref="issued_date"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  :return-value.sync="client.representative.issued_date"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  min-width="290px"
                >
                  <v-text-field hide-details
                    slot="activator"
                    v-model="client.representative.issued_date"
                    label="Дата выдачи"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker
                    v-model="client.representative.issued_date"
                    @input="$refs.issued_date.save(client.representative.issued_date)">
                  </v-date-picker>
                </v-menu>
              </div>
              <div class='vertical-inputs__input vertical-inputs__input_wide'>
                <v-text-field v-model="client.representative.issued_by" label="Выдан" hide-details></v-text-field>
              </div>
              <div class='vertical-inputs__input vertical-inputs__input_wide'>
                <v-textarea v-model="client.representative.address" label="Адрес" hide-details></v-textarea>
              </div>
              <div class='vertical-inputs__input'>
                <EmailField :entity='client.representative' />
                <div class='vertical-inputs__input__message blue--text accent-1'>данный email используется в качестве логина</div>
              </div>
              <div>
                <Phones :item='client.representative' />
              </div>
            </div>
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
    </div>
  </div>
</template>

<script>

import AvatarLoader from '@/components/AvatarLoader'
import ClientMap from '@/components/Client/Map'
import Phones from '@/components/Phones'
import { MODEL_DEFAULTS } from '@/components/Client/data'
import EmailField from '@/components/UI/EmailField'

export default {
  data() {
    return {
      loading: true,
      saving: false,
      client: null,
      map_dialog: false,
    }
  },

  components: {  AvatarLoader, ClientMap, Phones, EmailField },

  created() {
    if (this.$route.params.id) {
      this.loadData()
    } else {
      this.client = MODEL_DEFAULTS
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
