/**
 * Created by lenovo on 2017/01/20.
 */
import config from './config'

export default {
  install (Vue, options) {
    Object.defineProperty(Vue.prototype, '$env', {
      get () { return config }
    })
  }
}