/**
 * Created by lenovo on 2017/01/20.
 */
import * as types from '../mutation-types'

const state = {
  footer_type: null
}

const getters = {
  footer_type: state => state.footer_type
}

const mutations = {
  [types.FOOT_SET_TYPE] (state, {type}) {
    state.footer_type = type
  }
}

export default {
  state,
  getters,
  mutations
}