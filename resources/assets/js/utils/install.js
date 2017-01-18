/**
 * Created by lenovo on 2017/01/18.
 */
import Extension from './vue-extension'

(function (global) {
  'use strict';

  function install (Vue, options) {
    Object.defineProperty(Vue.prototype, '$extension', {
      get () { return Extension }
    })
  }

  if (typeof exports === 'object' && typeof module !== 'undefined') {

    module.exports = install

  } else if (typeof define === 'function' && w.define.amd) {

    global.define([], function () { return install })

  }

  if (global.Vue) {
    global.Vue.use(install)
  }
})(window);