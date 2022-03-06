<template>
 <div>
  <v-container>
   <v-layout column>
    <h1>Account Settings</h1>
    <p class="text-caption">Please make sure that your informations entered are correct.</p>

    <v-row>
     <v-col cols="12" sm="12" md="11" lg="11" xl="8">
      <v-divider class="mt-7" />
      <h2 class="mt-10">Profile</h2>
      <p class="text-caption">This information will be displayed publicly so becareful what you share.</p>
      <v-form ref="form" v-model="valid" @submit.prevent="update" lazy-validation>
       <v-row dense class="mt-3">
        <v-col cols="11" sm="10" md="6" lg="6">
         <v-text-field
          append-icon="mdi-card-bulleted"
          class=""
          hide-details="auto"
          :rules="required"
          label="First Name"
          :loading="isLoading"
          v-model="data.first_name"
          required
          dense
          outlined
         ></v-text-field>
        </v-col>
        <v-col cols="11" sm="10" md="6" lg="6">
         <v-text-field
          append-icon="mdi-card-bulleted"
          class=""
          hide-details="auto"
          label="Middle Name"
          :loading="isLoading"
          v-model="data.middle_name"
          required
          outlined
          dense
         ></v-text-field>
        </v-col>
        <v-col cols="11" sm="10" md="6" lg="6">
         <v-text-field
          append-icon="mdi-card-bulleted"
          class=""
          hide-details="auto"
          :rules="required"
          label="Last Name"
          :loading="isLoading"
          v-model="data.last_name"
          required
          dense
          outlined
         ></v-text-field>
        </v-col>
        <v-col cols="11" sm="10" md="6" lg="6">
         <v-text-field
          append-icon="mdi-card-bulleted"
          class=""
          hide-details="auto"
          :rules="required"
          label="Address"
          :loading="isLoading"
          v-model="data.address"
          required
          dense
          outlined
         ></v-text-field>
        </v-col>
        <v-col cols="11" sm="10" md="6" lg="6">
         <v-select
          v-model="data.gender"
          :rules="required"
          :items="gender"
          label="Gender"
          hide-details="auto"
          outlined
          dense
         ></v-select>
        </v-col>
        <v-col cols="11" sm="10" md="6" lg="6">
         <v-text-field
          append-icon="mdi-phone"
          class=""
          hide-details="auto"
          :rules="required"
          label="Contact Number"
          :loading="isLoading"
          v-model="data.contact_number"
          dense
          outlined
         ></v-text-field>
        </v-col>
        <v-col cols="11" sm="10" md="6" lg="6">
         <v-menu
          ref="menu"
          v-model="menu"
          :close-on-content-click="false"
          transition="scale-transition"
          offset-y
          min-width="auto">
          <template v-slot:activator="{ on, attrs }">
           <v-text-field
            v-model="data.birthday"
            label="Date of Birth"
            append-icon="mdi-calendar"
            readonly
            v-bind="attrs"
            v-on="on"
            :loading="isLoading"
            :rules="required"
            dense
            outlined
           ></v-text-field>
          </template>
          <v-date-picker
           v-model="data.birthday"
           :active-picker.sync="activePicker"
           min="1960-01-01"
           :max="maxBirthDate"
           @change="save"
           landscape
          ></v-date-picker>
         </v-menu>
        </v-col>
       </v-row>

       <v-divider class="mt-7" />
       <!--- Photo --->
       <h2 class="mt-8">Photo</h2>
       <p class="text-caption">Your photo will be displayed publicly.</p>
       <v-row class="mt-3" align="center">
        <v-avatar color="grey darken-1" size="70" class="ml-3">
         <img
          class="img-cover"
          v-if="user.info.image"
          :src="`http://127.0.0.1:8000/images/${this.user.info.image}`"
          alt="John"
         />
         <span v-else class="white--text text-h6">{{ this.data.first_name[0] + this.data.last_name[0] }}</span>
        </v-avatar>
        <label
         for="uploadimg"
         class="ml-3 v-btn v-btn--outlined v-btn--rounded theme--light v-size--default success--text cursor-pointer"
         >Upload</label
        >
        <input type="file" id="uploadimg" @change="uploadProfileImage" />
        <v-dialog v-model="dialogRemove" persistent max-width="400">
         <template v-slot:activator="{ on, attrs }">
          <v-btn
           :disabled="user.info.image == null"
           color="secondary"
           class="ml-2"
           v-bind="attrs"
           v-on="on"
           outlined
           rounded
          >
           Remove</v-btn
          >
         </template>
         <v-card>
          <v-card-title class="text-h5"> Remove Image </v-card-title>
          <v-card-text>
           Are you sure you want to remove your current profile image? You cannot undo this action.
          </v-card-text>
          <v-card-actions>
           <v-spacer></v-spacer>
           <v-btn color="grey darken-1" text @click="dialogRemove = false"> Cancel </v-btn>
           <v-btn @click.prevent="removeImage" color="red darken-1" text :loading="isLoading"> Remove </v-btn>
          </v-card-actions>
         </v-card>
        </v-dialog>
       </v-row>

       <!--- Photo Section --->
       <v-divider class="mt-15" />

       <h2 class="mt-10">Login Credentials</h2>
       <p class="text-caption">Please ensure that your login credentials are correct.</p>
       <v-row class="mt-3">
        <v-col cols="11" sm="10" md="6" lg="6">
         <v-text-field
          append-icon="mdi-email"
          class=""
          hide-details="auto"
          :rules="required"
          label="Email Address"
          :loading="isLoading"
          v-model="data.email"
          disabled
          required
          dense
          outlined
         ></v-text-field>
        </v-col>
        <v-col cols="11" sm="10" md="6" lg="6">
         <v-text-field
          append-icon="mdi-key"
          class=""
          type="password"
          hide-details="auto"
          :rules="required"
          label="Password"
          :loading="isLoading"
          v-model="data.password"
          disabled
          readonly
          dense
          outlined
         >
          <v-icon @click.prevent="dialogPassword = true" slot="append-outer" color="green"> mdi-pencil </v-icon>
         </v-text-field>
        </v-col>
       </v-row>
       <v-dialog v-model="dialog" persistent max-width="400">
        <template v-slot:activator="{ on, attrs }">
         <v-btn color="success" class="mt-5 mb-6" dark v-bind="attrs" v-on="on" depressed> Save Changes </v-btn>
        </template>
        <v-card>
         <v-card-title class="text-h5"> Confirm Changes </v-card-title>
         <v-card-text>
          Please enter your current password to save your changes.
          <v-text-field
           type="password"
           append-icon="mdi-key"
           class="mt-3"
           hide-details="auto"
           v-model="data.current_password"
           :rules="required"
           label="Current Password"
           required
           outlined
           dense
          ></v-text-field>
         </v-card-text>
         <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey darken-1" text @click="dialog = false"> Cancel </v-btn>
          <v-btn @click.prevent="update" color="green darken-1" text> Confirm </v-btn>
         </v-card-actions>
        </v-card>
       </v-dialog>
      </v-form>
     </v-col>
    </v-row>
   </v-layout>
  </v-container>

  <v-dialog v-model="dialogPassword" max-width="420">
   <v-card>
    <v-form ref="formPass" @submit.prevent="savePassword" lazy-validation>
     <v-card-title class="text-h5"> Change Password </v-card-title>
     <v-card-text class="mt-3 mb-3">
      Password is a confidential data. Please dont share it with anyone else.
      <v-text-field
       type="password"
       append-icon="mdi-key"
       class="mt-3"
       hide-details="auto"
       v-model="password_data.current_password"
       :rules="required"
       label="Current Password"
       required
       outlined
       dense
      ></v-text-field>
      <v-text-field
       type="password"
       append-icon="mdi-key-variant"
       class="mt-3"
       hide-details="auto"
       v-model="password_data.new_password"
       :rules="required"
       label="New Password"
       required
       outlined
       dense
      ></v-text-field>
      <v-text-field
       type="password"
       append-icon="mdi-key-variant"
       class="mt-3"
       hide-details="auto"
       v-model="password_data.confirm_password"
       :rules="[matchingPasswords]"
       label="Confirm Password"
       required
       outlined
       dense
      ></v-text-field>
     </v-card-text>

     <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn color="grey darken-2" text @click="dialogPassword = false"> Cancel </v-btn>
      <v-btn color="green darken-1" text type="submit"> Confirm </v-btn>
     </v-card-actions>
    </v-form>
   </v-card>
  </v-dialog>
 </div>
</template>
<script>
 import { mapState } from 'vuex';
 import API from '../../store/base';

 export default {
  data: () => ({
   emailRules: [(v) => !!v || 'E-mail is required', (v) => /.+@.+\..+/.test(v) || 'E-mail must be valid'],
   required: [(v) => !!v || 'Field is required'],
   minChar: [(v) => v.length >= 10 || 'Input should be atleast 10 characters'],

   dialogPassword: false,
   gender: [
    {
     text: 'Male',
     value: 'Male',
    },
    {
     text: 'Female',
     value: 'Female',
    },
   ],
   activePicker: null,
   menu: false,
   valid: true,
   dialog: false,
   data: {
    first_name: '',
    middle_name: '',
    last_name: '',
    gender: '',
    contact_number: '',
    address: '',
    birthday: null,
    email: '',
    password: '************',
    current_password: '',
   },
   password_data: {
    current_password: '',
    new_password: '',
    confirm_password: '',
   },

   dialogRemove: false,
   isLoading: false,
   maxBirthDate: '',
  }),
  mounted() {
   this.maxDate();
   document.title = 'Account Settings';
  },
  computed: {
   ...mapState('auth', ['user']),
  },
  methods: {
   matchingPasswords: function() {
      if (this.password_data.new_password === this.password_data.confirm_password) {
        return true;
      } else {
        return 'Passwords does not match.';
      }
    },
   async removeImage() {
    this.isLoading = true;
    const { status, data } = await this.$store.dispatch('auth/removeImage');
    if (status == 200) {
     const alert = {
      msg: data.msg,
      isVisible: true,
      alertType: 'success',
     };
     this.$store.commit('alert/setAlert', alert);
     await this.$store.dispatch('auth/checkUser');
    } else {
     const alert = {
      msg: 'Something went wrong',
      isVisible: true,
      alertType: 'error',
     };
     this.$store.commit('alert/setAlert', alert);
    }
    this.dialogRemove = false;
    this.isLoading = false;
   },
   maxDate() {
    const date = new Date();
    const newDate = (date.getFullYear() - 4).toString() + '-01-01';
    this.maxBirthDate = newDate;
   },
   save(date) {
    this.$refs.menu.save(date);
   },
   async update() {
    this.valid = this.$refs.form.validate();

    if (this.valid) {
     const { status, data } = await this.$store.dispatch('auth/update', this.data);
     if (status == 200) {
      const alert = {
       msg: data.msg,
       isVisible: true,
       alertType: 'success',
      };
      this.$store.commit('alert/setAlert', alert);
      await this.$store.dispatch('auth/checkUser');
     } else {
      const alert = {
       msg: data,
       isVisible: true,
       alertType: 'error',
      };
      console.log(data);
      this.$store.commit('alert/setAlert', alert);
     }
     this.dialog = false;
     this.data.current_password = null;
    }
   },
   async savePassword() {
    const valid = this.$refs.formPass.validate();
    if (valid) {
     const { status, data } = await this.$store.dispatch('auth/changePassword', this.password_data);
     if (status == 200) {
      const alert = {
       msg: data.msg,
       isVisible: true,
       alertType: 'success',
      };

      this.$refs.formPass.reset();
      this.$store.commit('alert/setAlert', alert);
     } else {
      const alert = {
       msg: data,
       isVisible: true,
       alertType: 'error',
      };

      this.$refs.formPass.reset();
      this.$store.commit('alert/setAlert', alert);
     }
    }
    this.dialogPassword = false
    this.$refs.formPass.resetValidation();

   },
   async uploadProfileImage(event) {
    let formData = new FormData();
    formData.append('img', event.target.files[0]);
    await API.post(`upload-profile`, formData, {
     headers: {
      'Content-Type': 'multipart/form-data',
     },
    })
     .then((response) => {
      const alert = {
       msg: 'Profile image updated successfully!',
       isVisible: true,
       alertType: 'success',
      };

      this.$store.commit('alert/setAlert', alert);
     })
     .catch((error) => {
      console.log({ error });
     });
    await this.$store.dispatch('auth/checkUser');
   },
  },
  watch: {
   menu(val) {
    val && setTimeout(() => (this.activePicker = 'YEAR'));
   },
   user() {
    this.data.first_name = this.user.info.first_name;
    this.data.middle_name = this.user.info.middle_name;
    this.data.last_name = this.user.info.last_name;
    this.data.address = this.user.info.address;
    this.data.contact_number = this.user.info.contact_number;
    this.data.gender = this.user.info.gender;
    this.data.birthday = this.user.info.birthday;
    this.data.email = this.user.email;
   },
  },

 };
</script>
