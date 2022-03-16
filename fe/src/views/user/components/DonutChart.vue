<template>
 <div>
  <h1>{{ projectName }}</h1>
  <p class="text-caption">Select a project from the dropdown below.</p>
  <div class="mt-5">
   <apexchart type="donut" height="450" :options="chartOptions" :series="series" ></apexchart>
   <v-layout align-center>
    <p class="mr-2 mb-5 mt-5">Select:</p>
    <v-select
     v-model="activeProject"
     :items="projects"
     label=""
     hide-details="auto"
     dense
     outlined
     class="w-75 w-md-80"
    ></v-select>
   </v-layout>
  </div>
 </div>
</template>
<script>
 import { mapState } from 'vuex';
 export default {
  data: () => ({
   activeProject: null,
   projects: [],
   series: [],
   chartOptions: {
    chart: {
     type: 'donut',
    },
    labels: [],
    noData: {
     text: 'No data available',
     align: 'center',
    },
    legend: {
     position: 'right',
     horizontalAlign: 'left',
    },
    responsive: [
     {
      breakpoint: 480,
      options: {
       chart: {
        width: 320,
       },
       legend: {
        position: 'bottom',
       },
      },
     },
    ],
   },
  }),
  methods: {
   async getProjects() {
    await this.$store.dispatch('dashboard/getDashboardProjects');
   },
  },
  computed: {
   ...mapState('dashboard', ['dashboardProjects']),
   projectName() {
    return this.activeProject != null ? this.dashboardProjects[this.activeProject].name : 'Select Project';
   },
  },
  async mounted() {
   await this.getProjects();
  },
  watch: {
   dashboardProjects() {
    if (this.dashboardProjects.length > 0) {
     this.chartOptions.labels.push(['In-Progress']);
     this.chartOptions.labels.push(['Pending']);
     this.chartOptions.labels.push(['No status']);
     this.chartOptions.labels.push(['Finished']);
     this.activeProject = 0;
     this.dashboardProjects.map((project, i) => {
      this.projects.push({ value: i, text: project.name });
     });
    }
   },
   activeProject() {
    this.series.splice(0, this.series.length);
    this.series.push(this.dashboardProjects[this.activeProject].inprogress_tasks);
    this.series.push(this.dashboardProjects[this.activeProject].pending_tasks);
    this.series.push(this.dashboardProjects[this.activeProject].nostatus_tasks);
    this.series.push(this.dashboardProjects[this.activeProject].finished_tasks);
   },
  },
 };
</script>
