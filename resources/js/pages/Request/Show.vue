<template>
  <div>
    <div class='headline mb-4 flex-items align-center'>
      Заявка {{ $route.params.id }}
    </div>
    <Loader v-if='loading' transparent />
    <div v-else>
      <RequestItem :item='item' @openDialog='$refs.RequestDialog.open' @openClientDialog='$refs.ClientDialog.open' />

      <Loader block v-if='relativeRequestsLoading' />
      <div v-else-if='relativeRequests !== null'>
        <div class='my-4 text-sm-center'>
          <v-icon style='transform: rotate(90deg)'>link</v-icon>
        </div>
        <RequestItem v-for='request in relativeRequests' 
          @openDialog='$refs.RequestDialog.open' 
          @openClientDialog='$refs.ClientDialog.open' 
          :item='request' 
          :key='request.id' 
        />
      </div>
    </div>
    <RequestDialog ref='RequestDialog' @updated='(i) => item = i' />
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
      relativeRequestsLoading: false,
      item: null,
      relativeRequests: null,
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
      this.item = null
      this.loading = true
      Vue.nextTick(() => {
        axios.get(apiUrl(API_URL, this.$route.params.id), {
          params: {
            resource: 1
          }
        }).then(r => {
          this.item = r.data
          this.loading = false
          this.loadRelativeRequests()
        })
      })
    },

    loadRelativeRequests() {
      if (this.item.relative_ids.length > 0) {
        this.relativeRequestsLoading = true
        axios.get(apiUrl(API_URL), {
          params: {
            id: this.item.relative_ids.join(',')
          }
        }).then(r => {
          this.relativeRequests = r.data.data
          this.relativeRequestsLoading = false
        })
      }
    }
  }
}
</script>