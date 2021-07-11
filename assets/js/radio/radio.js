'use strict';

import Vue   from 'vue';
import store from './store';
import Radio from '../../components/radio/Radio';

new Vue({
    el: "#app",
    store,
    template: "<Radio />",
    components: { Radio },
    data: window.__DATA__,
    created() {
        this.$store.commit('setRadioList', this.radioList);
        this.$store.commit('setSettings', this.settings);
    }
});