<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ editMode ? 'Редактирование' : 'Добавление' }} {{ title }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat v-if='editMode' @click.native="destroy" :loading='destroying'>Удалить</v-btn>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ editMode ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='px-0'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <slot></slot>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>



<script>
export default {
  props: {
    modelDefaults: {
      type: Object,
      required: false,
      default () {
        return {}
      }
    },
    apiUrl: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      required: false,
      default: '',
    }
  },

  data() {
    return {
      dialog: false,
      saving: false,
      item: null,
      loading: true,
      editMode: true,
      destroying: false,
    }
  },

  methods: {
    open(itemId = null, defaults = {}) {
      this.item = null
      this.dialog = true
      if (itemId !== null) {
        this.editMode = true
        this.loadData(itemId)
      } else {
        this.editMode = false
        this.item = {...this.modelDefaults, ...defaults }
        this.loading = false
      }
    },

    loadData(itemId) {
      this.loading = true
      axios.get(apiUrl(this.apiUrl, itemId)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    destroy() {
      this.destroying = true
      axios.delete(apiUrl(this.apiUrl, this.item.id)).then(r => {
        this.$emit('updated')
        this.dialog = false
        this.waitForDialogClose(() => this.destroying = false)
      })
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(this.apiUrl, this.item.id), this.item).then(r => this.item = r.data)
      } else {
        await axios.post(apiUrl(this.apiUrl), this.item).then(r => this.item = r.data)
      }
      this.$emit('updated', this.item)
      colorLog("Emitting updated", 'Turquoise')
      this.dialog = false
      this.waitForDialogClose(() => this.saving = false)
    }
  }
}
</script>
