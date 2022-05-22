<template>
  <div>
    <v-container>
      <v-layout column>
        <h1>
          Projects
          <v-btn
            @click.prevent="
              createProjectDialog = true;
              formType = 'create';
            "
            color="primary"
            elevation="0"
            x-small
            fab
          >
            <v-icon>mdi-plus</v-icon>
          </v-btn>
        </h1>
        <p class="text-caption">Shown on the table below are the projects that youre currently working on.</p>

        <v-row class="mt-5 mb-3">
          <v-col cols="12" sm="12" md="11" lg="11" xl="8">
            <v-row justify="end">
              <v-col sm="6" md="6" lg="4">
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details dense outlined class="mb-6 mt-4"></v-text-field>
              </v-col>
            </v-row>
            <v-data-table :headers="headers" :items="projects" :search="search" :items-per-page="10" class="mt-4">
              <template v-slot:item.project_status="{ item }">
                <v-chip :color="isOverDue(item.delivery_date) ? 'red darken-2' : 'primary'" dark small>{{isOverDue(item.delivery_date) ? 'Overdue' : 'On-going'}}</v-chip>
              </template>
              <template v-slot:item.members="{ item }">
                <v-layout d-flex>
                  <v-tooltip bottom v-for="(member, i) in item.project_members" :key="i">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn v-bind="attrs" v-on="on" x-small fab elevation="0" class="ml-n5">
                        <v-avatar size="32" :color="randomColor(i)"
                          ><span class="white--text">{{ member.user.info.first_name[0] }}{{ member.user.info.last_name[0] }}</span></v-avatar
                        >
                      </v-btn>
                    </template>
                    <span class="text-center"
                      ><small>{{ member.user.info.first_name[0] }}. {{ member.user.info.last_name }}</small></span
                    ><br />
                    <span class="text-center"
                      ><small>{{ member.role && member.role.role }}</small></span
                    >
                  </v-tooltip>
                  <v-btn color="primary" v-if="item.owner.id == user.id" @click.stop="addMemberDialog(item.id)" x-small fab elevation="0" class="dashed-border ml-n5">
                    <v-icon> mdi-plus </v-icon>
                  </v-btn>
                </v-layout>
              </template>
              <template v-slot:item.actions="{ item }">
                <v-layout>
                  <v-btn @click="viewProject(item)" small text color="green darken-1">View</v-btn>
                  <v-btn
                    v-if="item.user_id == user.id"
                    @click="
                      data = { ...item };
                      formType = 'update';
                      createProjectDialog = true;
                    "
                    small
                    text
                    color="primary darken-1"
                    >Update</v-btn
                  >
                  <v-btn
                    v-if="item.user_id == user.id"
                    @click="
                      dialogDelete = true;
                      delete_data = item.id;
                    "
                    small
                    text
                    color="red darken-1"
                    >Delete</v-btn
                  >
                </v-layout>
              </template>
            </v-data-table>
          </v-col>
        </v-row>
      </v-layout>
    </v-container>

    <!-- Create project dialog --->

    <v-dialog v-model="createProjectDialog" max-width="450">
      <v-card class="pt-3 pb-3 pl-2 pr-2">
        <v-form ref="formCreate" @submit.prevent="createProject" lazy-validation>
          <v-card-title class="text-h5"> {{ formText }} </v-card-title>
          <v-card-text class="mt-3 mb-3">
            Please fill-in the fields so we can {{ formType }} your project.
            <v-text-field
              type="text"
              append-icon="mdi-card-text-outline"
              class="mt-3"
              hide-details="auto"
              v-model="data.name"
              :rules="required"
              label="Project Name"
              required
              outlined
              dense
            ></v-text-field>
            <v-textarea
              type="text"
              append-icon="mdi-information-outline"
              class="mt-3"
              hide-details="auto"
              v-model="data.description"
              :rules="required"
              label="Description"
              required
              auto-grow
              counter
              outlined
              dense
            ></v-textarea>
            <v-menu ref="startdate" v-model="startMenu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="data.start_date"
                  label="Start Date"
                  append-icon="mdi-send-clock-outline"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  :loading="isLoading"
                  :rules="required"
                  hide-details="auto"
                  dense
                  outlined
                ></v-text-field>
              </template>
              <v-date-picker v-model="data.start_date" :active-picker.sync="activePickerStart" @change="saveStart"></v-date-picker>
            </v-menu>
            <v-menu ref="deliverydate" v-model="deliveryMenu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="data.delivery_date"
                  label="Delivery Date"
                  append-icon="mdi-send-clock-outline"
                  hide-details="auto"
                  class="mt-3"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  :loading="isLoading"
                  :rules="required"
                  dense
                  outlined
                ></v-text-field>
              </template>
              <v-date-picker v-model="data.delivery_date" :active-picker.sync="activePicker" @change="save"></v-date-picker>
            </v-menu>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="grey darken-2" text @click="createProjectDialog = false"> Cancel </v-btn>
            <v-btn :loading="isLoading" color="green darken-1" text type="submit"> {{ formType == 'create' ? 'Create' : 'Update' }} </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogDelete" max-width="420">
      <v-card class="pt-3 pb-3">
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <v-card-text class="mt-3 mb-3">
          Are you sure you want to delete this project you're working on?
          <span class="red--text">Warning: You cannot undo this action.</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-2" text @click="dialogDelete = false"> Cancel </v-btn>
          <v-btn color="red darken-1" text @click="deleteProject"> Delete </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <add-project-member :projectId="projectId" />
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import AddProjectMember from './components/AddProjectMember.vue';
  import moment from 'moment';
  export default {
    components: { AddProjectMember },
    data: () => ({
      search: '',
      deliveryMenu: false,
      startMenu: false,
      dialogDelete: false,
      projectId: null,
      delete_data: {
        id: '',
      },
      data: {
        name: '',
        description: '',
        start_date: '',
        delivery_date: '',
      },
      required: [(v) => !!v || 'Field is required'],

      headers: [
        {
          text: 'Project Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Members', value: 'members' },
        { text: 'Description', value: 'description' },
        { text: 'Start Date', value: 'start_date' },
        { text: 'Delivery Date', value: 'delivery_date' },
        { text: 'Project Status', value: 'project_status' },
        { text: 'Date Created', value: 'created_at' },
        { text: 'Action', value: 'actions', align: 'start' },
      ],
      activePicker: null,
      activePickerStart: null,
      createProjectDialog: false,
      isLoading: false,
      formType: 'create',
      formText: 'New Project',
    }),
    async mounted() {
      this.isLoading = true;
      document.title = 'Projects';
      await this.getProjects();
      this.isLoading = false;
    },
    computed: {
      ...mapState('project', ['projects']),
      ...mapState('auth', ['user']),
    },
    methods: {
      randomColor(i) {
        switch (i) {
          case 0:
            return 'orange darken-2';
          case 1:
            return 'teal lighten-2';
          case 2:
            return 'green darken-1';
          case 3:
            return 'red darken-1';
          case 4:
            return 'yellow darken-2';
          case 5:
            return 'blue darken-2';
          default:
            return 'primary';
        }
      },
      isOverDue(date) {
        const currentDate = moment(Date.now()).format('YYYY-MM-DD');
        const isSame = moment(currentDate).isSame(moment(date))
        return isSame
        // console.log(isSame);
      },
      addMemberDialog(id) {
        this.projectId = id;
        this.$store.commit('project/SET_MEMBER_DIALOG', true);
      },
      save(date) {
        this.$refs.deliverydate.save(date);
      },
      saveStart(date) {
        this.$refs.startdate.save(date);
      },
      async getProjects() {
        const { status, data } = await this.$store.dispatch('project/getProjects');
      },
      async deleteProject() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('project/deleteProject', this.delete_data);
        if (status == 200) {
          this.showToast(data.msg);
        } else {
          this.showToast(data, 'error');
        }
        this.dialogDelete = false;
        this.isLoading = false;
      },
      async viewProject(data) {
        this.$router.push({ name: 'Project', params: { slug: data.slug, name: data.name } });
        this.$store.commit('project/SET_SELECTED', data);
      },
      async createProject() {
        const validated = this.$refs.formCreate.validate();

        if (validated) {
          this.isLoading = true;
          if (this.formType == 'create') {
            const { status, data } = await this.$store.dispatch('project/storeProject', this.data);
            if (status == 200) {
              this.showToast(data.msg);
              this.getProjects();
            } else {
              this.showToast(data, 'error');
            }
          } else if (this.formType == 'update') {
            const { status, data } = await this.$store.dispatch('project/updateProject', this.data);
            if (status == 200) {
              this.showToast(data.msg);
            } else {
              this.showToast(data, 'error');
            }
          }
          this.$refs.formCreate.reset();
          this.createProjectDialog = false;
          this.isLoading = false;
        }
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
    watch: {
      formType() {
        if (this.formType == 'create') {
          this.formText = 'New Project';
        }
        if (this.formType == 'update') {
          this.formText = 'Update Project';
        }
      },
      createProjectDialog() {
        if (this.formType == 'create') {
          if (this.$refs.formCreate) {
            this.$refs.formCreate.reset();
          }
        }
      },
    },
  };
</script>
