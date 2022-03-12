<template>
 <div class="">
  <v-container>
   <v-col cols="12" sm="12" md="11" lg="11" xl="8">
    <v-row justify="end">
     <v-btn @click.prevent="setLogDrawerState" text v-if="$route.name == 'Project'">
      <v-icon size="25" color="blue lighten-1">mdi-history</v-icon>
      &nbsp;Activities
     </v-btn>
     <v-menu id="" class="notif-card" rounded="xl" bottom left :offset-y="true" :close-on-click="true">
      <template v-slot:activator="{ on, attrs }">
       <v-btn dark icon v-bind="attrs" v-on="on">
        <v-icon size="25" color="blue lighten-1">mdi-bell</v-icon>
       </v-btn>
      </template>

      <v-list class="pt-6 pb-3 pl-5 pr-5" :max-width="340">
       <p class="font-weight-bold font-poppins ml-2">Notifications</p>
       <v-list-item three-line v-for="(item, i) in items" :key="i">
        <v-avatar color="grey" class="mr-3"></v-avatar>
        <v-list-item-content>
         <v-layout column justify-center>
          <p class="text-body-2">
           {{ item.description }}
          </p>
          <p class="text-caption">02-22-2022 2:50PM</p>
         </v-layout>
        </v-list-item-content>
       </v-list-item>
      </v-list>
     </v-menu>
    </v-row>
   </v-col>
  </v-container>
 </div>
</template>
<script>
 export default {
  data: () => ({
   items: [
    { description: 'John Doe added you as a member in a Project.' },
    { description: 'John Doe added you as a member in a Project.' },
   ],
  }),
  methods: {
   setLogDrawerState() {
    console.log('Clicked');
    this.$store.commit('logs/SET_LOG_STATE', true);
   },
  },

 };
</script>
