<template>
 <div class="notification">
  <v-alert class="" :value="isVisible" border="left" :type="alertType" transition="scroll-x-transition">{{msg}}</v-alert>
 </div>
</template>
<script>
import {mapState} from 'vuex'
export default {
 computed: {
  ...mapState('alert', ['msg', 'isVisible', 'alertType'])
 },
 watch: {
  msg(){
   // console.count('HI')
    if(this.isVisible){
     setTimeout(() => {
      const alert = {
        isVisible: false,
        // alertType: 'success'
      }
  
      this.$store.commit('alert/setAlert', alert)
     }, 4000)  
    }
  }
 }
}
</script>
<style scoped>
.notification {
 position: fixed;
 top: 1.5rem;
 left: 1.5rem;
 max-width: 400px;
 z-index: 99;
}
</style>