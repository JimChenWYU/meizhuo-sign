/**
 * Created by lenovo on 2017/01/16.
 */
/**
 * bootstrap http client
 */
import './bootstrap'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import Vue from 'vue'
import App from './App'
import VueMaterial from 'vue-material'
import Vuerify from 'vuerify'
import VuerifyDirective from 'v-vuerify-next'
import router from './router/index'
import Extension from './utils/install'
import 'vue-material/dist/vue-material.css'


Vue.use(VueMaterial);
Vue.use(Vuerify);
Vue.use(VuerifyDirective);
Vue.use(Extension);

//console.log(Extension);

new Vue({
  el: '#app',
  router,
  render: h => h(App)
});