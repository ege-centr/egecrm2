import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import mutations from './mutations';
import getters from './getters';
import actions from './actions';

const store = new Vuex.Store({
  state: {
    drawer: true,
    user: null,
    data: null,
    loading: false,
    counters: {},
    snackBar: {
      show: false,
      text: '',
      color: 'red'
    },
    search: {
      query: '',
      results: null,
    },
  },
  mutations,
  getters,
  actions
});

export default store;
