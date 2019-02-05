<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} клиента</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ edit_mode ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0"  style='max-width: 100%' fluid v-else>
            <v-layout>
              <v-flex md6>
                <v-layout wrap>
                  <v-flex md12 class='headline'>
                    Ученик
                  </v-flex>

                  <v-flex md12>
                    <div class='vertical-inputs'>
                      <div class='vertical-inputs__input' v-if='edit_mode'>
                        <AvatarLoader class-name='Client\Client' :entity-id='item.id' :photo='item.photo' @photoChanged='photoChanged' />
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.first_name" label="Имя" hide-details></v-text-field>
                        <!-- <div class='vertical-inputs__input__message'>123</div> -->
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.last_name" label="Фамилия" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.middle_name" label="Отчество" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.school" label="Школа" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <GradeAndYear :item='item' />
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-select multiple hide-details
                          v-model="item.branches"
                          :items="$store.state.data.branches"
                          item-value='id'
                          item-text='full'
                          label="Филиалы"
                        ></v-select>
                      </div>
                      <div class='vertical-inputs__input'>
                        <EmailField :entity='item' />
                      </div>
                      <div>
                        <PhoneEdit :item='item' />
                      </div>
                    </div>
                  </v-flex>
                </v-layout>
              </v-flex>

              <v-flex md6>
                <v-layout wrap>
                  <v-flex md12 class='headline'>
                    Представитель
                  </v-flex>
                  <v-flex md12>
                    <div class='vertical-inputs'>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.representative.first_name" label="Имя" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.representative.last_name" label="Фамилия" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.representative.middle_name" label="Отчество" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.representative.series" label="Серия" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.representative.number" label="Номер" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field v-model="item.representative.code" label="Код подразделения" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <DatePicker v-model='item.representative.birthday' label="Дата рождения" />
                      </div>
                      <div class='vertical-inputs__input'>
                        <DatePicker v-model='item.representative.issued_date' label="Дата выдачи" />
                      </div>
                      <div class='vertical-inputs__input vertical-inputs__input_wide'>
                        <v-text-field v-model="item.representative.issued_by" label="Выдан" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input vertical-inputs__input_wide'>
                        <v-text-field v-model="item.representative.address" label="Адрес" hide-details></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <EmailField :entity='item.representative' />
                        <div class='vertical-inputs__input__message blue--text accent-1'>данный email используется в качестве логина</div>
                      </div>
                      <div>
                        <PhoneEdit :item='item.representative' />
                      </div>
                    </div>
                  </v-flex>
                </v-layout>
              </v-flex>

            </v-layout>            
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

// import ClientMap from '@/components/Client/Map'
import PhoneEdit from '@/components/Phone/Edit'
import { MODEL_DEFAULTS, API_URL } from '@/components/Client'
import EmailField from '@/components/UI/EmailField'
import GradeAndYear from '@/components/GradeAndYear'
import AvatarLoader from '@/components/AvatarLoader'
import { DatePicker } from '@/components/UI'

export default {
  data() {
    return {
      loading: true,
      saving: false,
      item: null,
      dialog: false,
      edit_mode: true,
    }
  },

  components: {  AvatarLoader, PhoneEdit, EmailField, GradeAndYear, DatePicker },

  methods: {
    open(client_id = null, defaults = {}) {
      this.dialog = true
      if (client_id !== null) {
        this.edit_mode = true
        this.loadData(client_id)
      } else {
        this.edit_mode = false
        this.item = {...MODEL_DEFAULTS, ...defaults }
        this.loading = false
      }
    },

    loadData(client_id) {
      this.loading = true
      axios.get(apiUrl(API_URL, client_id)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    photoChanged(new_photo) {
      this.item.photo = new_photo
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(API_URL, this.item.id), this.item).then(r => {
          this.item = r.data
        })
      } else {
        await axios.post(apiUrl('clients'), this.item).then(r => {
          this.$router.push({ name: 'ClientShow', params: { id: r.data.id }})
        })
      }
      this.saving = false
      this.dialog = false
    }
  }
}
</script>
