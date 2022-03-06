<template>
 <div>
  <v-container>
   <v-layout column>
    <h1>Account Logs</h1>
    <p class="text-caption">
     Shown below are the logs of your account. If something is wrong kindly update your login credentials.
    </p>
    <v-row class="mt-5 mb-5">
     <v-col cols="12" sm="12" md="11" lg="11" xl="9">
      <v-row justify="end">
       <v-col sm="6" md="6" lg="4">
        <v-text-field
         v-model="search"
         append-icon="mdi-magnify"
         label="Search"
         single-line
         hide-details
         dense
         outlined
         class="mb-6 mt-4"
        ></v-text-field>
       </v-col>
      </v-row>
      <v-data-table :loading="isLoading" :loading-text="'Retrieving your account logs. Please wait...'" :headers="headers" :items="userLogs" :search="search">
       <template v-slot:item.event="{ item }">
        <v-chip :color="getColor(item.event)" dark small>
         {{ item.event }}
        </v-chip>
       </template>
      </v-data-table>
     </v-col>
    </v-row>
   </v-layout>
  </v-container>
 </div>
</template>
<script>
 import { mapGetters, mapState } from 'vuex';
 export default {
  data: () => ({
   search: '',
   headers: [
    {
     text: 'Activity',
     align: 'start',
     sortable: false,
     value: 'log_name',
    },
    { text: 'Event', value: 'event' },
    { text: 'Description', value: 'description' },
    { text: 'Timestamp', value: 'created_at' },
   ],

   isLoading: false,
  }),
  mounted() {
   this.logs();
  },
  methods: {
   async logs() {
    this.isLoading = true;
    const { status, data } = await this.$store.dispatch('logs/logs');
    this.isLoading = false;
   },
   getColor(event) {
    switch (event) {
     case 'login':
      return 'success';
     case 'update':
      return 'light-green lighten-1';
     case 'logout':
      return 'error';
    }
   },
  },
  computed: {
   ...mapGetters({ userLogs: 'logs/GET_LOGS' }),
  },
 };
</script>
