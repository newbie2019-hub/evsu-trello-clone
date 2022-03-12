<template>
 <div>
  <v-container>
   <v-row class="mt-5 mb-3">
    <v-col cols="12" sm="12" md="11" lg="11" xl="8">
     <v-breadcrumbs class="pa-0 ma-0" :items="items">
      <template v-slot:divider>
       <v-icon>mdi-chevron-right</v-icon>
      </template>
     </v-breadcrumbs>
     <h1 class="mt-4">{{ selected_project.name }}</h1>
     <p class="text-caption">
      This project was created by {{getOwner()}} on {{ this.selected_project.created_at }}
     </p>
     <v-slide-group multiple show-arrows class="mt-5">
      <v-slide-item v-for="(member, i) in selected_project.members" :key="i" v-slot="{}">
       <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
         <v-btn v-bind="attrs" v-on="on" small fab elevation="0" class="ml-1">
          <v-avatar size="40" color="primary"
           ><span class="white--text">{{ member.info.first_name[0] }}{{ member.info.last_name[0] }}</span></v-avatar
          >
         </v-btn>
        </template>
        <span
         ><small>Member - {{ member.info.first_name[0] }}. {{ member.info.last_name }}</small></span
        >
       </v-tooltip>
      </v-slide-item>
      <v-tooltip bottom>
       <template v-slot:activator="{ on, attrs }">
        <v-btn v-on="on" v-bind="attrs" small fab elevation="0" class="ml-1">
         <v-avatar size="40" color="green darken-1"
          ><span class="white--text"
           >{{ selected_project.owner.info.first_name[0] }}{{ selected_project.owner.info.last_name[0] }}</span
          ></v-avatar
         >
        </v-btn>
       </template>
       <span class=""
        ><small
         >Owner - {{ selected_project.owner.info.first_name[0] }}. {{ selected_project.owner.info.last_name }}</small
        ></span
       >
      </v-tooltip>
      <v-btn
       v-if="selected_project.owner.id == user.id"
       @click.stop="addMemberDialog"
       small
       fab
       elevation="0"
       class="dashed-border ml-1"
      >
       <v-icon> mdi-plus </v-icon>
      </v-btn>
     </v-slide-group>
    </v-col>
   </v-row>

   <v-row class="mt-1 mb-3">
    <v-col cols="12" sm="12" md="11" lg="11" xl="8">
     <draggable
      v-model="selected_project.boards"
      :options="{ animation: 200 }"
      @change="updateBoardOrder"
      group="boards"
      class="boards-container"
     >
      <v-card
       class="mr-5 rounded-lg"
       color="grey lighten-4"
       min-width="320"
       elevation="2"
       max-width="320"
       min-height="60"
       max-height="420"
       v-for="(board, i) in selected_project.boards"
       :key="i"
      >
       <!-- <p
        @click="showEditField(board)"
        v-show="editBoardName != board.id"
        class="font-poppins rounded-tr-lg rounded-tl-lg pl-4 pt-3 pb-3 text-capitalize font-weight-bold sticky"
        :class="board.color">
        {{ board.name }}
       </p> -->
       <v-card-title  :class="board.color">
        <span @click="showEditField(board)" v-show="editBoardName != board.id" class="text-h6">{{ board.name }}</span>
        <v-layout v-if="editBoardName == board.id" class="" d-block>
         <v-text-field
          @blur="updateBoardName"
          v-model="boardData.name"
          autofocus
          outlined
          filled
          hide-details="auto"
          dense
          :class="board.color"
          dark
          class="font-poppins text-capitalize font-weight-bold cursor-pointer"
         >
         </v-text-field>
        </v-layout>
        <v-spacer></v-spacer>
        <v-menu bottom left>
         <template v-slot:activator="{ on, attrs }">
          <v-btn dark icon v-bind="attrs" v-on="on">
           <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
         </template>
         <v-list class="pa-0 ma-0">
          <v-list-item class="pa-0 ma-0">
           <v-btn text block color="green darken-1">Update</v-btn>
          </v-list-item>
          <v-list-item>
           <v-btn text block color="red darken-1">Delete</v-btn>
          </v-list-item>
         </v-list>
        </v-menu>
       </v-card-title>

       <div class="task-container">
        <draggable
         v-model="board.task"
         :options="{ animation: 200 }"
         @move="noteMovedTask"
         @change="updateTaskOrder"
         group="tasks"
        >
         <v-card
          v-for="(task, t) in board.task"
          @click.prevent="setSelectedTask(task, t, i, board.name)"
          :key="t"
          class="mt-2 pt-2 pl-3 pr-2 pb-1 rounded-lg cursor-pointer task-card-hover"
          elevation="1"
         >
          <p>{{ task.task }}</p>
         </v-card>
        </draggable>
        <v-card v-if="showAddIndex == board.id" class="mt-2 pl-3 pr-2 pb-3 rounded-lg" elevation="1">
         <v-text-field
          @blur="saveCardTask"
          @keypress.enter="saveCardTask"
          v-model="data.name"
          persistent-hint
          label=""
          hint="Enter task name"
          autofocus
         ></v-text-field>
        </v-card>
       </div>
       <div class="task-add-container">
        <v-btn @click.prevent="showAddField(board)" class="" color="grey darken-2" block text
         ><v-icon small color="grey darken-2">mdi-plus</v-icon> Add Task</v-btn
        >
       </div>
      </v-card>
      <v-card
       class="mr-5 rounded-lg pl-2 pr-2 pb-2"
       color="grey lighten-4"
       min-width="320"
       elevation="2"
       max-width="320"
       min-height="60"
       max-height="380"
      >
       <v-card v-if="showAddBoard" class="mt-2 pl-3 pr-2 pb-3 rounded-lg" elevation="1">
        <v-text-field
         @blur="saveBoard"
         @keypress.enter="saveBoard"
         v-model="boardData.name"
         persistent-hint
         label=""
         hint="Enter board name"
         autofocus
        ></v-text-field>
       </v-card>
       <div v-else class="task-add-container">
        <v-btn @click.prevent="showAddBoard = true" class="mt-3" color="grey darken-2" block text
         ><v-icon small color="grey darken-2">mdi-plus</v-icon> Add Board</v-btn
        >
       </div>
      </v-card>
     </draggable>
    </v-col>
   </v-row>
  </v-container>

  <v-dialog v-model="taskDialog" max-width="720">
   <v-card class="pt-2 pl-1 pr-1 pb-2">
    <v-card-title class="text-h5">
     <v-icon class="mr-2">mdi-lightning-bolt</v-icon>
     Task
    </v-card-title>
    <v-textarea
     rows="1"
     background-color="grey lighten-4"
     auto-grow
     hide-details="auto"
     v-model="taskData.task"
     label="Task Name"
     class="pl-12 pr-6 ml-2"
     dense
     outlined
    >
    </v-textarea>
    <v-card-text class="pl-14 mt-2">
     This task is created by
     <span class="font-italic font-weight-bold">{{ getTaskOwner() ? 'You' : getTaskOwner() }}</span> on
     {{ taskData.created_at }}<br />
     <span class="font-italic font-weight-bold">{{ taskData.task }}</span> is on a board named
     <span class="font-italic font-weight-bold">{{ taskData.boardName }}</span>
    </v-card-text>
    <p class="text-subtitle-1 font-weight-bold black--text ml-14">Assignee</p>
    <v-layout class="pl-14 pr-6">
     <v-menu bottom left v-for="(assignee, i) in taskInfo.assignee" :key="i" offset-x>
      <template v-slot:activator="{ on: menu, attrs }">
       <v-tooltip bottom>
        <template v-slot:activator="{ on: tooltip }">
         <v-btn small fab elevation="0" class="ml-1" v-bind="attrs" v-on="{ ...tooltip, ...menu }">
          <v-avatar size="40" color="primary"
           ><span class="white--text">{{ assignee.info.first_name[0] }}{{ assignee.info.last_name[0] }}</span></v-avatar
          >
         </v-btn>
        </template>
        <span>{{ assignee.info.first_name }} {{ assignee.info.last_name }}</span>
       </v-tooltip>
      </template>
      <v-list class="pa-0">
       <v-list-item class="pa-0 ma-0">
        <v-btn @click.prevent="removeAssignee(assignee, i)" text depressed color="red darken-1">Remove</v-btn>
       </v-list-item>
      </v-list>
     </v-menu>
     <v-btn @click.stop="dialogAssignee = true" small fab elevation="0" class="dashed-border ml-2">
      <v-icon> mdi-plus </v-icon>
     </v-btn>
    </v-layout>
    <v-card-title class="text-h6 mt-2">
     <v-icon class="mr-2">mdi-card-bulleted</v-icon>
     Description
    </v-card-title>
    <v-textarea
     rows="3"
     background-color="grey lighten-4"
     auto-grow
     hide-details="auto"
     label="Task Description"
     v-model="taskData.description"
     class="pl-14 pr-7"
     outlined
    >
    </v-textarea>
    <v-card-title class="text-h6 mt-2">
     <v-icon class="mr-2">mdi-alert-octagon</v-icon>
     Task Info
    </v-card-title>
    <v-row no-gutters class="pl-14 pr-7">
     <v-col cols="10" sm="6" md="6" lg="6">
      <v-menu
       ref="startdate"
       v-model="startMenu"
       :close-on-content-click="false"
       transition="scale-transition"
       offset-y
       min-width="auto"
      >
       <template v-slot:activator="{ on, attrs }">
        <v-text-field
         background-color="grey lighten-3"
         v-model="taskData.start_date"
         label="Start Date"
         append-icon="mdi-send-clock-outline"
         class="mr-2"
         readonly
         v-bind="attrs"
         v-on="on"
         :loading="isLoading"
         hide-details="auto"
         dense
         outlined
        ></v-text-field>
       </template>
       <v-date-picker
        v-model="taskData.start_date"
        :active-picker.sync="activePickerStart"
        @change="saveStart"
       ></v-date-picker>
      </v-menu>
     </v-col>
     <v-col cols="10" sm="6" md="6" lg="6">
      <v-menu
       ref="deliverydate"
       v-model="deliveryMenu"
       :close-on-content-click="false"
       transition="scale-transition"
       offset-y
       min-width="auto"
      >
       <template v-slot:activator="{ on, attrs }">
        <v-text-field
         background-color="grey lighten-3"
         v-model="taskData.delivery_date"
         label="Delivery Date"
         append-icon="mdi-send-clock-outline"
         hide-details="auto"
         class=""
         readonly
         v-bind="attrs"
         v-on="on"
         :loading="isLoading"
         dense
         outlined
        ></v-text-field>
       </template>
       <v-date-picker
        v-model="taskData.delivery_date"
        :active-picker.sync="activePicker"
        @change="save"
       ></v-date-picker>
      </v-menu>
     </v-col>
    </v-row>
    <v-row no-gutters class="pl-14 pr-7 mt-2">
     <v-col cols="10" sm="6" md="6" lg="6">
      <v-select
       background-color="grey lighten-3"
       v-model="taskData.type"
       class="mr-2"
       :items="taskTypeItem"
       label="Task Type"
       outlined
       dense
      ></v-select>
     </v-col>
     <v-col cols="10" sm="6" md="6" lg="6">
      <v-select
       background-color="grey lighten-3"
       v-model="taskData.status"
       :items="taskStatusItem"
       label="Status"
       outlined
       dense
      ></v-select>
     </v-col>
    </v-row>
    <v-card-actions class="mt-5">
     <v-spacer></v-spacer>
     <v-btn color="green darken-1" text @click="taskDialog = false"> Close </v-btn>
     <v-btn color="green darken-1" text @click.prevent="updateTask"> Save </v-btn>
    </v-card-actions>
    <v-card-title class="text-h6 mt-2">
     <v-icon class="mr-2">mdi-comment-processing</v-icon>
     Comments
    </v-card-title>
    <v-layout>
     <v-avatar class="ml-14" size="45" color="primary"
      ><span class="white--text timeline-comment">{{
       `${this.user.info.first_name[0]}${this.user.info.last_name[0]}`
      }}</span></v-avatar
     >
     <v-textarea
      rows="2"
      background-color="grey lighten-4"
      auto-grow
      hide-details="auto"
      v-model="taskData.comment"
      label="Enter your comment"
      class="pr-7 ml-2"
      dense
      outlined
     >
     </v-textarea>
    </v-layout>
    <v-layout justify-end class="mr-7 mt-4">
     <v-btn @click.prevent="saveComment" text color="primary">Comment</v-btn>
    </v-layout>
    <task-comments :comments="taskInfo.comments" />
   </v-card>
  </v-dialog>

  <!-- Add Assignee Dialog --->
  <v-dialog v-model="dialogAssignee" max-width="420">
   <v-card>
    <v-card-title class="text-h5"> Assign Member </v-card-title>
    <v-card-text> You can select multiple members from the list below to assign to this task </v-card-text>
    <v-select
     v-model="taskData.assignee"
     :items="memberSelection"
     item-value="id"
     class="pl-6 pr-7"
     label="Select members to assign"
     no-data-text="No member is available"
     :menu-props="{ offsetY: true }"
     outlined
     dense
     multiple
    >
     <template slot="item" slot-scope="data">
      <v-avatar class="mr-5" size="35" color="primary">
       <span class="white--text">{{ `${data.item.info.first_name[0]}${data.item.info.last_name[0]}` }}</span>
      </v-avatar>
      <span class="font-weight-bold">
       {{ `${data.item.info.last_name}, ${data.item.info.first_name}` }}
      </span>
     </template>
     <template v-slot:selection="{ item, index }">
      <v-chip v-if="index === 0" class="mt-3 mb-3">
       <span>{{ item.info.first_name }} {{ item.info.last_name }}</span>
      </v-chip>
      <span v-if="index === 1" class="grey--text text-caption"> (+{{ taskData.assignee.length - 1 }} others) </span>
     </template></v-select
    >
    <v-card-actions>
     <v-spacer></v-spacer>
     <v-btn color="green darken-1" text @click="dialogAssignee = false"> Cancel </v-btn>
     <v-btn color="green darken-1" text @click="assignTask"> Assign </v-btn>
    </v-card-actions>
   </v-card>
  </v-dialog>

  <add-project-member :projectId="selected_project.id" />
  <project-log-drawer />
  <gantt-chart />
 </div>
</template>
<script>
 import draggable from 'vuedraggable';
 import { mapState } from 'vuex';
 import TaskComments from './components/TaskComments.vue';
 import AddProjectMember from './components/AddProjectMember.vue';
 import ProjectLogDrawer from './components/ProjectLogDrawer.vue'
 import GanttChart from './components/GanttChart.vue'

 export default {
  data: () => ({
   items: [
    {
     text: 'Dashboard',
     href: '/user/dashboard',
    },
    {
     text: 'Project',
     href: '/user/projects',
    },
   ],
   currentProject: '',
   showAddIndex: null,
   dialogAssignee: false,
   boardIndex: null,
   editBoardName: null,
   showAddBoard: false,
   taskDialog: false,
   isLoading: false,
   required: [(v) => !!v || 'Field is required'],
   taskTypeItem: [
    {
     text: 'Enhancement',
     value: 'Enhancement',
    },
    {
     text: 'New Feature',
     value: 'New Feature',
    },
    {
     text: 'Deploy',
     value: 'Deploy',
    },
    {
     text: 'Issue',
     value: 'Issue',
    },
   ],
   taskStatusItem: [
    {
     text: 'Pending',
     value: 'Pending',
    },
    {
     text: 'In-Progress',
     value: 'In-Progress',
    },
    {
     text: 'For Testing',
     value: 'For Testing',
    },
    {
     text: 'Finished',
     value: 'Finished',
    },
   ],
   taskInfo: {
    user: {
     info: {
      first_name: '',
      last_name: '',
     },
    },
    assignee: [],
   },
   data: {
    name: '',
   },
   taskData: {
    boardName: '',
    comment: '',
    name: '',
    description: '',
    start_date: '',
    delivery_date: '',
    type: '',
    status: '',
    assignee: [],
   },
   boardData: {
    name: '',
   },
   deliveryMenu: false,
   startMenu: false,
   activePicker: null,
   activePickerStart: null,
  }),
  async mounted() {
   this.isLoading = true
   if (this.selected_project.length == 0) {
     this.$router.push({ name: 'Projects' });
   }
   this.items.push({ text: this.currentRouteName, href: '', disabled: true });
   await this.getProjectLogs()
   await this.getChartData()
   this.isLoading = false
  },
  computed: {
   currentRouteName() {
    return this.$route.params.name;
   },
   memberSelection() {
    let projmembers = [...this.$store.getters['project/GET_SELECTED_PROJECT_MEMBERS']];
    let index = [];
    /**
     *  Member should be removed if
     *  it existed on the assignee
     *  already.
     *
     *  Bug on the index that is
     *  being removed.
     *
     *  --- BUG HAS BEEN FIXED ----
     *
     */
    let beenSpliced = false;
    this.selected_project.members.forEach((member, i) => {
     this.taskInfo.assignee.forEach((assignee, a) => {
      if (member.info.id == assignee.user_id) {
       if (beenSpliced) {
        // console.log('Been spliced');
        projmembers.splice(i - 1, 1);
       } else {
        beenSpliced = true;
        projmembers.splice(i, 1);
       }
      }
     });
    });

    return projmembers;
   },
   ...mapState('project', ['selected_project']),
   ...mapState('auth', ['user']),
  },
  components: { draggable, TaskComments, AddProjectMember, ProjectLogDrawer, GanttChart },
  methods: {
   noteMovedTask(evt) {
    console.log(evt);
   },
   async getChartData(){
    await this.$store.dispatch('project/getTasks', this.selected_project.id)
   },
   async getProjectLogs(){
    await this.$store.dispatch('logs/getProjectActivity', this.selected_project.id)
   },
   addMemberDialog() {
    this.$store.commit('project/SET_MEMBER_DIALOG', true);
   },
   save(date) {
    this.$refs.deliverydate.save(date);
   },
   saveStart(date) {
    this.$refs.startdate.save(date);
   },
   getOwner() {
    return this.user.id == this.selected_project.user_id
     ? 'You'
     : this.selected_project.owner.info.first_name + ' ' + this.selected_project.owner.info.last_name;
   },
   getTaskOwner() {
    return this.user.id == this.taskInfo.user_id
     ? true
     : this.taskInfo.user.info.first_name + this.taskInfo.user.info.last_name;
   },
   setSelectedTask(data, taskIndex, boardIndex, boardName) {
    this.taskDialog = true;
    this.taskInfo = data;
    this.taskData.boardName = boardName;
    this.taskData.id = data.id;
    this.taskData.taskIndex = taskIndex;
    this.taskData.boardIndex = boardIndex;
    this.taskData.task = data.task;
    this.taskData.description = data.description;
    this.taskData.start_date = data.start_date;
    this.taskData.delivery_date = data.delivery_date;
    this.taskData.status = data.status;
    this.taskData.type = data.type;
    this.taskData.created_at = data.created_at;
   },
   showAddField(data) {
    this.showAddIndex = data.id;
   },
   showEditField(data) {
    this.editBoardName = data.id;
    this.boardData.name = data.name;
   },
   showToast(msg, alertType = 'success', isVisible = true) {
    const alert = {
     msg: msg,
     isVisible: isVisible,
     alertType: alertType,
    };
    this.$store.commit('alert/setAlert', alert);
   },
   async updateBoardName() {
    if (this.boardData.name.trim() == '') {
     this.editBoardName = '';
     this.boardData.name = '';
    } else {
     const boardData = {
      project_id: this.selected_project.id,
      board_id: this.editBoardName,
      name: this.boardData.name,
     };

     const { status, data } = await this.$store.dispatch('project/updateBoardName', boardData);
     if (status != 200) {
      this.showToast(data, 'error');
      // this.showToast(data.msg);
     }

     this.boardData.name = '';
     this.editBoardName = '';
     await this.getProjectLogs()
    }
   },
   async saveBoard() {
    if (this.boardData.name.trim() == '') {
     this.editBoardName = '';
     this.boardData.name = '';
     this.showAddBoard = false;
    } else {
     const boardData = {
      project_id: this.selected_project.id,
      name: this.boardData.name,
      board_count: this.selected_project.boards.length,
     };

     const { status, data } = await this.$store.dispatch('project/storeBoard', boardData);
     if (status != 200) {
      this.showToast(data, 'error');
      // this.showToast(data.msg);
     }

     this.showAddBoard = false;
     await this.getProjectLogs()

    }
   },
   async updateBoardOrder() {
    this.selected_project.boards.map((board, i) => {
     board.order = i + 1;
    });

    await this.$store.dispatch('project/updateBoardOrder', this.selected_project);
   },
   async updateTaskOrder(data) {
    console.log(data.added);
    this.selected_project.boards.map((board, i) => {
     board.task.map((task, i) => {
      if (data.added && data.added.element.id == task.id) {
       task.moved = true;
      } else {
       task.moved = false;
      }

      task.order = i + 1;
      task.board_id = board.id;
     });
    });

    await this.$store.dispatch('project/updateTaskOrder', this.selected_project);
    await this.getProjectLogs()
   },
   async assignTask() {
    this.isLoading = true;
    const { status, data } = await this.$store.dispatch('project/assignTask', this.taskData);
    if (status != 200) {
     this.showToast(data, 'error');
    }
    this.taskData.assignee = [];
    this.isLoading = false;
    this.dialogAssignee = false;
   },
   async removeAssignee(assignee, i) {
    const { status, data } = await this.$store.dispatch('project/removeAssignee', {
     assignee: assignee,
     data: this.taskData,
     assigneeIndex: i,
    });
    if (status != 200) {
     this.showToast(data, 'error');
    }
   },
   async updateTask() {
    this.isLoading = true;
    const { status, data } = await this.$store.dispatch('project/updateTask', this.taskData);
    if (status != 200) {
     this.showToast(data, 'error');
    }
    this.isLoading = false;
    this.taskDialog = false;
   },
   async saveCardTask() {
    if (this.data.name.trim() == '') {
     this.showAddIndex = null;
    } else {
     this.selected_project.boards.forEach((board, i) => {
      if (board.id == this.showAddIndex) {
       this.boardIndex = i;
      }
     });
     const task = {
      project_id: this.selected_project.id,
      board_id: this.showAddIndex,
      task: this.data.name,
      order: this.selected_project.boards[this.boardIndex].task.length,
     };

     const { status, data } = await this.$store.dispatch('project/storeTask', task);
     if (status == 200) {
      this.showToast(data.msg);
     } else {
      this.showToast(data, 'error');
     }
     this.showAddIndex = null;
     this.data.name = '';
     await this.getProjectLogs()

    }
   },
   async saveComment() {
    const { status, data } = await this.$store.dispatch('project/storeComment', this.taskData);
    if (status == 200) {
     this.showToast(data.msg);
    } else {
     this.showToast(data, 'error');
    }
    this.taskData.comment = '';
     await this.getProjectLogs()

   },
  },
  watch: {
   $route(to, from) {
    this.items.push({ text: to.name, href: '', disabled: true });
   },
  },
 };
</script>
