'use strict';

import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
    radioList: [],
    settings: [],
};

const mutations = {
    setRadioList: (state, payload) => {
        state.radioList = payload;
    },
    setSettings: (state, payload) => {
        state.settings = payload;
    },
};

const actions = {

};

const getters = {
    radioList: state => state.radioList,
    settings: state => state.settings,
};

const store = new Vuex.Store({
    state: state,
    mutations: mutations,
    actions: actions,
    getters: getters
});

export default store;
