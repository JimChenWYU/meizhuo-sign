/**
 * Created by lenovo on 2017/01/20.
 */
import * as types from './mutation-types'

export const setHeader = ({ commit }, header) => {
  if (typeof header !== 'undefined') {
    if (typeof header.title !== 'undefined') {
      commit(types.HEAD_SET_TITLE, {
        title: header.title
      })
    }

    if (typeof header.type !== 'undefined') {
      commit(types.HEAD_SET_TYPE, {
        type: header.type
      })
    }
  }
}

export const setFooter = ({ commit }, footer) => {
  if (typeof footer !== 'undefined') {
    if (typeof footer.type !== 'undefined') {
      commit(types.FOOT_SET_TYPE, {
        type: footer.type
      })
    }
  }
}


export const setBody = ({ commit }, body) => {
  if (typeof body !== 'undefined') {
    if (typeof body.type !== 'undefined') {
      commit(types.BODY_SET_TYPE, {
        type: body.type
      })
    }
  }
}

export const setAdmin = ({ commit }, admin) => {
  if (typeof admin !== 'undefined') {
    commit(types.ADMIN_SET_AUTHORIZATION, {
      token: admin.token
    })
  }
}