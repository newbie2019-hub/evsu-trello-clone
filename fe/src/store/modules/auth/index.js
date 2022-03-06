import API from '../../base/'

export default {
  namespaced: true,
  state: {
    user: {
      info: {
        first_name: "Evsu",
        last_name: "Tacloban",
      }
    },
    userinfo: [],
    useraccount: [],
    signup: '',
    userlogs: [],
    accounts: [],
    token: localStorage.getItem('auth') || '',
    selectedAccount: [],
  },
  getters: {
    GET_USER(state) {
      return state.user;
    }
  },
  mutations: {
    SET_ACCOUNTS(state, data){
      state.accounts = data
    },
    SET_VIEW_ACCOUNT(state, data){
      state.selectedAccount = data.data
    },
    SET_AUTH_ACC(state, data) {
      state.userinfo = data.user_info
      state.useraccount = data.user_account
      const bearer_token = localStorage.getItem('auth') || ''
      API.defaults.headers.common['Authorization'] = `Bearer ${bearer_token}`
    },
    SET_ACC(state, data){
      state.user = data
      const bearer_token = localStorage.getItem('auth') || ''
      API.defaults.headers.common['Authorization'] = `Bearer ${bearer_token}`
    },
    SET_USER_LOGS(state, data) {
      state.userlogs = data
    },
    SET_USER_ACC(state, data) {
      state.user = data
    },
    SET_USER_TOKEN(state, token) {
     localStorage.setItem('auth', token)
     localStorage.setItem('isUser', 'true')
     state.token = token

     const bearer_token = localStorage.getItem('auth') || ''
     API.defaults.headers.common['Authorization'] = `Bearer ${bearer_token}`
    },
    SET_AUTH_TOKEN(state, token) {
     localStorage.setItem('auth', token)
     localStorage.setItem('isAdmin', 'true')
     state.token = token

     const bearer_token = localStorage.getItem('auth') || ''
     API.defaults.headers.common['Authorization'] = `Bearer ${bearer_token}`
    },
    UNSET_USER(state){
     localStorage.removeItem('auth');
     localStorage.removeItem('isUser');
     state.token = ''
     state.user = {
        info: {
          first_name: "",
          last_name: "",
        }
      },

     API.defaults.headers.common['Authorization'] = ''
   } 
  },
  actions: {
    async getUserLogs({commit}){
      const res = await API.get('/auth/user/logs').then(res => {
        commit('SET_USER_LOGS', res.data)

        return res;
      }).catch(err => {
       return err
      })

      return res;
    },
    async login({commit}, payload){
      const res = await API.post('/auth/login', payload).then(res => {
        commit('SET_USER_ACC', res.data.user)
        commit('SET_USER_TOKEN', res.data.access_token)

        return res;
      }).catch(err => {
       return err.response;
      })

      return res;
    },
    async signup({commit}, payload){

      const res = await API.post('/auth/store', payload).then(res => {
        return res;
      }).catch(err => {
       return err.response;
      })

      return res;
    },
    async update({commit}, data){
      const res = await API.post(`auth/update`, data).then(res => {
        return res;
      }).catch(err => {
       return err.response;
      })

      return res;
    },
    async logout({commit}){
     const res = await API.post('auth/logout?token=' + localStorage.getItem('auth')).then(response => {
       commit('UNSET_USER')
       return response
     }).catch(error => {
       return error.response
     });

     return res;
   },
   async checkUser({commit, dispatch}) {
    const res = await API.post('auth/me?token=' + localStorage.getItem('auth')).then(res => {
      commit('SET_ACC', res.data)
      return res;
    }).catch(error => {
      if(error.response.status == 401){
        commit('UNSET_USER')
        location.reload()
      }
      return error.response;
    })

    return res;
   },
   async removeImage({commit}){
    const res = await API.post('remove-profile').then(res => {
      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
   async changePassword({commit}, data) {
    const res = await API.post('auth/update-password', data).then(res => {
      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
   async verifyEmail({commit}, data){
    const res = await API.post('user/email/verify', data).then(res => {

      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
   async checkResetPassword({commit}, data){
    const res = await API.post('request/check/reset', data).then(res => {

      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
   async saveResetPassword({commit}, data){
    const res = await API.post('request/reset/password', data).then(res => {

      return res;
    }).catch(error => {
      return error.response;
    })

    return res;
   },
  },
}