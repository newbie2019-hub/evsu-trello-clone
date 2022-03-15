<template>
 <div class="mt-5">
  <apexchart type="donut" :options="chartOptions" :series="series"></apexchart>
  <v-layout align-center>
   <p class="mr-2 mb-5 mt-5">Select:</p>
   <v-select :items="projects" label="" hide-details="auto" dense outlined class="w-75 w-md-80"></v-select>
  </v-layout>
 </div>
</template>
<script>
 import { mapState } from 'vuex';
 export default {
  data: () => ({
   projects: [
    {
     text: 'Trello x Jira practice',
     value: 'Trello',
    },
   ],
   series: [],
   chartOptions: {
    chart: {
     type: 'donut',
    },
    labels: [],
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
  },
  async mounted() {
   await this.getProjects();
  },
  watch: {
   dashboardProjects() {
    if (this.dashboardProjects.length > 0) {
     this.series.push(this.dashboardProjects[0].inprogress_tasks);
     this.series.push(this.dashboardProjects[0].pending_tasks);
     this.series.push(this.dashboardProjects[0].nostatus_tasks);
     this.series.push(this.dashboardProjects[0].finished_tasks);
     this.chartOptions.labels.push(['In-Progress']);
     this.chartOptions.labels.push(['Pending']);
     this.chartOptions.labels.push(['No status']);
     this.chartOptions.labels.push(['Finished']);
    }
   },
  },
 };
</script>
