<template>
 <div>
  <sidebar />
  <div class="content">
   <navbar />
   <router-view name="dashboard" />
   <router-view name="activities" />
   <router-view name="project" />
   <router-view name="projects" />
   <router-view name="logs" />
   <router-view name="settings" />
  </div>
 </div>
</template>
<script>
 import Sidebar from '../user/components/Sidebar.vue';
 import Navbar from '../user/components/Navbar.vue';
 export default {
  components: { Sidebar, Navbar },
 };
</script>
