/**
 * Created by lenovo on 2017/01/18.
 */
import _ from 'lodash'

(function (global, factory) {
  if (typeof exports === 'object' && typeof module !== 'undefined') {
    module.exports = factory()
  }
}(this, (function () { 'use strict';

  function extend(_from, _to) {
    for(let key in _from) {
      _to[key] = _from[key]
    }
    return _to
  }
  function $extension () {}

  extend(_, $extension.prototype);

  return new $extension()

})))