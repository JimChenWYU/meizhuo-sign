/**
 * Created by lenovo on 2017/01/18.
 */
import base64url from 'base64-url'
import _ from 'lodash'

(function () {
  'use strict';

  function extend(_from, _to) {
    for(let key in _from) {
      _to[key] = _from[key]
    }
    return _to
  }
  function $extension () {}

  extend(base64url, $extension.prototype);
  extend(_, $extension.prototype);

  if (typeof exports === 'object' && typeof module !== 'undefined') {

    module.exports = new $extension()

  }
})();