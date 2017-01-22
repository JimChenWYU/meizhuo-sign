/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

//window.$ = window.jQuery = require('jquery');
//require('bootstrap-sass');

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */
import Vue from 'vue'
import Axios from 'axios'
import VueMaterial from 'vue-material'
import Vuerify from 'vuerify'
import Extension from './utils/install'
import Config from './config'
import 'vue-material/dist/vue-material.css'

Vue.use(VueMaterial)
Vue.use(Vuerify)
Vue.use(Extension)
Vue.use(Config)

/**
 * We'll register a HTTP interceptor to attach the "CSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 */
export default class Http {

  constructor (baseUrl) {
    this.http = Axios.create({
      baseUrl: baseUrl
    })

    this.request()

    return this.http
  }

  request () {
    this.http.interceptors.request.use(config => {
      //request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);
      return config
    }, error => Promise.reject(error))

    this.http.interceptors.response.use(response => {
      return response
    }, error => {
      let errResponse = error.response
      let res = ''
      //console.log(errResponse);
      switch (errResponse.status) {
        case 404:
          res = '网络有问题！';
          break;
        case 500:
          res = '服务端繁忙，请稍后！';
          break;
        case 405:
        case 412:
        case 422:
        case 507:
          res = errResponse.data
          break
      }
      return Promise.reject(res)
    })
  }
}

Vue.prototype.$http = new Http(Vue.prototype.$env.baseUrl)

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });