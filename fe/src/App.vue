<template>
  <v-app>
    <v-main>
      <router-view/>
    </v-main>
    <alert />
  </v-app>
</template>

<script>
import Alert from './views/components/Alert.vue'
export default {
  name: 'App',
  components: {Alert},
  data: () => ({
    //
  }),
  mounted(){
    // this.$vuetify.theme.dark = true
  },
  watch: {
    $route (to, from){
      if(to.meta.auth) return
      this.$store.dispatch('auth/checkUser')
    }
  }
};
</script>
