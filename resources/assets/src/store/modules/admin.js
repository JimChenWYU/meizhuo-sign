/**
 * Created by lenovo on 2017/01/21.
 */
import * as types from '../mutation-types'

const key = 'token'

const state = {
  setToken: (value) => window.localStorage.setItem(key, value),
  getToken: () => window.localStorage.getItem(key)
}

const mutations = {
  [types.ADMIN_SET_AUTHORIZATION] (state, {token}) {
    state.setToken(token)
  }
}

export default {
  state,
  mutations
}
