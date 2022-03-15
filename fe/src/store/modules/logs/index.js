import API from '../../base/'

export default {
  namespaced: true,
  state: {
    logs: [],
    projectLogs: [],
    projectActivity: {
      current_page: null
    },
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
      if(payload.current_page == 1){
        state.projectActivity = payload
      }
      else {
        payload.data.map((data) => {
          state.projectActivity.data.push(data)
        })
        state.projectActivity.current_page = payload.current_page
        state.projectActivity.last_page = payload.last_page
        state.projectActivity.first_page_url = payload.first_page_url
        state.projectActivity.next_page_url = payload.next_page_url
        state.projectActivity.prev_page_url = payload.prev_page_url
        state.projectActivity.to = payload.to
        state.projectActivity.from = payload.from
      }
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
   async getProjectActivity({commit}, {payload: payload, page: page}){
     console.log(page)
    const res = await API.post(`project-activity/${payload}?page=${page}`).then(res => {
      commit('SET_PROJECT_ACTIVITY', res.data.data)
      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
  }
}