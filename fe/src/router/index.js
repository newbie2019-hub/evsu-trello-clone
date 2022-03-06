import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../views/Login.vue'


/** User Views */
import Dashboard from '../views/user/Dashboard.vue'
import Activities from '../views/user/Activities.vue'
import SelectedProject from '../views/user/SelectedProject.vue'
import Projects from '../views/user/Projects.vue'
import Logs from '../views/user/Logs.vue'
import Settings from '../views/user/Settings.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login,
    meta: {auth: true}
  },
  {
    path: '/signup',
    name: 'Signup',
    component: () => import(/* webpackChunkName: "signup" */ '../views/Signup.vue'),
    meta: {auth: true}
  },
  {
    path: '/user',
    name: 'Home',
    component: () => import(/* webpackChunkName: "home" */ '../views/user/Home.vue'),
    meta: {requiresLogin: true},
    children: [
      {
        path: 'dashboard',
        name: 'Dashboard',
        components: {
          dashboard: Dashboard
        }
      },
      {
        path: 'activities',
        name: 'Activities',
        components: {
          activities: Activities
        }
      },
      {
        path: 'projects',
        name: 'Projects',
        components: {
          projects: Projects
        }
      },
      {
        path: 'project/:slug',
        name: 'Project',
        components: {
          project: SelectedProject
        }
      },
      {
        path: 'logs',
        name: 'Logs',
        components: {
          logs: Logs
        }
      },
      {
        path: 'settings',
        name: 'Settings',
        components: {
          settings: Settings
        }
      },
    ]
  },

]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
	if (to.matched.some((record) => record.meta.requiresLogin) && !localStorage.getItem('auth')){
    next({name: 'Login'})
  }
	else if (to.matched.some((record) => record.meta.auth) && localStorage.getItem('auth')){
    next({name: 'Dashboard'})
  }
  else {
		next();
	}
});

export default router
