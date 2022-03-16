<template>
 <div id="chart">
  <apexchart type="bar" height="390" :options="chartOptions" :series="series"></apexchart>
 </div>
</template>
<script>
 import { mapState } from 'vuex';
 export default {
  data: () => ({
   series: [],
   chartOptions: {
    chart: {
     height: 350,
     type: 'bar',
     events: {
      click: function (chart, w, e) {
       // console.log(chart, w, e)
      },
     },
    },
    plotOptions: {
     bar: {
      columnWidth: '90%',
      distributed: true,
     },
    },
    dataLabels: {
     enabled: false,
    },
    legend: {
     show: false,
    },
    xaxis: {
     categories: [],
     tooltip: {
      enabled: false,
      formatter: undefined,
      offsetY: 0,
      style: {
       fontSize: 0,
       fontFamily: 0,
      },
     },
     labels: {
      show: false,
      style: {
       fontSize: '11px',
      },
     },
    },
    yaxis: {
     tickAmount: 7,
    },
   },
  }),
  methods: {
   async getProjects() {
    await this.$store.dispatch('dashboard/getDashboardProjectsTask');
   },
  },
  computed: {
   ...mapState('dashboard', ['dashboardProjectsTask']),
  },
  async mounted() {
   await this.getProjects();
  },
  watch: {
   dashboardProjectsTask() {
    if (this.dashboardProjectsTask.length > 0) {
     let data = [];
     this.dashboardProjectsTask.map((proj, p) => {
      this.chartOptions.xaxis.categories.push(proj.name);
      data.push(proj.tasks_count);
     });
     this.series.push({ name: 'Tasks' });
     this.series[0].data = data
     console.log(data);
    }
   },
  },
 };
</script>
