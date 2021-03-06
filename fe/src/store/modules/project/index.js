import API from '../../base/'

export default {
  namespaced: true,
  state: {
    projects: [],
    selected_project: [],
    addMemberDialog: false,
    ganttChartState: false,
    chartData: [],
    roles: []
  },
  getters: {
    GET_SELECTED_PROJECT_MEMBERS(state) {
      return state.selected_project.members
    },
    GET_MEMBER_DIALOG(state) {
      return state.addMemberDialog
    }
  },
  mutations: {
  
    SET_ROLES(state, payload) {
      state.roles = payload
    },
    SET_MEMBER_DIALOG(state, payload) {
      state.addMemberDialog = payload
    },
    SET_PROJECTS(state, payload) {
      state.projects = payload
    },
    SET_CHART_STATE(state, payload) {
      state.ganttChartState = payload
    },
    SET_PROJECT_TASKS(state, payload) {
      state.chartData = payload
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
    UPDATE_TASK_ASSIGNMENT(state, { stateData: payload, data: data }) {
      data.forEach((assignee, i) => {
        state.selected_project.boards[payload.boardIndex].task[payload.taskIndex].assignee.push(assignee)
      })
    },
    REMOVE_ASSIGNEE(state, { stateData: payload, data: data, assigneeIndex: AI }) {
      state.selected_project.boards[data.boardIndex].task[data.taskIndex].assignee.splice(AI, 1)
    },
    REMOVE_MEMBER(state, { stateData: payload, memberIndex: AI }) {
      state.selected_project.members.splice(AI, 1)
    },
    REMOVE_PROJECT_ASSIGNEE(state, { stateData: payload, memberIndex: AI }) {
      state.selected_project.boards.map((board, b) => {
        board.task.map((task, t) => {
          task.assignee.map((assignee, a) => {
            if (payload.id == assignee.info.id) {
              state.selected_project.boards[b].task[t].assignee.splice(a, 1)
            }
          })
        })
      })
    },
    REMOVE_TASK(state, payload) {
      state.selected_project.boards[payload.boardIndex].task.map((task, i) => {
        if (task.id == payload.id) {
          state.selected_project.boards[payload.boardIndex].task.splice(i, 1)
        }
      })
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
    SAVE_BOARD(state, payload) {
      state.selected_project.boards.push(payload)
    },
    UPDATE_BOARD(state, payload) {
      state.selected_project.boards.map((board, i) => {
        if (board.id == payload.id) {
          state.selected_project.boards[i].name = payload.name
          state.selected_project.boards[i].description = payload.description
          state.selected_project.boards[i].color = payload.color
          state.selected_project.boards[i].updated_at = payload.updated_at
        }
      })
    },
    REMOVE_BOARD(state, payload) {
      state.selected_project.boards.map((board, i) => {
        if (board.id == payload.id) {
          state.selected_project.boards.splice(i, 1)
        }
      })
    },

    /**
     *  Comment Mutations
     * 
     */
    STORE_COMMENT(state, { resData: data, payload: payload }) {
      state.selected_project.boards[payload.boardIndex].task[payload.taskIndex].comments.unshift(data)
    },

    ADD_PROJECT_MEMBER(state, { data: data, payload: payload }) {
      if (state.selected_project.length > 0) {
        data.forEach((user, i) => {
          state.selected_project.members.push(user.user)
        })
      }
      state.projects.map((project) => {
        if (project.id == payload.project_id) {
          data.forEach((user, i) => {
            project.members.push(user.user)
          })
        }
      })
    }
  },
  actions: {
    async exportFileAttachment({ commit }, payload) {
      // const data = {
      //   id: payload
      // }
      const res = await API.get(`/task/export?id=${ payload.id }`, {
        headers:
        {
          'Content-Disposition': "attachment;",
          'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        },
        responseType: 'arraybuffer',
      }).then(res => {
        return res;
      }).catch(err => {
        return err.response
      })

      return res;
    },
    async deleteFileAttachment({ commit }, payload) {
      const res = await API.delete(`task/attachment/${ payload.id }`, payload).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async getRoles({ commit }) {
      const res = await API.get('roles').then(res => {
        commit('SET_ROLES', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async getSelectedProject({ commit }, payload) {
      const res = await API.post('project/selectedProject', payload).then(res => {
        commit('SET_SELECTED', res.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
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
    async updateMemberRole({ commit }, payload) {
      const res = await API.put(`project-member/${ payload.project_id }`, payload).then(res => {
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
    async removeMember({ commit }, { project: project, member: payload, memberIndex: AI }) {
      const res = await API.delete(`project/${ project.id }/member/${ payload.id }`).then(res => {
        commit('REMOVE_MEMBER', { stateData: payload, memberIndex: AI })
        commit('REMOVE_PROJECT_ASSIGNEE', { stateData: payload, memberIndex: AI })
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },

    /**
     *  Task Section
     */
    async getTasks({ commit }, payload) {
      const res = await API.get(`task/${ payload }`).then(res => {
        commit('SET_PROJECT_TASKS', res.data.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async storeTask({ commit }, payload) {
      const res = await API.post('task', payload).then(res => {
        commit('UPDATE_BOARD_TASK', res.data.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async deleteTask({ commit }, payload) {
      console.log(payload)
      const res = await API.delete(`task/${ payload.id }`).then(res => {
        commit('REMOVE_TASK', payload)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateTask({ commit }, payload) {
      const res = await API.put(`task/${ payload.id }`, payload).then(res => {
        commit('UPDATE_TASK_DATA', payload)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async assignTask({ commit }, payload) {
      const res = await API.post(`task/assignee`, payload).then(res => {
        commit('UPDATE_TASK_ASSIGNMENT', { stateData: payload, data: res.data.data })
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async removeAssignee({ commit }, { assignee: payload, data: data, assigneeIndex: AI }) {
      const res = await API.delete(`task/assignee/${ payload.id }`).then(res => {
        commit('REMOVE_ASSIGNEE', { stateData: payload, data: data, assigneeIndex: AI })
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateTaskOrder({ commit }, payload) {
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

    async storeBoard({ commit }, payload) {
      const res = await API.post('board', payload).then(res => {
        commit('SAVE_BOARD', res.data.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateBoard({ commit }, payload) {
      const res = await API.put(`board/${ payload.id }`, payload).then(res => {
        commit('UPDATE_BOARD', res.data.data)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateBoardOrder({ commit }, payload) {
      const res = await API.post('board/update-order', payload).then(res => {
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async updateBoardName({ commit }, payload) {
      const res = await API.post('board/update-name', payload).then(res => {
        commit('UPDATE_BOARD_NAME', payload)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },
    async deleteBoard({ commit }, payload) {
      const res = await API.delete(`board/${ payload.id }`).then(res => {
        commit('REMOVE_BOARD', payload)
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },

    /** 
     *  Comment Section
     *  
     *  - actions for the comments for the task goes 
     *  here.
     * 
     */

    async storeComment({ commit }, payload) {
      const res = await API.post('task-comment', payload).then(res => {
        commit('STORE_COMMENT', { resData: res.data.data, payload: payload })
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    },

    /**
     *  Project Members Section
     *  
     */
    async addMembers({ commit }, payload) {
      const res = await API.post('project-member', payload).then(res => {
        commit('ADD_PROJECT_MEMBER', { data: res.data.data, payload: payload })
        return res;
      }).catch(error => {
        return error.response;
      })

      return res;
    }

  }
}