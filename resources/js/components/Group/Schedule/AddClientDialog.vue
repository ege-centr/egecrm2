<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" max-width="300px">
      <v-card>
        <v-card-text>
          <v-container grid-list-xl class="pa-0 ma-0" fluid>
            <v-layout wrap>
              <v-flex md12>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input mb-0'>
                    <v-text-field
                      @input='handleInput'
                      :loading='loading'
                      :hint='client === null ? null : client.names.short'
                      v-model='input' 
                      v-mask="'######'" 
                      label='ID ученика'></v-text-field>
                    </div>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions class='justify-center'>
          <v-btn color='primary' class='ma-0' 
            @click='add' 
            :loading='adding' 
            :disabled='client === null'>
            добавить
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>


<script>
import { API_URL } from '@/components/Client'

export default {
  data() {
    return {
      input: '',
      dialog: false,
      loading: false,
      client: null,
      adding: false,
    }
  },

  methods: {
    open() {
      this.input = ''
      this.dialog = true
    },

    add() {
      this.$emit('added', {
        client: this.client,
        entity_id: this.client.id,
      })
      this.dialog = false
    },

    handleInput: _.debounce(function() {
      if (this.input === '') {
        this.client = null
      } else {
        this.loading = true
        this.client = null
        axios.get(apiUrl(API_URL) + queryString({id: this.input})).then(r => {
          if (r.data.meta.total > 0) {
            this.client = r.data.data[0]
          }
          this.loading = false
        })
      }
    }, 300),
  }
}
</script>
