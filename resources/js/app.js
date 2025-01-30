/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.appName = 'CRM COLORISTA';
window.appErrorMessage = 'ocurrió un error inesperado. intente nuevamente más tarde.';
window.appCannotDeleteMessage = 'No se puede editar un registro eliminado.';
window.appRecordIsDeletedMessage = 'El registro ya está eliminado.';
window.showPreloader = () => {$(".preloader").show();};
window.hidePreloader = () => {$(".preloader").fadeOut();};
Vue.component('agenda', require('./components/agenda/AgendaIndex.vue').default);
window.playSoundScanner = (() => {
    const beepSound = new Audio(window.location.origin+'/sound/scanner-beep.wav');
    return () => {
        beepSound.currentTime = 0; // Reinicia el sonido al principio
        beepSound.play();
    };
})();


import router from './routes';
import auth from './auth';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.mixin(auth)


export const bus = new Vue({
    methods: {
        actualizaragendas(){
            this.$emit('actualizaragendas');
        },
    }
});

const app = new Vue({
    el: '#main-wrapper',
    router,
});
