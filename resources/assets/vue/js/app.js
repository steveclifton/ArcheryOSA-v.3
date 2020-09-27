// jQuery
window.$ = window.jQuery = require('jquery');
require('bootstrap');

// Feather Icons
const feather = require('feather-icons')
feather.replace()

// Datatables CSS
require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');

// Axios
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;


// VueJS
import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';

Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    router: new VueRouter(routes)
});

