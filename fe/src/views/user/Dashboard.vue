<template>
 <div>
  <v-container>
   <v-layout column>
    <h1>Summary</h1>
    <p class="text-caption">Welcome back, here is the summary for your account.</p>
    <v-row class="mt-3">
     <v-col cols="12" sm="12" md="11" lg="11" xl="10">
      <v-row>
       <v-col cols="6" sm="6" md="6" lg="4" xl="4">
        <v-card color="green lighten-1" class="mx-auto pt-7 pl-5 pr-5 pb-3" outlined flat hover rounded="xl">
         <v-card-text>
          <v-row align="center" justify="center">
           <v-col cols="6">
            <p class="white--text text-h2 font-weight-bold font-poppins">
             <span v-if="summary.projects < 10" class="">0</span>{{ summary.projects }}
            </p>
            <p class="white--text text-h6 font-weight-bold font-poppins">Projects</p>
           </v-col>
           <v-icon class="mt-n5" color="white" size="7rem" right> mdi-package-variant </v-icon>
          </v-row>
         </v-card-text>
        </v-card>
       </v-col>
       <v-col cols="6" sm="6" md="6" lg="4" xl="4">
        <v-card color="blue lighten-2" class="mx-auto pt-7 pl-5 pr-5 pb-3" outlined flat hover rounded="xl">
         <v-card-text>
          <v-row align="center" justify="center">
           <v-col cols="6">
            <p class="white--text text-h2 font-weight-bold font-poppins">
             <span v-if="summary.tasks.length < 10" class="">0</span>{{ summary.tasks.length }}
            </p>
            <p class="white--text text-h6 font-weight-bold font-poppins">Tasks</p>
           </v-col>
           <v-icon class="mt-n5" color="white" size="7rem" right> mdi-clock-check-outline </v-icon>
          </v-row>
         </v-card-text>
        </v-card>
       </v-col>
       <v-col cols="6" sm="6" md="6" lg="4" xl="4">
        <v-card color="red lighten-2" class="mx-auto pt-7 pl-5 pr-5 pb-3" outlined flat hover rounded="xl">
         <v-card-text>
          <v-row align="center" justify="center">
           <v-col cols="6">
            <p class="white--text text-h2 font-weight-bold font-poppins">
             <span v-if="summary.issues < 10" class="">0</span>{{ summary.issues }}
            </p>
            <p class="white--text text-h6 font-weight-bold font-poppins">Issues</p>
           </v-col>
           <v-icon class="mt-n5" color="white" size="7rem" right> mdi-fire </v-icon>
          </v-row>
         </v-card-text>
        </v-card>
       </v-col>
      </v-row>
     </v-col>
    </v-row>

    <!--- Second row --->
    <v-row class="mt-10 mb-5">
     <v-col cols="12" sm="12" md="11" lg="5" xl="5">
      <h1>Projects</h1>
      <p class="text-caption">Select a project from the dropdown below.</p>
      <donut-chart />
     </v-col>
     <v-col cols="12" sm="12" md="11" lg="5" xl="5">
      <h1>Projects</h1>
      <p class="text-caption">Select a project from the dropdown below.</p>
      <scatter-chart />
     </v-col>
    </v-row>

    <v-row>
     <v-col cols="12" sm="12" md="11" lg="11" xl="8">
      <h1>Assigned Task</h1>
      <p class="text-caption">Shown below are the latest task assigned to you.</p>
      <v-data-table :headers="taskHeaders" :items="summary.tasks" :items-per-page="5" class="mt-4"></v-data-table>
     </v-col>
    </v-row>

    <!--- Project Activity --->
    <v-row class="mt-5 mb-3">
     <v-col cols="12" sm="12" md="11" lg="11" xl="8">
      <h1>Project Activities</h1>
      <p class="text-caption">Shown below are the logs of the project.</p>
      <v-data-table :headers="headers" :items="summary.activity" :items-per-page="10" class="mt-4">
       <template v-slot:item.user="{ item }"> {{ item.user.info.first_name }} {{ item.user.info.last_name }} </template>
      </v-data-table>
     </v-col>
    </v-row>
   </v-layout>
  </v-container>
 </div>
</template>
<script>
 import { mapState } from 'vuex';
 import DonutChart from './components/DonutChart.vue';
 import ScatterChart from './components/ScatterChart.vue';
 export default {
  data: () => ({
   projects: [
    {
     text: 'Trello x Jira practice',
     value: 'Trello',
    },
   ],
   taskHeaders: [
    {
     text: 'Task',
     align: 'start',
     sortable: true,
     value: 'task',
    },
    {
     text: 'Type',
     align: 'start',
     sortable: true,
     value: 'type',
    },
    {
     text: 'Status',
     align: 'start',
     sortable: true,
     value: 'status',
    },
    {
     text: 'On Project',
     align: 'start',
     sortable: true,
     value: 'project.name',
    },
    {
     text: 'Start Date',
     align: 'start',
     sortable: true,
     value: 'start_date',
    },
    {
     text: 'Delivery Date',
     align: 'start',
     sortable: true,
     value: 'delivery_date',
    },
    {
     text: 'Created On',
     align: 'start',
     sortable: true,
     value: 'created_at',
    },
   ],
   headers: [
    {
     text: 'Activity Name',
     align: 'start',
     sortable: false,
     value: 'name',
    },
    { text: 'Event', value: 'event' },
    { text: 'Description', value: 'description' },
    { text: 'User', value: 'user' },
    { text: 'On Project', value: 'project.name' },
   ],
  }),
  async mounted() {
   document.title = 'Dashboard - Welcome ';
   await this.$store.dispatch('dashboard/summary');
  },
  computed: {
   ...mapState('dashboard', ['summary']),
  },
  components: { DonutChart, ScatterChart },
 };
</script>
<style>
 .v-list-item .v-list-item__title,
 .v-list-item .v-list-item__subtitle {
  line-height: 0.5;
 }
</style>
