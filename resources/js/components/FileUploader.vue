<template>
  <div class='file-uploader'>
    <LoadingChip v-for="(file, index) in serverFiles" :key='file.id' :file='file' @remove='removeFile(index, true)' />
    <LoadingChip v-for="(file, index) in $upload.files('file').all" :key='file.$id' :file='file' @remove='removeFile(index)' />
    <v-btn @click='attach' flat fab small v-if="$upload.files('file').all.length < maxFiles" class='v-btn_attach'>
      <v-icon>attach_file</v-icon>
    </v-btn>
  </div>
</template>



<script>
import LoadingChip from '@/components/UI/LoadingChip'

export default {
  components: { LoadingChip },

  props: {
    init: {
      type: Array,
      default: null,
    }
  },

  data() {
    return {
      maxFiles: 30,
      files: [],
    }
  },
  
  created() {
    if (this.init !== null) {
      this.files = this.init
    }
    this.$upload.on('file', {
      extensions: false,
      multiple: true,
      // 100mb, но ограничение на самом деле 20
      maxSizePerFile: 1024 * 1024 * 100,
      maxFilesSelect: this.maxFiles + 10,
      url: apiUrl('upload'),
      onSuccess(e, response) {
        this.files.push(response.data)
        this.$emit('update:files', this.files)
      },
      onBeforeSelect(fileList) {
        this.uploadingError = false
        let size = 0
        if (this.$upload.files('file').all.length + fileList.length > this.maxFiles) {
          this.$store.commit('message', {text: `нельзя прикреплять более ${this.maxFiles} файлов`})
          return false
        }
        this.$upload.files('file').all.forEach(file => size += file.size)
        _.each(fileList, file => size += file.size)
        if (size / 1024 / 1024 >= 20) {
          this.$store.commit('message', {text: 'общий размер файлов больше 20мб'})
          return false
        }
        return true
      }
    })
  },

  destroyed() {
    this.$upload.off('file')
  },

  methods: {
    attach() {
      this.$upload.select('file')
    },

    removeFile(index, serverFile = false) {
      if (!serverFile) {
        index += this.serverFiles.length
      }
      this.files.splice(index, 1)
      this.$emit('update:files', this.files)
    },

    cancelUpload() {
      this.$upload.reset('file')
    },
  },

  computed: {
    serverFiles() {
      return this.files.filter(e => e.id > 0)
    }
  }
}
</script>

<style lang='scss'>
.file-uploader {
  & .v-chip {
    margin-left: 0;
  }
}
</style>