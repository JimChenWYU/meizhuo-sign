/**
 * Created by lenovo on 2017/01/21.
 */
import * as types from '../mutation-types'

const state = {
  token: null
}

const getters = {
  token: state => state.token
}

const mutations = {
  [types.AUTHORIZATION] (state, {token}) {
    state.token = token
  }
}

export default {
  state,
  getters,
  mutations
}
