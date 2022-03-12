import API from '../../base/'

export default {
  namespaced: true,
  state: {
    logs: [],
    projectLogs: [],
    projectActivity: [],
    logState: false,
  },
  getters: {
    GET_LOGS(state) {
     return state.logs
    },
    GET_LOG_STATE(state) {
     return state.logState
    }
  },
  mutations: {
    SET_LOG_STATE(state, payload){
     state.logState = payload
    },
    SET_LOGS(state, payload){
     state.logs = payload
    },
    SET_PROJECT_ACTIVITY(state, payload){
     state.projectActivity = payload
    },
    SET_PROJECT_LOGS(state, payload){
     state.projectLogs = payload
    },
  },
  actions: {
   async logs({commit}){
    const res = await API.get('account-logs').then(res => {
      commit('SET_LOGS', res.data)
      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
   async getProjectLogs({commit}){
    const res = await API.get('project-logs').then(res => {
      commit('SET_PROJECT_LOGS', res.data.data)
      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
   async getProjectActivity({commit}, payload){
    console.log(payload)
    const res = await API.post(`project-activity/${payload}`).then(res => {
      commit('SET_PROJECT_ACTIVITY', res.data.data)
      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
  }
}