<template>
 <div class="sidebar">
  <div class="profile-container">
   <v-avatar color="grey darken-1" size="90" class="mt-5">
    <img v-if="user.info.image" class="img-cover" :src="`http://127.0.0.1:8000/images/${this.user.info.image}`" alt="John">
    <span v-else class="white--text text-h4">{{this.user.info.first_name[0] + this.user.info.last_name[0]}}</span>
   </v-avatar>
   <div class="profile-container--info">
    <p class="font-weight-bold">{{this.user.info.first_name + ' ' + this.user.info.last_name}}</p>
    <p class="text-caption font-poppins">{{this.user.email}}</p>
   </div>
  </div>
  <p class="text-subtitle-2 ml-4 font-weight-bold">MAIN</p>
  <router-link to="/user/dashboard">
   <v-icon class="mr-1"> mdi-view-dashboard </v-icon>
   Dashboard
  </router-link>
  <v-divider class="ml-8 mr-8"/>
  <router-link to="/user/activities">
   <v-icon class="mr-1"> mdi-playlist-check </v-icon>
   Activities
  </router-link>
  <v-divider class="ml-8 mr-8"/>
  <router-link to="/user/logs">
   <v-icon class="mr-1"> mdi-history </v-icon>
   Logs
  </router-link>
  <v-divider class="ml-8 mr-8"/>
  <router-link to="/user/settings">
   <v-icon class="mr-1"> mdi-account-cog-outline </v-icon>
   Settings
  </router-link>
  <v-divider class="ml-8 mr-8"/>

  <p class="text-subtitle-2 ml-4 mt-6 font-weight-bold">PROJECTS</p>
  <router-link to="/user/projects" :class="{'router-link-exact-active router-link-active' : activeState}">
   <v-icon class="mr-1"> mdi-lightbulb-on-outline </v-icon>
   Projects
  </router-link>
  <v-divider class="ml-8 mr-8"/>

  <div class="logout-container">
   <v-btn @click.stop="dialog = true" text color="error">
    <v-icon class="mr-1"> mdi-close-circle-outline </v-icon>
    Log Out
   </v-btn>
  </div>

  <v-dialog v-model="dialog" max-width="420">
   <v-card>
    <v-card-title class="text-h5"> Confirm Log-out </v-card-title>
    <v-card-text class="mt-3 mb-3">
     Are you sure you want to logout your account?
    </v-card-text>
    <v-card-actions>
     <v-spacer></v-spacer>
     <v-btn color="grey darken-2" text @click="dialog = false"> Cancel </v-btn>
     <v-btn color="red darken-1" text @click="logout"> Logout </v-btn>
    </v-card-actions>
   </v-card>
  </v-dialog>
 </div>
</template>
<script>
import {mapState} from 'vuex'
import API from '../../../store/base'
export default {
  data: () => ({
   dialog: false,
   activeState: false,
  }),
  mounted() {

  },
  computed: {
    ...mapState('auth', ['user'])
  },
  methods: {
   async logout(){
    this.dialog = false
    const {status} = await this.$store.dispatch('auth/logout')
    if (status == 200){
     const alert = {
       msg: 'Log-out successful',
       isVisible: true,
       alertType: 'success'
     }
     this.$router.push({name: 'Login'})
     this.$store.commit('alert/setAlert', alert)
    }
   },
   async uploadProfileImage(){
      if(this.data.profileimg){
        let formData = new FormData();
        formData.append("img", this.data.profileimg);
        await API.post(`user/upload-profile`, formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          },
          }).then(response => {
            this.data.profileimg = null
            const alert = {
              msg: 'Profile image updated successfully!',
              isVisible: true,
              alertType: 'success'
            }
            this.$store.commit('alert/setAlert', alert)
          })
          .catch(error => {
            console.log({ error });
          });
            await this.$store.dispatch('auth/checkAuthUser')
      }
      else {
        this.$toast.error('Please select an image')
      }
   },

  },
  watch: {
    $route (to, from){
      if(to.name == 'Project' || to.name == 'Projects'){
        this.activeState = true
      }
      else {
        this.activeState = false
      }
    }
  }
 };
</script>
<style></style>
