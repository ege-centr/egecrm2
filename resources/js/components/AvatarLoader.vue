<template>
  <div>
    <div v-if='photo'>
      <v-hover>
        <v-avatar slot-scope="{ hover }" :size='180' style='overflow: hidden'>
          <img :src='photo.url_version' />
          <v-slide-y-reverse-transition>
            <div class='photo-actions' v-show='hover'>
              <div @click="selectFileToUpload">
                <v-icon>arrow_upward</v-icon>
                <span>загрузить новое</span>
              </div>
              <div @click="dialog = true">
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

    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="1000px">
        <v-card>
          <v-card-text>
              <vue-cropper v-if='photo'
                ref="cropper" style='height: 600px'
                :src="photo.url_original"
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
            <v-btn color="blue darken-1" flat @click.native="dialog = false">Отмена</v-btn>
            <v-btn color="blue darken-1" flat @click.native="cropImage" :loading='cropping'>Сохранить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
  </div>
</template>

<script>

import VueCropper from 'vue-cropperjs'

export default {
  props: ['className', 'entityId', 'photo'],

  data() {
    console.log(this.className, this.entityId, this.photo)
    return {
      dialog: false,
      cropping: false
    }
  },

  created() {
    this.$upload.on('photo', {
       url: apiUrl('photo/upload'),
       body: {
         class: this.className,
         entity_id: this.entityId
       },
       onSuccess(e, response) {
         this.$emit('photoChanged', null)
         Vue.nextTick(() => {
           this.$emit('photoChanged', response.data)
           this.dialog = true
         })
       }
    })
  },

  methods: {
    selectFileToUpload() {
      this.$upload.select('photo')
    },

    cropImage() {
      this.cropping = true
      this.$refs.cropper.getCroppedCanvas().toBlob((blob) => {
        const formData = new FormData()
        formData.append('file', blob)
        formData.append('photo_id', this.photo.id)
        axios.post(apiUrl('photo/crop'), formData).then(r => {
          this.$emit('photoChanged', r.data)
          this.dialog = false
          setTimeout(() => this.cropping = false, 300)
        })
      })
    }
  }
}
</script>

<style lang="scss">
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
