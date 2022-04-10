require('./bootstrap');
import $ from 'jquery';
window.$ = window.jQuery = $;
window.Vue = require('vue');
const options = {
    name: '_blank',
    specs: [
        'fullscreen=yes',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css',
        'https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css',
        'https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js',
        'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js',
        'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons',
        './assets/plugins/bootstrap/css/bootstrap.min.css'
    ]
}
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import 'vue-select/dist/vue-select.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootstrap/dist/css/bootstrap.css'
import 'vuetify/dist/vuetify.min.css'
import Vue from 'vue'
import Vuetify from 'vuetify'
Vue.use(VueAlertify);
import swal from 'sweetalert';
import BootstrapVue from 'bootstrap-vue'
import VueAlertify from 'vue-alertify';
import SortedTablePlugin from "vue-sorted-table";
import VueHtmlToPaper from 'vue-html-to-paper';
import vSelect from 'vue-select';
import VueApexCharts from 'vue-apexcharts'
import ToggleButton from 'vue-js-toggle-button'
Vue.component('apexchart', VueApexCharts)
Vue.component('v-select', vSelect);
Vue.use(BootstrapVue)
Vue.use(Vuetify)
Vue.use(VueHtmlToPaper, options);
Vue.use(SortedTablePlugin);
Vue.use(ToggleButton)
    // Vue.component('libro-component', require('./components/LibrosComponent.vue').default);
    // Vue.component('usuario-component', require('./components/UsuarioComponent.vue').default);
    // Vue.component('institucion-component', require('./components/InstitucionComponent.vue').default);
    // Vue.component('vendedor-component', require('./components/VendedorComponent.vue').default);
    // Vue.component('cuaderno-component', require('./components/CuadernosComponent.vue').default);
    // Vue.component('guia-component', require('./components/GuiasComponent.vue').default);
    // Vue.component('planlector-component', require('./components/PlanLectorComponent.vue').default);
    // Vue.component('planificacion-component', require('./components/PlanificacionesComponent.vue').default);
    // Vue.component('material-component', require('./components/MaterialesComponent.vue').default);
    // Vue.component('video-component', require('./components/VideosComponent.vue').default);
    // Vue.component('audio-component', require('./components/AudiosComponent.vue').default);
    // Vue.component('registarplanlector-component', require('./components/RegistrarPlanlectorComponent.vue').default);
    // Vue.component('registarlibro-component', require('./components/RegistrarLibroComponent.vue').default);
    // Vue.component('registarcuaderno-component', require('./components/RegistrarCuadernoComponent.vue').default);
    // Vue.component('registarguia-component', require('./components/RegistrarGuiaComponent.vue').default);
    // Vue.component('registarplanificacion-component', require('./components/RegistrarPlanificacionComponent.vue').default);
    // Vue.component('registarmaterial-component', require('./components/RegistrarMaterialComponent.vue').default);
    // Vue.component('registarvideo-component', require('./components/RegistrarVideoComponent.vue').default);
    // Vue.component('registaraudio-component', require('./components/RegistrarAudioComponent.vue').default);
    // Vue.component('registarasignatura-component', require('./components/RegistrarAsignaturaComponent.vue').default);
    // Vue.component('periodoescolar-component', require('./components/PeriodoEscolarComponent.vue').default);
    // Vue.component('header-component', require('./components/HeaderComponent.vue').default);
    // Vue.component('chat-component', require('./components/ChatComponent.vue').default);
    // Vue.component('papelera-component', require('./components/PapeleraComponent.vue').default);
    // Vue.component('classroom-component', require('./components/ClassroomComponent.vue').default);
    // Vue.component('informacion-component', require('./components/InformacionComponent.vue').default);
    // Vue.component('estudientecurso-component', require('./components/EstudianteCursoComponent.vue').default);
    // Vue.component('contenido-component', require('./components/ContenidoComponent.vue').default);
    // Vue.component('juegos-component', require('./components/JuegosComponent.vue').default);

Vue.component('app', require('./components/AppComponent.vue').default);
Vue.component('posts', require('./components/PostsComponent.vue').default);
Vue.component('InfiniteLoading', require('vue-infinite-loading').default);

import router from './routes'


const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router
});