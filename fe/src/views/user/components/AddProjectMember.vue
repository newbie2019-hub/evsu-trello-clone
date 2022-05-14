<template>
  <div>
    <v-dialog v-model="showDialog" max-width="400">
      <v-card>
        <v-card-title class="text-h5 font-poppins"> Invite Member </v-card-title>
        <v-card-text> Enter the email address to invite your friend to this project. <span class="font-weight-bold"> Invalid email address will be negated.</span> <br /> </v-card-text>
        <v-layout column class="pl-7 pr-7">
          <v-combobox v-model="data.emails" hint="Press enter to save the email address" persistent-hint filled clearable multiple chips deletable-chips autofocus></v-combobox>
          <!-- <v-select v-model="data.role_id" :items="roles" item-text="role" item-value="id"></v-select> -->
          <p class="text-caption">Note: You can set the roles of each individual users after inviting them</p>
          <p class="text-caption red--text mt-2">Note: Users with the email address that are already in this project will not be invited anymore.</p>
        </v-layout>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="showDialog = false"> Cancel </v-btn>
          <v-btn :loading="isLoading" color="green darken-1" text @click="inviteMembers"> Save </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  export default {
    props: ['projectId'],
    data: () => ({
      data: {
        emails: [],
      },
      isLoading: false,
    }),
    async mounted() {
      await this.$store.dispatch('project/getRoles');
    },
    methods: {
      async inviteMembers() {
        if (this.data.emails.length == 0) return (this.showDialog = false);
        this.isLoading = true;
        const memberdata = {
          emails: this.data.emails,
          project_id: this.projectId,
        };
        const { status, data } = await this.$store.dispatch('project/addMembers', memberdata);
        if (status != 200) {
          this.showToast(data, 'error');
        } else {
          this.showToast(data.msg);
        }
        this.isLoading = false;
        this.$store.commit('project/SET_MEMBER_DIALOG', false);
      },
      showToast(msg, alertType = 'success', isVisible = true) {
        const alert = {
          msg: msg,
          isVisible: isVisible,
          alertType: alertType,
        };
        this.$store.commit('alert/setAlert', alert);
      },
    },
    computed: {
      ...mapState('project', ['roles']),
      showDialog: {
        get() {
          return this.$store.getters['project/GET_MEMBER_DIALOG'];
        },
        set(val) {
          this.$store.commit('project/SET_MEMBER_DIALOG', val);
        },
      },
    },
  };
</script>
