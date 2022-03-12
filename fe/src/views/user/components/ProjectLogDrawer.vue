<template>
 <v-navigation-drawer v-model="isOpened" fixed temporary right width="420" class="pr-2 pl-3 pt-5">
  <v-list>
   <v-list-item>
    <v-list-item-content>
     <v-list-item-title class="text-h6 font-poppins font-weight-bold text-uppercase">
      Project Activities
     </v-list-item-title>
     <p class="text-subtitle-2 font-weight-light grey--text">Here are the activities for this current project</p>
    </v-list-item-content>
    <v-list-item-action>
     <v-btn @click.prevent="isOpened = false" icon large>
      <v-icon color="blue darken-1">mdi-close-circle</v-icon>
     </v-btn>
    </v-list-item-action>
   </v-list-item>
  </v-list>
  <v-list v-if="projectActivity.length > 0">
   <v-timeline dense>
    <v-timeline-item small v-for="(activity, i) in projectActivity" :key="i">
     <template v-slot:icon>
      <v-tooltip bottom>
       <template v-slot:activator="{ on, attrs }">
        <v-btn v-bind="attrs" v-on="on" small fab elevation="0" class="">
         <v-avatar size="35" color="primary"
          ><span class="white--text"
           >{{ activity.user.info.first_name[0] }}{{ activity.user.info.last_name[0] }}</span
          ></v-avatar
         >
        </v-btn>
       </template>
       <span
        ><small>{{ activity.user.info.first_name }} {{ activity.user.info.last_name }}</small></span
       >
      </v-tooltip>
     </template>
     <div class="line-height-small">
      <!-- <p class="text-h6 mb-0">{{ activity.name }}</p> -->
      <!-- <p class="text-subtitle-2 mt-0 pt-0"></p> -->
      <p class="">{{ activity.description }}</p>
      <small>{{ activity.created_at }}</small>
     </div>
    </v-timeline-item>
   </v-timeline>
  </v-list>
  <p class="text-subtitle ml-4 font-poppins" v-else>No activities for this project.</p>
 </v-navigation-drawer>
</template>
<script>
 import { mapState } from 'vuex';
 export default {
  data: () => ({
   isLoading: false,
   isOpened: {
    get() {
     return this.$store.state['logs'].logState;
    },
    set(val) {
     return val;
    },
   },
  }),
  async mounted() {},
  methods: {},
  computed: {
   ...mapState('logs', ['logState', 'projectActivity']),
  },
  watch: {
   logState() {
    this.isOpened = {
     get() {
      this.$store.state['logs'].logState;
     },
    };
   },
   isOpened(oldVal, newVal) {
    if (oldVal) {
     this.$store.commit('logs/SET_LOG_STATE', !oldVal);
    }
   },
  },
 };
</script>
