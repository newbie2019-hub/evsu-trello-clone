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
            <p class="white--text text-h2 font-weight-bold font-poppins">15</p>
            <p class="white--text text-h6 ml-3 font-weight-bold font-poppins">Projects</p>
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
            <p class="white--text text-h2 font-weight-bold font-poppins">18</p>
            <p class="white--text text-h6 ml-3 font-weight-bold font-poppins">Tasks</p>
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
            <p class="white--text text-h2 font-weight-bold font-poppins">0</p>
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
     <v-col cols="12" sm="12" md="11" lg="4">
      <h1>Projects</h1>
      <p class="text-caption">Select a project from the dropdown below.</p>
      <v-progress-circular class="mt-5" :rotate="-90" value="70" :size="240" :width="35" color="blue darken-1">
       <p class="text-h4 font-poppins font-weight-bold">70%</p>
      </v-progress-circular>
      <p class="mt-6">Select Project</p>
      <v-select :items="projects" label="" hide-details="auto" dense outlined class="w-75 w-md-80"></v-select>
     </v-col>

     <v-col cols="12" sm="12" md="11" lg="7">
      <h1>Assigned Task</h1>
      <p class="text-caption">Shown below are the latest task assigned to you.</p>
      <v-data-table :headers="headers" :items="desserts" :items-per-page="5" class="mt-4"></v-data-table>
     </v-col>
    </v-row>

    <!--- Project Activity --->
    <v-row class="mt-5 mb-3">
     <v-col cols="12" sm="12" md="11" lg="11" xl="8">
      <h1>Project Activities</h1>
      <p class="text-caption">Shown below are the logs of the project.</p>
      <v-data-table :headers="headers" :items="desserts" :items-per-page="5" class="mt-4"></v-data-table>
     </v-col>
    </v-row>
   </v-layout>
  </v-container>
 </div>
</template>
<script>
 export default {
  data: () => ({
   projects: [
    {
     text: 'Trello x Jira practice',
     value: 'Trello',
    },
   ],
   headers: [
    {
     text: 'Dessert (100g serving)',
     align: 'start',
     sortable: false,
     value: 'name',
    },
    { text: 'Calories', value: 'calories' },
    { text: 'Fat (g)', value: 'fat' },
    { text: 'Carbs (g)', value: 'carbs' },
    { text: 'Protein (g)', value: 'protein' },
    { text: 'Iron (%)', value: 'iron' },
   ],
   desserts: [
    {
     name: 'Frozen Yogurt',
     calories: 159,
     fat: 6.0,
     carbs: 24,
     protein: 4.0,
     iron: '1%',
    },
    {
     name: 'Ice cream sandwich',
     calories: 237,
     fat: 9.0,
     carbs: 37,
     protein: 4.3,
     iron: '1%',
    },
    {
     name: 'Eclair',
     calories: 262,
     fat: 16.0,
     carbs: 23,
     protein: 6.0,
     iron: '7%',
    },
    {
     name: 'Cupcake',
     calories: 305,
     fat: 3.7,
     carbs: 67,
     protein: 4.3,
     iron: '8%',
    },
    {
     name: 'Gingerbread',
     calories: 356,
     fat: 16.0,
     carbs: 49,
     protein: 3.9,
     iron: '16%',
    },
    {
     name: 'Jelly bean',
     calories: 375,
     fat: 0.0,
     carbs: 94,
     protein: 0.0,
     iron: '0%',
    },
   ],
  }),
 };
</script>
<style>
 .v-list-item .v-list-item__title,
 .v-list-item .v-list-item__subtitle {
  line-height: 0.5;
 }
</style>
