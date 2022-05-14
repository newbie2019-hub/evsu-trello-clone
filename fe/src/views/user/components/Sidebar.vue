<template>
  <div>
    <v-app-bar elevation="2" :color="$vuetify.theme.isDark ? '' : 'white'" class="pt-2 pr-2" fixed height="68">
      <v-app-bar-nav-icon class="ml-2" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-spacer></v-spacer>
      <v-btn icon @click.prevent="setDarkMode">
        <v-icon color="blue darken-2"> {{ setToggleIcon() }} </v-icon>
      </v-btn>
      <v-btn icon @click.prevent="setLogDrawerState" text v-if="$route.name == 'Project'">
        <v-icon size="25" color="blue darken-2">mdi-history</v-icon>
      </v-btn>
      <v-menu v-if="user.info" transition="slide-y-transition" :close-on-content-click="false" content-class="elevation-3" v-model="showMenu" absolute bottom left style="max-width: 450px">
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon text v-on="on" v-bind="attrs" color="blue darken-2">
            <v-icon>mdi-bell-outline</v-icon>
          </v-btn>
        </template>

        <v-card elevation="0" class="pl-5 pr-5 pt-2 pb-1">
          <v-list dense>
            <v-subheader>NOTIFICATIONS</v-subheader>
          </v-list>
        </v-card>
      </v-menu>
    </v-app-bar>

    <v-navigation-drawer class="pt-4 pl-0 pr-0 pb-2" width="235" elevation="1" v-model="drawer" app>
      <p class="ml-5 mb-4 mt-2">Main Menu</p>
      <v-list dense v-for="(item, i) in items" :key="i" class="pa-0 mb-1">
        <v-list-item link :to="item.link" active-class="v-list-active--item">
          <v-list-item-content class="ml-4 pa-0">
            <v-list-item-title class="font-weight-regular">
              <v-icon size="22" class="mr-2">
                {{ item.icon }}
              </v-icon>
              {{ item.title }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <p class="ml-5 mb-4 mt-6">Logs</p>
      <v-list dense v-for="(item, i) in logItems" :key="i + 7" class="pa-0 mb-1">
        <v-list-item link :to="item.link" active-class="v-list-active--item">
          <v-list-item-content class="ml-4 pa-0">
            <v-list-item-title class="font-weight-regular">
              <v-icon size="22" class="mr-2">
                {{ item.icon }}
              </v-icon>
              {{ item.title }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <template v-slot:append>
        <v-list-item two-line class="mb-2">
          <v-list-item-avatar size="50" class="ma-0" color="primary">
            <img class="cursor-pointer" v-if="user.info.profile_img" :src="`http://127.0.0.1:8000/images/profiles/${user.info.profile_img}`" />
            <p v-else class="white--text font-weight-bold mb-0">{{ user.info.first_name[0] }}{{ user.info.last_name[0] }}</p>
          </v-list-item-avatar>

          <v-list-item-content class="ml-2 line-height-small">
            <v-list-item-title>
              <p class="mb-1 font-weight-bold">{{ `${user.info.last_name}, ${user.info.first_name}` }}</p>
            </v-list-item-title>
            <v-list-item-subtitle>
              <v-btn @click.stop="logoutDialog = true" small color="red darken-2" text class="pa-0">
                <v-icon small> mdi-exit-to-app </v-icon>
                Log-out
              </v-btn>
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>
    </v-navigation-drawer>

    <v-dialog v-model="logoutDialog" max-width="500">
      <v-card>
        <v-card-title class="text-h5"> Confirm Log-out </v-card-title>
        <h4 class="font-weight-light ml-6 mr-5 mb-5">Are you sure you want to logout your account?</h4>
        <v-card-actions class="pb-2">
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" large text @click="logoutDialog = false"> Cancel </v-btn>
          <v-btn color="red darken-1" large text @click="logout" :loading="btnLoading"> Logout </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import API from '@/store/base';
  import { mapState } from 'vuex';
  export default {
    data: () => ({
      drawer: true,
      group: null,
      showMenu: false,
      logoutDialog: false,
      btnLoading: false,
      items: [
        { title: 'Dashboard', icon: 'mdi-view-dashboard', link: '/user/dashboard' },
        { title: 'Activities', icon: 'mdi-playlist-check', link: '/user/activities' },
        { title: 'Account Logs', icon: 'mdi-history', link: '/user/logs', role: '/user/logs' },
        { title: 'Settings', icon: 'mdi-account-cog-outline', link: '/user/settings' },
      ],
      logItems: [{ title: 'Projects', icon: 'mdi-package', link: '/user/projects' }],
      overwriteBreakpoint: false,
    }),
    async mounted() {},
    methods: {
      setLogDrawerState() {
        console.log('Clicked');
        this.$store.commit('logs/SET_LOG_STATE', true);
      },
      async setDarkMode() {
        const darkMode = !JSON.parse(localStorage.getItem('darkMode'));
        this.$vuetify.theme.dark = darkMode;
        localStorage.setItem('darkMode', darkMode);
      },
      setToggleIcon() {
        return JSON.parse(localStorage.getItem('darkMode')) ? 'mdi-weather-sunny' : 'mdi-weather-night';
      },
      async logout() {
        this.btnLoading = true;
        const { status, data } = await this.$store.dispatch('auth/logout');
        this.toastData(status, data);
        this.btnLoading = false;
        this.$router.push('/');
        this.logoutDialog = false;
      },
      async uploadProfileImage(event) {
        let formData = new FormData();
        formData.append('img', event.target.files[0]);
        await API.post(`update-profile`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
          .then((response) => {
            this.toastData(200, { msg: 'Profile image updated successfully!' });
          })
          .catch((error) => {
            console.log({ error });
          });
        await this.$store.dispatch('auth/checkUser');
      },
    },
    computed: {
      ...mapState('auth', ['user']),
    },
    watch: {
      group() {
        this.drawer = false;
      },
    },
  };
</script>
<style>
  .v-list .v-list-active--item {
    /* background-color: #9c5353; */
    color: #3b85df;
    position: relative;
  }

  .v-list-active--item.v-list-item--link:before {
    content: '';
    opacity: 1;
    background: #3b85df;
    position: absolute;
    top: 0;
    width: 4px;
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
  }

  .v-navigation-drawer__content {
    width: 100%;
  }

  .v-toolbar__content {
    padding-bottom: 1rem !important;
  }
</style>
