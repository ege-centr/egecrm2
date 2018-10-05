<template>
  <v-layout row justify-center>
    <v-layout row justify-center>

      <v-dialog v-model="crop_dialog" persistent max-width="1000px">
        <v-card>
          <v-card-text>
              <vue-cropper v-if='dialog_model.photo'
                ref="cropper" style='height: 600px'
                :src="dialog_model.photo.url_original"
                :zoomable='false'
                :view-mode='1'
                :min-crop-box-width='100'
                :min-crop-box-height='100'
                :min-container-height='600'
                :min-container-width='968'
                :aspect-ratio='1'
                :responsive='false'
              >
              </vue-cropper>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click.native="crop_dialog = false">Отмена</v-btn>
            <v-btn color="blue darken-1" flat @click.native="cropImage" :loading='cropping'>Сохранить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>


    </v-layout>
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

              <div class='mr-5 mb-5'>
                <v-flex>
                  <div v-if='dialog_model.photo'>
                    <v-hover>
                      <v-avatar slot-scope="{ hover }" :size='180' style='overflow: hidden'>
                        <img :src='dialog_model.photo.url_version' />
                        <v-slide-y-reverse-transition>
                          <div class='photo-actions' v-show='hover  '>
                            <div @click="selectFileToUpload">
                              <v-icon>arrow_upward</v-icon>
                              <span>загрузить новое</span>
                            </div>
                            <div @click="crop_dialog = true">
                              <v-icon>fullscreen</v-icon>
                              <span>изменить</span>
                            </div>
                          </div>
                        </v-slide-y-reverse-transition>
                      </v-avatar>
                    </v-hover>
                  </div>
                  <div v-else class='image-upload' @click="selectFileToUpload">
                    загрузить фото
                  </div>
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
                  <v-flex md12 class='py-0'>
                    <v-btn color="blue darken-1" class="ma-0 pl-1" flat @click="dialog_model.phones.push({phone: '', comment: ''})">
                      <v-icon class="mr-1">add</v-icon>
                      добавить телефон
                    </v-btn>
                  </v-flex>
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
                <v-btn color="blue darken-1" class="ma-0 pl-1" flat @click="dialog_model.ips.push({})">
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

  created() {
    axios.get(apiUrl('rights')).then(r => {
      this.rights = r.data
    })

    this.$upload.on('photo', {
       url: apiUrl('photo/upload'),
       onSuccess(e, response) {
         this.dialog_model.photo = null
         Vue.nextTick(() => {
           this.dialog_model.photo = response.data
           this.crop_dialog = true
         })
       }
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
    selectFileToUpload() {
      this.$upload.option('photo', 'body', {
        class: 'admin',
        entity_id: this.dialog_model.id
      })
      this.$upload.select('photo')
    },

    cropImage() {
      this.cropping = true
      this.$refs.cropper.getCroppedCanvas().toBlob((blob) => {
        const formData = new FormData()
        formData.append('file', blob)
        formData.append('photo_id', this.dialog_model.photo.id)
        axios.post(apiUrl('photo/crop'), formData).then(r => {
          this.dialog_model.photo = r.data
          this.crop_dialog = false
          setTimeout(() => this.cropping = false, 300)
        })
      })
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

  .photo-actions {
    position: absolute;
    background: rgba(29,32,34,.7);
    color: white;
    height: 36%;
    width: 100%;
    bottom: 0;
    padding-top: 5px;
    & .v-icon {
      color: white;
      height: 16px;
      font-size: 18px;
    }
    & > div {
      opacity: .8;
      cursor: pointer;
      margin-bottom: 4px;
      &:hover {
        opacity: 1;
      }
    }
  }

  .image-upload {
    border-radius: 50%;
    border: 3px #c5c5c5 dashed;
    width: 180px;
    height: 180px;
    -webkit-box-flex: 0 !important;
    -ms-flex: none !important;
    flex: none !important;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c5c5c5;
    cursor: pointer;
    transition: all .2s linear;
    &:hover {
      border-color: #0088ec;
      color: #0088ec;
      background: #e5f1fd;
    }
  }
</style>
