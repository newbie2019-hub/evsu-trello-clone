import API from '../../base/'

export default {
  namespaced: true,
  state: {
    isVisible: false,
    msg: '',
    alertType: 'success'
  },
  getters: {
    
  },
  mutations: {
    setVisibility(state, payload){
     state.isVisible = payload
    },
    setAlert(state, payload){
    //  state.msg = 'An error occured!\n'
     state.msg = ''
     console.log(payload)
     if(typeof payload.msg === 'object'){
      for (const [key, value] of Object.entries(payload.msg)) {
        console.log(`${key}: ${value}`);
        state.msg += `${value}\n`
      }
     }
     else {
      state.msg = payload.msg
     }
     state.isVisible = payload.isVisible
     state.alertType = payload.alertType
    }
  },
  actions: {
  }
}