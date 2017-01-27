/**
 * Created by lenovo on 2017/01/27.
 */
/**
 * Created by lenovo on 2017/01/21.
 */

const state = {
  setStorage: (key, value) => window.localStorage.setItem(key, value),
  getStorage: (key) => window.localStorage.getItem(key),
}

export default {
  state
}
