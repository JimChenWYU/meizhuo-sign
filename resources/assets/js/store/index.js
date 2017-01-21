/**
 * Created by lenovo on 2017/01/20.
 */
import Vue from 'vue'
import Vuex from 'vuex'
import * as actions from './actions'
import * as getters from './getters'
import createLogger from 'logger'
import header from './modules/header'
import footer from './modules/footer'
import body from './modules/body'
import admin from './modules/admin'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  actions,
  getters,
  modules: {
    header, footer, body, admin
  },
  strict: debug,
  plugins: debug ? [createLogger()] : []
})