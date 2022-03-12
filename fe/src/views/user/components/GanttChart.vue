<template>
 <div>
  <div v-if="chartState" class="chart-container">
   <v-layout class="text-center">
    <p class="text-h6 font-poppins font-weight-bold text-center">{{ selected_project.name }}</p>
    <v-layout justify-end>
     <v-btn icon @click="chartState = false">
      <v-icon>mdi-close</v-icon>
     </v-btn>
    </v-layout>
   </v-layout>
   <!-- <v-toolbar dark color="primary">
   
     <v-toolbar-title>{{ selected_project.name }} - Gantt Chart</v-toolbar-title>
     <v-spacer></v-spacer>

    </v-toolbar> -->

   <div class="chart-placeholder">
    <apexchart
     id="apex-chart-height"
     class="h-100"
     type="rangeBar"
     :options="chartOptions"
     :series="series"
    ></apexchart>
   </div>
  </div>
 </div>
</template>
<script>
 import { mapState } from 'vuex';
 import moment from 'moment';
 export default {
  data: () => ({
   dialog: true,
   widgets: true,
   sound: true,
   notifications: true,
   series: [],
   chartOptions: {
    chart: {
     width: '100%',
     height: 350,
     type: 'rangeBar',
    },
    dataLabels: {
     enabled: true,
     formatter: function (val, opts) {
      // var label = opts.w.globals.labels[opts.dataPointIndex];
      var a = moment(val[0]);
      var b = moment(val[1]);
      var diff = b.diff(a, 'days');
      return diff + (diff > 1 ? ' days' : ' day');
     },
    },
    plotOptions: {
     bar: {
      horizontal: true,
      dataLabels: {
       hideOverflowingLabels: false,
      },
     },
    },
    xaxis: {
     type: 'datetime',
    },
    grid: {
     row: {
      colors: ['#f3f4f5', '#fff'],
      opacity: 1,
     },
    },
   },
   chartState: {
    get() {
     return this.$store.state['project'].ganttChartState;
    },
    set(val) {
     return val;
    },
   },
  }),

  computed: {
   ...mapState('project', ['selected_project']),
   ...mapState('project', ['ganttChartState', 'chartData']),
  },
  methods: {
   setChartData() {
    let series = {
     name: '',
     data: [],
    };
    this.chartData.map((task) => {
     series = {
      // name: '',
      data: [],
     };
     // series.name = task.user.info.first_name[0] + '. ' + task.user.info.last_name;
     series.data.push({
      x: task.task,
      y: [
       task.start_date ? new Date(task.start_date).getTime() : new Date(task.created_at).getTime(),
       task.delivery_date ? new Date(task.delivery_date).getTime() : new Date(task.project.delivery_date).getTime(),
      ],
     });
     this.series.push(series);
    });
    // this.chartData.map((user) => {
    //  series = {
    //   name: '',
    //   data: [],
    //  };
    //  series.name = user.info.first_name[0] + '. ' + user.info.last_name;
    //  user.tasks.map((task) => {
    //   if (task.project_id == this.selected_project.id) {
    //    series.data.push({
    //     x: task.task,
    //     y: [
    //      task.start_date ? new Date(task.start_date).getTime() : new Date(task.created_at).getTime(),
    //      task.delivery_date ? new Date(task.delivery_date).getTime() : new Date(task.project.delivery_date).getTime(),
    //     ],
    //    });
    //   }
    //  });
   },
  },
  watch: {
   chartData() {
    if (this.chartData) {
     this.setChartData();
    }
   },
   ganttChartState() {
    this.chartState = {
     get() {
      this.$store.state['project'].ganttChartState;
     },
    };
   },
   chartState(oldVal, newVal) {
    if (oldVal) {
     this.$store.commit('project/SET_CHART_STATE', !oldVal);
    }
   },
  },
 };
</script>
<style>
 .chart-container {
  position: fixed;
  top: 0;
  left: 0;
  background: white;
  height: 100%;
  width: 100%;
  z-index: 999;
  padding: 1rem 1rem;
  overflow-y: auto;
 }

 .chart-placeholder {
  position: relative;
  left: 50%;
  transform: translateX(-50%);
  max-width: 1024px;
 }
</style>
