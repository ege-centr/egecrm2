<template>
  <div>
    <v-slide-y-transition :group='true'>
      <div class='flex-items align-flex-start mb-3' v-for='comment in comments' :key='comment.id'>
        <Avatar :photo='comment.createdAdmin.photo' :size='50' class='mr-3' />
        <div>
          <div>
            <b>{{ comment.createdAdmin.name }}</b>
            <span class='d-inline-block ml-1 grey--text'>{{ comment.created_at | date-time }}</span>
          </div>
          <div>
            {{ comment.text }}
          </div>
        </div>
      </div>
    </v-slide-y-transition>
    <a v-show='!commenting' class='grey--text' @click='startCommenting'>комментировать</a>
    <div class='flex-items align-center' v-show='commenting'>
      <div>
        <Avatar :photo='$store.state.user.photo' :size='50' class='mr-3' />
      </div>
      <div style='flex: 1'>
        <b style='position: absolute'>{{ $store.state.user.first_name }} {{ $store.state.user.last_name }}</b>
        <v-text-field ref='comment' v-model="text" hide-details placeholder='введите комментарий...'
          @blur='endCommenting'
          @keydown.esc='endCommenting'
          @keydown.enter='saveComment'
          :loading='adding'
        ></v-text-field>
      </div>
    </div>
  </div>
</template>

<script>

  import Avatar from '@/components/UI/Avatar'

  const API_URL = 'comments';

  export default {
    props: ['className', 'entityId'],
    data() {
      return {
        adding: false,
        commenting: false,
        text: '',
        comments: []
      }
    },
    created() {
      this.loadData()
    },
    components: { Avatar },
    methods: {
      startCommenting() {
        this.commenting = true
        Vue.nextTick(() => {
          this.$refs.comment.focus()
        })
      },
      endCommenting() {
        this.commenting = false
        this.text = ''
      },
      saveComment() {
        this.adding = true
        axios.post(apiUrl(API_URL), {
          text: this.text,
          class: this.className,
          entity_id: this.entityId
        }).then(r => {
          this.comments.push(r.data)
          this.endCommenting()
          this.adding = false
        })
      },
      loadData() {
        axios.get(apiUrl(API_URL), {
          params: {
            class: this.className,
            entity_id: this.entityId
          }
        }).then(r => {
          this.comments = r.data
        })
      }
    }
  }
</script>
