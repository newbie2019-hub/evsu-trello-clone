import API from '../../base/'

export default {
  namespaced: true,
  state: {
    summary: []
  },
  getters: {
    GET_LOGS(state) {
     return state.logs
    }
  },
  mutations: {
    SET_SUMMARY(state, payload){
     state.summary = payload
    },
  },
  actions: {
   async summary({commit}){
    const res = await API.get('dashboard').then(res => {
      commit('SET_SUMMARY', res.data)
      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
  }
}