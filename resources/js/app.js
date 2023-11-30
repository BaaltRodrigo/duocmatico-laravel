import './bootstrap';
window.Vue = require('vue').default;
import App from './App.vue';


const app = new Vue({
    el: '#app',
    render: h => h(App)
});