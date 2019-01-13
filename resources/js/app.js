
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import $ from 'jquery';
window.$ = window.jQuery = $;

window.Vue = require('vue');

Vue.config.devtools = false



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('races', require('./components/Races.vue'));
Vue.component('lobby', require('./components/Lobby.vue'));
Vue.component('single-race', require('./components/SingleRace.vue'));
Vue.component('user-nav', require('./components/UserNav.vue'));
Vue.component('main-nav', require('./components/MainNav.vue'));
Vue.component('next-race', require('./components/NextRace.vue'));
Vue.component('edit-events', require('./components/admin/EditEvents.vue'));

const app = new Vue({
    el: '#app',
    mode: 'production',
    mounted() {
        Echo.channel('race-status')
        .listen('BalanceChanged', (e) => {
            console.log(e)
        });
    }
});
