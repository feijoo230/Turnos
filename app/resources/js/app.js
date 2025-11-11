/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
window.Vue = require('vue');
// import ExampleComponent from './components/ExampleComponent.vue'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
// console.log(Vue);
// Vue.component('example-component', ExampleComponent.default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('direccion-tramite', require('./components/DirTramComponent.vue').default);
Vue.component('direccion-tramite1', require('./components/DireTramComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const appElement = document.getElementById('app');
if (appElement) {
    // Si existe, crea la instancia de Vue y monta en el elemento
    const app = new Vue({
        el: '#app',
        // Otras opciones y configuraciones aquí...
    });
}
// const app = new Vue({
//     el: '#app',
// });
