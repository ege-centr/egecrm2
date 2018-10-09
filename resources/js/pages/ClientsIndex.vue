<template lang="html">
  <div>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card v-if='dialog_model !== null'>
          <v-toolbar dark color="primary">
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>{{ dialog_model.id ? 'Редактирование' : 'Добавление' }} клиента</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dark flat @click.native="storeOrUpdateModel" :loading='loading.dialog'>{{ dialog_model.id ? 'Сохранить' : 'Добавить' }}</v-btn>
            </v-toolbar-items>
          </v-toolbar>
          <v-card-text>
            <v-container grid-list-xl class="pa-0 ma-0" fluid>
              <v-layout wrap>
                <v-flex md12 class='headline'>
                  Ученик
                </v-flex>

                <div class='mr-4 mb-5'>
                  <v-flex>
                    <AvatarLoader class-name='client' :entity-id='dialog_model.id' :photo='dialog_model.photo' @photoChanged='photoChanged' />
                    <div class='mt-5 flex-items align-center link' @click='openMap'>
                      <v-icon color='primary' class='mr-1'>location_on</v-icon>
                      <span>Метки ({{ dialog_model.markers.length }})</span>
                    </div>
                  </v-flex>
                </div>

                <v-flex d-flex md9>
                  <v-layout row wrap>
                    <v-flex md3>
                      <v-text-field v-model="dialog_model.first_name" label="Имя"></v-text-field>
                    </v-flex>
                    <v-flex md3>
                      <v-text-field v-model="dialog_model.last_name" label="Фамилия"></v-text-field>
                    </v-flex>
                    <v-flex md3>
                      <v-text-field v-model="dialog_model.middle_name" label="Отчество"></v-text-field>
                    </v-flex>
                    <v-flex md3>
                      <v-select clearable
                        v-model="dialog_model.grade"
                        :items="$store.state.data.grades"
                        item-value='id'
                        item-text='title'
                        label="Класс"
                      ></v-select>
                    </v-flex>
                    <v-flex md3>
                      <v-select clearable
                        v-model="dialog_model.year"
                        :items="$store.state.data.years"
                        label="Год"
                      ></v-select>
                    </v-flex>
                    <v-flex md3>
                      <v-select multiple
                        v-model="dialog_model.branches"
                        :items="$store.state.data.branches"
                        item-value='id'
                        item-text='full'
                        label="Филиалы"
                      ></v-select>
                    </v-flex>
                    <v-flex md3>
                      <v-text-field v-model="dialog_model.email.email" label="Email"></v-text-field>
                    </v-flex>
                    <v-flex md12 v-for="(phone, index) in dialog_model.phones" :key='index'>
                      <v-layout>
                        <v-flex md3>
                          <v-text-field
                            placeholder='+7 (###) ###-##-##'
                            v-mask="'+7 (###) ###-##-##'"
                            v-model="dialog_model.phones[index].phone" :label="`Телефон ${index + 1}`"
                          >
                          </v-text-field>
                        </v-flex>
                        <v-flex md3>
                          <v-text-field v-model="dialog_model.phones[index].comment"
                            :label="`Комментарий к телефону ${index + 1}`">
                          </v-text-field>
                        </v-flex>
                      </v-layout>
                    </v-flex>
                    <v-flex md12 pt-0>
                      <v-btn color="blue darken-1" class="ma-0 pl-1" flat @click="dialog_model.phones.push({phone: '', comment: ''})">
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
                  <v-text-field v-model="dialog_model.passport.first_name" label="Имя"></v-text-field>
                </v-flex>
                <v-flex md3>
                  <v-text-field v-model="dialog_model.passport.last_name" label="Фамилия"></v-text-field>
                </v-flex>
                <v-flex md3>
                  <v-text-field v-model="dialog_model.passport.middle_name" label="Отчество"></v-text-field>
                </v-flex>
                <v-flex md12 class='headline'>
                  Паспорт
                </v-flex>
                <v-flex>
                  <v-text-field v-model="dialog_model.passport.series" label="Серия"></v-text-field>
                </v-flex>
                <v-flex>
                  <v-text-field v-model="dialog_model.passport.number" label="Номер"></v-text-field>
                </v-flex>
                <v-flex>
                  <v-text-field v-model="dialog_model.passport.code" label="Код подразделения"></v-text-field>
                </v-flex>
                <v-flex>
                  <v-menu
                    ref="menu3"
                    :close-on-content-click="false"
                    v-model="menu3"
                    :nudge-right="40"
                    :return-value.sync="dialog_model.passport.birthday"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                  <v-text-field
                    slot="activator"
                    v-model="dialog_model.passport.birthday"
                    label="Дата рождения"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker
                    v-model="dialog_model.passport.birthday"
                    @input="$refs.menu3.save(dialog_model.passport.birthday)">
                  </v-date-picker>
                  </v-menu>
                </v-flex>
                <v-flex>
                  <v-menu
                    ref="menu4"
                    :close-on-content-click="false"
                    v-model="menu4"
                    :nudge-right="40"
                    :return-value.sync="dialog_model.passport.issued_date"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                  <v-text-field
                    slot="activator"
                    v-model="dialog_model.passport.issued_date"
                    label="Дата выдачи"
                    prepend-icon="event"
                    readonly
                  ></v-text-field>
                  <v-date-picker
                    v-model="dialog_model.passport.issued_date"
                    @input="$refs.menu4.save(dialog_model.passport.issued_date)">
                  </v-date-picker>
                  </v-menu>
                </v-flex>
                <v-flex md12>
                  <v-text-field v-model="dialog_model.passport.issued_by" label="Выдан"></v-text-field>
                </v-flex>
                <v-flex md12>
                  <v-textarea v-model="dialog_model.passport.address" label="Адрес"></v-textarea>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>

    <v-layout row justify-center v-if='dialog_model' @shown="refreshMap">
      <v-dialog v-model="map_dialog" persistent max-width="1000px">
        <v-card>
          <v-card-text>
            <GmapMap ref='map' @click='mapClick'
                :center="{lat: 55.7387, lng: 37.6032}"
                :zoom="12"
                :options="{
                  disableDefaultUI: true
                }"
                style="width: 100%; height: 600px"
              >
              <GmapMarker
                v-for="(m, index) in dialog_model.markers"
                :key="index"
                :position="{lat: m.lat, lng: m.lng}"
                :clickable="true"
                @dblclick='deleteMarker(index)'
              />
            </GmapMap>
          </v-card-text>
          <v-card-actions class='justify-center'>
            <v-btn color="blue darken-1" flat @click.native="map_dialog = false">Сохранить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>

    <v-layout>
      <v-flex xs12 class="text-xs-right">
        <router-link :to="{ name: 'ClientCreate' }" class='black-link'>
          <v-btn small fab color="primary">
            <v-icon dark>add</v-icon>
          </v-btn>
          добавить клиента
        </router-link>
      </v-flex>
    </v-layout>

    <v-container grid-list-md fluid class="px-0" v-if='collection !== null'>
      <v-layout row wrap class='relative'>
        <Loader v-if='loading.pagination' />
        <v-flex xs12>
          <v-data-table :items='collection.data' :item-key='id' hide-headers hide-actions>
            <template slot="items" slot-scope="{ item }">
              <td>
                <router-link :to="{ name: 'ClientsShow', params: { id: item.id }}">
                  {{ item.name }}
                </router-link>
              </td>
              <td class='text-md-right'>
                <router-link :to="{ name: 'ClientEdit', params: { id: item.id }}">
                  <v-btn flat icon color="black" class='ma-0'>
                      <v-icon>more_horiz</v-icon>
                  </v-btn>
                </router-link>
              </td>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
      <div class="text-xs-center mt-4">
        <v-pagination
          v-if='collection.meta.last_page > 1'
          v-model="page"
          :length="collection.meta.last_page"
          :total-visible="7"
          circle
        ></v-pagination>
     </div>
    </v-container>
  </div>
</template>

<script>

import AvatarLoader from '@/components/AvatarLoader'

const MODEL_DEFAULTS = {
  markers: [],
  phones: [{phone: '', comment: ''}],
  passport: {},
  email: {}
}

export default {
  components: { AvatarLoader },

  data() {
    return {
      page: 1,
      dialog: false,
      dialog_model: null,

      map_dialog: false,

      loading: {
        dialog: false,
        pagination: false
      },
      collection: null
    }
  },

  created() {
    this.loadData()
  },

  watch: {
    page() {
        this.loadData()
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
      this.dialog_model.markers.splice(index, 1)
    },
    mapClick(event) {
      this.dialog_model.markers.push({
        lat: event.latLng.lat(),
        lng: event.latLng.lng()
      })
    },
    openDialog() {
      this.dialog = true
      this.dialog_model = _.clone(MODEL_DEFAULTS)
    },
    async storeOrUpdateModel() {
      this.loading.dialog = true
      if (this.dialog_model.id) {
        await axios.put(apiUrl(`clients/${this.dialog_model.id}`), this.dialog_model)
      } else {
        await axios.post(apiUrl('clients'), this.dialog_model)
      }
      this.loadData()
      this.loading.dialog = false
      this.dialog = false
    },
    showModel(id) {
      axios.get(apiUrl(`clients/${id}`)).then(r => {
        this.dialog_model = r.data
        this.dialog = true
      })
    },
    loadData() {
      this.loading.pagination = true
      axios.get(apiUrl(`clients?page=${this.page}`)).then(response => {
        this.collection = response.data
        this.loading.pagination = false
      })
    },

    photoChanged(new_photo) {
      this.dialog_model.photo = new_photo
    }
  }
}
</script>

<style lang="css">
</style>
