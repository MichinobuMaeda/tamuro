/*  
 * Copyright (c) 2017, Michinobu Maeda 
 * Licensed under the MIT License.
 * See LICENSE file in the project root for full license information.  
 */

import { combineReducers } from 'redux'

import title from './title'
import page from './page'
import sess from './sess'
import priv from './priv'
import prim from './prim'
import auth from './auth'
import error from './error'
import wait from './wait'

const tamuroApp = combineReducers({
  title,
  page,
  sess,
  priv,
  prim,
  auth,
  error,
  wait,
})

export default tamuroApp
