import Vue from 'vue'
import Vuex from 'vuex'

import auth from './modules/auth'
import alert from './modules/alert'
import project from './modules/project'
import logs from './modules/logs'

Vue.use(Vuex);

export default new Vuex.Store({
 modules: {
  auth, alert, logs, project
 }
})