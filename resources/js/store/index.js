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
    data: {
      admins: null,
      subjects: null,
      branches: null,
      grades: null,
      years: null
    },
    loading: false
  },
  mutations,
  getters,
  actions
});

export default store;
