import API from '../../base/'

export default {
  namespaced: true,
  state: {
    logs: []
  },
  getters: {
    GET_LOGS(state) {
     return state.logs
    }
  },
  mutations: {
    SET_LOGS(state, payload){
     state.logs = payload
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
  }
}