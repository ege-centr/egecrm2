<template>
  <v-layout row justify-center>
    <!-- <v-dialog v-model="dialog" transition="dialog-bottom-transition" content-class='v-dialog--fullscreen halfscreen-dialog'> -->
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ (item === null || item.id) ? 'Редактирование' : 'Добавление' }} акта</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn v-if='item && item.id' dark flat @click.native="destroy" :loading='destroying'>Удалить</v-btn>
            <v-btn v-if='item && item.id' dark flat @click.native="destroy">Сгенерировать</v-btn>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ (item === null || item.id) ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout wrap>
              <v-flex md12>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                    <v-select hide-details
                      v-model="item.teacher_id"
                      :items="withNullOption2(teachers)"
                      label="Учитель"
                      item-value='id'
                      item-text='names.abbreviation'
                    ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field v-model='item.lesson_count' label='Количество занятий' hide-details v-mask="'####'"></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field v-model='item.sum' label='Сумма' hide-details v-mask="'######'"></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-menu
                      ref="date"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="item.date"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <v-text-field
                        slot="activator"
                        v-model="item.date"
                        label="Дата занятия"
                        readonly hide-details
                      ></v-text-field>
                      <v-date-picker
                        v-model="item.date"
                        @input="$refs.date.save(item.date)">
                      </v-date-picker>
                    </v-menu>
                  </div>
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
import { GROUP_ACTS_API_URL } from '@/components/Group'

export default {
  props: {
    groupId: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      loading: true,
      saving: false,
      item: null,
      dialog: false,

      teachers: [],
      loading: true,
      saving: false,
      destroying: false,
    }
  },
  
  methods: {
    open(item_id = null) {
      this.item = null
      this.dialog = true
      if (item_id === null) {
        this.item = {group_id: this.groupId}
      }
      this.loadData(item_id)
    },

    async loadData(item_id) {
      await axios.get(apiUrl('teachers')).then(r => {
        this.teachers = r.data
      })
      if (item_id) {
        axios.get(apiUrl(GROUP_ACTS_API_URL, item_id)).then(r => {
          this.item = r.data
        })
      }
      this.loading = false
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(GROUP_ACTS_API_URL, this.item.id), this.item)
      } else {
        await axios.post(apiUrl(GROUP_ACTS_API_URL), this.item)
      }
      this.$emit('updated')
      this.saving = false
      this.dialog = false
    },

    async destroy() {
      this.destroying = true
      await axios.delete(apiUrl(GROUP_ACTS_API_URL, this.item.id))
      this.$emit('updated')
      this.destroying = false
      this.dialog = false
    },
  },
}
</script>