/**
 * Created by lenovo on 2017/01/20.
 */
import * as types from '../mutation-types'

// initial state
// shape: [{ id, quantity }]
const state = {
  header_title: null,
  header_type: null
}

const getters = {
  header_title: state => state.header_title,
  header_type: state => state.header_type
}

const mutations = {
  [types.HEAD_SET_TITLE] (state, {title}) {
    state.header_title = title
  },

  [types.HEAD_SET_TYPE] (state, {type}) {
    state.header_type = type
  }
}

export default {
  state,
  getters,
  mutations
}