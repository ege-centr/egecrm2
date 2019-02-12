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
                      <div class='vertical-inputs__input'>
                        <AvatarLoader 
                          :entity-type='CLASS_NAME' 
                          :item='item' 
                        />
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

import ClientMap from '@/components/Client/Map'
import PhoneEdit from '@/components/Phone/Edit'
import { MODEL_DEFAULTS, CLASS_NAME, API_URL } from '@/components/Client'
import EmailField from '@/components/UI/EmailField'
import GradeAndYear from '@/components/GradeAndYear'
import AvatarLoader from '@/components/AvatarLoader'
import DatePicker from '@/components/UI/DatePicker'
import { DialogMixin } from '@/mixins'

export default {
  mixins: [ DialogMixin ],

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      CLASS_NAME,
    }
  },

  components: {  AvatarLoader, PhoneEdit, EmailField, GradeAndYear, DatePicker },
}
</script>
