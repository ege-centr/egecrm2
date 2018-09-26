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
    users: null,
    data: {},
    loading: false
  },
  mutations,
  getters,
  actions
});

export default store;
