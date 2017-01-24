/**
 * Created by lenovo on 2017/01/20.
 */
/**
 * Created by lenovo on 2017/01/20.
 */
import * as types from '../mutation-types'

const state = {
  body_type: null
}

const getters = {
  body_type: state => state.body_type
}

const mutations = {
  [types.BODY_SET_TYPE] (state, {type}) {
    state.body_type = type
  }
}

export default {
  state,
  getters,
  mutations
}