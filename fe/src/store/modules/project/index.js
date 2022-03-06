import API from '../../base/'

export default {
  namespaced: true,
  state: {
    projects: [],
    selected_project: [],
  },
  getters: {
    GET_SELECTED_PROJECT_MEMBERS(state){
      return state.selected_project.members
    }
  },
  mutations: {
    SET_PROJECTS(state, payload) {
      state.projects = payload
    },
    UPDATE_PROJECT(state, payload) {
      state.projects.forEach((project, i) => {
        if (project.id == payload.id) {
          state.projects[i].name = payload.name
          state.projects[i].description = payload.description
          state.projects[i].start_date = payload.start_date
          state.projects[i].delivery_date = payload.delivery_date
        }
      });
    },
    DELETE_PROJECT(state, payload) {
      state.projects.forEach((project, i) => {
        if (project.id == payload) {
          state.projects.splice(i, 1)
        }
      });
    },
    SET_SELECTED(state, payload) {
      state.selected_project = payload
    },
    UPDATE_TASK_DATA(state, payload) {
     state.selected_project.boards[payload.boardIndex].task[payload.taskIndex].task = payload.task
     state.selected_project.boards[payload.boardIndex].task[payload.taskIndex].description = payload.description
     state.selected_project.boards[payload.boardIndex].task[payload.taskIndex].type = payload.type
     state.selected_project.boards[payload.boardIndex].task[payload.taskIndex].status = payload.status
     state.selected_project.boards[payload.boardIndex].task[payload.taskIndex].start_date = payload.start_date
     state.selected_project.boards[payload.boardIndex].task[payload.taskIndex].delivery_date = payload.delivery_date
    },
    UPDATE_TASK_ASSIGNMENT(state, {stateData: payload, data: data}){
      data.forEach((assignee, i) => {
        state.selected_project.boards[payload.boardIndex].task[payload.taskIndex].assignee.push(assignee)
      })
    },
    REMOVE_ASSIGNEE(state, {stateData: payload, data: data, assigneeIndex: AI}){
      state.selected_project.boards[data.boardIndex].task[data.taskIndex].assignee.splice(AI, 1)
    },
    UPDATE_BOARD_TASK(state, payload) {
      state.selected_project.boards.forEach((board, i) => {
        if (board.id == payload.board_id) {
          state.selected_project.boards[i].task.push(payload)
        }
      })
    },
    UPDATE_BOARD_NAME(state, payload) {
      state.selected_project.boards.forEach((board, i) => {
        if (board.id == payload.board_id) {
          state.selected_project.boards[i].name = payload.name
        }
      })
    },
    SAVE_BOARD(state, payload){
      state.selected_project.boards.push(payload)
    }
  },
  actions: {
    async getProjects({ commit }) {
      const res = await API.get('project').then(res => {
        commit('SET_PROJECTS', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async storeProject({ commit }, payload) {
      const res = await API.post('project', payload).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateProject({ commit }, payload) {
      const res = await API.put(`project/${ payload.id }`, payload).then(res => {
        commit('UPDATE_PROJECT', payload)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async deleteProject({ commit }, payload) {
      const res = await API.delete(`project/${ payload }`).then(res => {
        commit('DELETE_PROJECT', payload)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },

    /**
     *  Task Section
     */
    async storeTask({ commit }, payload) {
      const res = await API.post('task', payload).then(res => {
        commit('UPDATE_BOARD_TASK', res.data.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateTask({commit}, payload){
      const res = await API.put(`task/${payload.id}`, payload).then(res => {
        commit('UPDATE_TASK_DATA', payload)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async assignTask({commit}, payload){
      const res = await API.post(`task/assignee`, payload).then(res => {
        commit('UPDATE_TASK_ASSIGNMENT', {stateData: payload, data: res.data.data})
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async removeAssignee({commit}, {assignee: payload, data: data, assigneeIndex: AI}){
      const res = await API.delete(`task/assignee/${payload.id}`).then(res => {
        commit('REMOVE_ASSIGNEE', {stateData: payload, data: data, assigneeIndex: AI})
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateTaskOrder({commit}, payload){
      const res = await API.post('task/update-order', payload).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },


    /**
     *  Board Section
     */

    async storeBoard({commit}, payload){
      const res = await API.post('board', payload).then(res => {
        commit('SAVE_BOARD', res.data.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateBoardOrder({commit}, payload){
      const res = await API.post('board/update-order', payload).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateBoardName({commit}, payload){
      const res = await API.post('board/update-name', payload).then(res => {
        commit('UPDATE_BOARD_NAME', payload)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    
  }
}