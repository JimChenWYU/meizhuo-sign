/**
 * Created by lenovo on 2017/01/21.
 */
import * as types from '../mutation-types'

const key = 'token'
const msg = 'msg'

const state = {
  setToken: (value) => window.localStorage.setItem(key, value),
  getToken: () => window.localStorage.getItem(key),
  listCondition: {
    page: null,
    name: null,
    student_id: null,
    department: null
  },
  map: {
    all: null,
    android: '移动组',
    web: 'Web组',
    design: '美工组',
    marking: '营销策划'
  }
}

const getters = {
  listCondition: state => state.listCondition,
  map: state => state.map
}

const mutations = {
  [types.ADMIN_SET_AUTHORIZATION] (state, {token}) {
    state.setToken(token)
  },

  [types.ADMIN_SET_LIST_CONDITION] (state, {listCondition}) {
    // console.log(listCondition)
    state.listCondition = Object.assign(state.listCondition, listCondition)
  }
}

export default {
  getters,
  state,
  mutations
}
