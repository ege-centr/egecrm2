<template>
  <v-layout row justify-center>
    <v-dialog persistent v-model="dialog" max-width="300px">
      <v-card>
        <v-card-text>
          <v-container grid-list-xl class="pa-0 ma-0" fluid>
            <v-layout wrap>
              <v-flex md12>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input mb-0'>
                    <v-text-field
                      ref='input'
                      v-model='email' 
                      :validate-on-blur='true'
                      :rules="[rules.email]"
                      label='E-Mail'></v-text-field>
                    </div>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions class='justify-center'>
          <v-btn color='primary' class='ma-0' @click='add' >
            добавить
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>


<script>
export default {
  data() {
    return {
      email: '',
      dialog: false,
      rules: {
          email: value => {
            const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            return pattern.test(value) || 'некорректный email'
          }
        }
    }
  },

  methods: {
    open() {
      this.email = ''
      this.dialog = true
    },

    add() {
      this.$refs.input.blur()
      if (! this.$refs.input.hasError) {
        this.$emit('added', this.email)
        this.dialog = false
      }
    },
  }
}
</script>