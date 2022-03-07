<template>
 <v-layout column class="pl-14 pr-7 mt-3">
  <v-timeline dense>
   <v-timeline-item v-for="(comment, i) in comments" :key="i" large>
    <template v-slot:icon>
     <span class="white--text">{{ `${comment.user.info.first_name[0]}${comment.user.info.last_name[0]}` }}</span>
    </template>
    <p class="line-height-small">
     <span class="font-weight-bold">{{ `${comment.user.info.first_name} ${comment.user.info.last_name}` }}</span
     ><br />
     <span
      ><small>{{ comment.created_at }}</small></span
     ><br />
     <span class="">{{ comment.comment }}</span> <br />
    </p>
   </v-timeline-item>
  </v-timeline>
  <!-- <v-row align="center" v-for="(comment, i) in comments" :key="i" class="pt-4">
    <v-avatar class="ml-14 mr-3 mt-n1" size="35" color="primary"><span class="white--text font-caption">{{ `${comment.user.info.first_name[0]}${comment.user.info.last_name[0]}` }}</span></v-avatar>
    <p class="line-height-small">{{comment.comment}} <br/>
     <small>{{comment.created_at}}</small>
    </p>
  </v-row> -->
 </v-layout>
</template>
<script>
 import { mapState } from 'vuex';
 export default {
  props: ['comments'],
  data: () => ({
   taskData: {
    comment: '',
   },
  }),
  computed: {
   ...mapState('project', ['selected_project']),
   ...mapState('auth', ['user']),
  },
  methods: {},
 };
</script>
