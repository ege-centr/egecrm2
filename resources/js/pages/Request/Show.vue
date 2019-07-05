<template>
  <div>
    <div class='headline mb-4 flex-items align-center'>
      Заявка {{ $route.params.id }}
    </div>
    <Loader v-if='loading' transparent />
    <div v-else>
      <RequestItem 
        v-for='item in items'
        :key='item.id'
        :item='item' 
        :is-current='item.id == $route.params.id'
        @openDialog='$refs.RequestDialog.open' 
        @openClientDialog='$refs.ClientDialog.open' 
      />
    </div>
    <RequestDialog ref='RequestDialog' @updated='(payload) => item = payload.item' />
    <ClientDialog ref='ClientDialog' />
  </div>
</template>



<script>
import { API_URL } from '@/components/Request'
import RequestItem from '@/components/Request/Item'
import ClientDialog from '@/components/Client/Dialog'
import RequestDialog from '@/components/Request/Dialog'

export default {
  components: { 
    RequestItem, ClientDialog, RequestDialog
  },

  data() {
    return {
      loading: true,
      items: null,
    }
  },

  watch: {
    '$route.params': {
        handler() {
          this.loadData()
        },
        immediate: true,
    }
  },

  methods: {
    loadData() {
      this.items = null
      this.loading = true
      Vue.nextTick(() => {
        axios.get(apiUrl(API_URL, this.$route.params.id), {
          params: {
            resource: 1
          }
        }).then(r => {
          this.items = r.data
          this.loading = false
        })
      })
    },
  }
}
</script>