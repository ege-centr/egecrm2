<template>
  <v-app id="inspire">
    <ClientLayout v-if='$store.state.user.entity_type === userTypes.client' />
    <TeacherLayout v-if='$store.state.user.entity_type === userTypes.teacher' />
    <AdminLayout v-if='$store.state.user.entity_type === userTypes.admin' />
    <v-content>
      <v-container fluid>
        <transition name="fade">
          <router-view v-show='true'></router-view>
        </transition>
        <v-fade-transition>
          <ContentOverlay v-show='$store.state.search.results !== null'>
            <div class='mb-2'>
              <a @click="$store.commit('clearSearch')">закрыть</a>
            </div>
            <SearchResults></SearchResults>
          </ContentOverlay>
        </v-fade-transition>
      </v-container>
    </v-content>
    <v-dialog
      v-model="$store.state.loading"
      persistent
      width="300"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text class='text-sm-center'>
          Загрузка
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-snackbar v-model="$store.state.snackBar.show"
      :bottom="true"
      :timeout="6000"
      :color='$store.state.snackBar.color'
      class='v-snack_global'
    >
      <span v-html='$store.state.snackBar.text'></span>
    </v-snackbar>

    <ListenToLogout />

  </v-app>
</template>

<script>
  
  import AdminLayout from '@/layouts/Admin/Layout'
  import ClientLayout from '@/layouts/Client/Layout'
  import TeacherLayout from '@/layouts/Teacher/Layout'
  import { SearchResults } from '@/components/Search'
  import ListenToLogout from '@/components/ListenToLogout'
  import ContentOverlay from '@/components/UI/ContentOverlay'

  export default {
    components: { 
      AdminLayout, TeacherLayout, ClientLayout, SearchResults, 
      ListenToLogout, ContentOverlay
    },
  }
</script>
