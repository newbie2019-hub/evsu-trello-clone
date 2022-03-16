import API from '../../base/'

export default {
  namespaced: true,
  state: {
    summary: [],
    dashboardProjects: [],
    dashboardProjectsTask: [],
  },
  getters: {
    GET_LOGS(state) {
      return state.logs
    }
  },
  mutations: {
    SET_SUMMARY(state, payload) {
      state.summary = payload
    },
    SET_DASHBOARD_PROJECTS(state, payload) {
      state.dashboardProjects = payload
    },
    SET_DASHBOARD_PROJECTS_TASK(state, payload) {
      state.dashboardProjectsTask = payload
    },
  },
  actions: {
    async summary({ commit }) {
      const res = await API.get('dashboard').then(res => {
        commit('SET_SUMMARY', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async getDashboardProjects({ commit }) {
      const res = await API.get('project-dashboard').then(res => {
        commit('SET_DASHBOARD_PROJECTS', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async getDashboardProjectsTask({ commit }) {
      const res = await API.get('project-dashboard/task').then(res => {
        commit('SET_DASHBOARD_PROJECTS_TASK', res.data.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
  }
}