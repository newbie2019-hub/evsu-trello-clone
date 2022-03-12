<template>
 <div>
  <v-container>
   <v-layout column>
    <h1>Project Activities</h1>
    <p class="text-caption">Shown below are the activities of the projects your currently working on.</p>
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
      <v-data-table :search="search" :headers="headers" :items="projectLogs" :items-per-page="10" class="">
       <template v-slot:item.user="{ item }"> {{ item.user.info.first_name }} {{ item.user.info.last_name }} </template>
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

   isLoading: false,
  }),
  async mounted() {
   this.isLoading = true;
   document.title = "Project Activities"
   await this.$store.dispatch('logs/getProjectLogs');
   this.isLoading = false;
  },
  computed: {
   ...mapState('logs', ['projectLogs']),
  },
 };
</script>
