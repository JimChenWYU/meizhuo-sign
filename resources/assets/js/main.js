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
import router from './router/index'
import store from './store/index'

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
});