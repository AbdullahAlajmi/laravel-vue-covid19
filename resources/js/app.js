require('./bootstrap');

import Vue from 'vue'
import VueAxios from 'vue-axios';
import axios from 'axios';
import App from './App.vue';


Vue.use(VueAxios, axios);

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    render: h => h(App)
});
