import Vue from 'vue'
import App from './App.vue'
import BootstrapVue from 'bootstrap-vue'
import underscore from 'vue-underscore';
import {_} from 'vue-underscore';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vuetify/dist/vuetify.min.css'

import Vuetify from 'vuetify'
Vue.use(Vuetify) //, {
  // iconfont: 'fa' // 'md' || 'mdi' || 'fa' || 'fa4'
// })


Vue.use(BootstrapVue);
Vue.use(underscore);

new Vue({
  el: '#app',
  render: h => h(App)
})
