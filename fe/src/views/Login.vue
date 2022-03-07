<template>
 <div class="bg-hero">
  <div class="top-nav">
   <div>
    <p class="logo">BRAND NAME</p>
   </div>
   <router-link to="/signup">Sign Up</router-link>
  </div>
  <v-container class="vh-100">
   <v-row justify="center" align-content="center" class="h-100 mt-4">
    <v-col cols="10" sm="10" md="11" lg="12">
     <p class="white--text mt-6">JOIN FOR FREE</p>
     <p class="white--text font-poppins text-h3">Welcome back, User!</p>
     <p class="grey--text text--lighten-1">CLIENT • DEVELOPERS • USERS</p>
     <v-row >
       <v-col xl="5" lg="5" md="6" sm="8" cols="10">
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-text-field append-icon="mdi-email" class="mt-3" hide-details="auto" v-model="data.email" :rules="emailRules" label="Email Address" required dark outlined filled dense></v-text-field>
          <v-text-field append-icon="mdi-key" type="password" @keypress.enter="login" class="mt-3" hide-details="auto" v-model="data.password" :rules="required" label="Password" required dark filled outlined dense></v-text-field>
          <p class="grey--text text--lighten-1 mt-4">
            Don't have an account? <router-link class="text-decoration-none" to="/signup">Sign-up</router-link>
          </p>
          <v-btn @click.prevent="login" class=" mt-4" depressed color="success" :loading="isLoading">Login Account</v-btn>
          <!-- <v-btn :disabled="!valid" color="success" class="mr-4" @click="validate"> Validate </v-btn>
          <v-btn color="error" class="mr-4" @click="reset"> Reset Form </v-btn>
          <v-btn color="warning" @click="resetValidation"> Reset Validation </v-btn> -->
        </v-form>
       </v-col>
     </v-row>
    </v-col>
   </v-row>
  </v-container>
 </div>
</template>
<script>
export default {
  name: 'Login',
  mounted() {
   document.title = 'Welcome, Please Log-in your account';
  },
  data: () => ({
    valid: true,
    data: {
      firstname: '',
      lastname: '',
      email: '',
      password: '',
    },
    emailRules: [
      v => !!v || 'E-mail is required',
      v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
    ],
    required: [
      v => !!v || 'Field is required'
    ],
    minChar: [
      v => v.legth >= 10 || 'Input should be atleast 10 characters'
    ],
    isLoading: false
  }),
  methods: {
    async login(){
      this.isLoading = true
      const {status, data} = await this.$store.dispatch('auth/login', this.data)
      if(status == 200) {
        const alert = {
          msg: `Welcome back, ${data.user_info.first_name[0]}. ${data.user_info.last_name}!`,
          isVisible: true,
          alertType: 'success'
        }

        this.$refs.form.reset()
        this.$store.commit('alert/setAlert', alert)
        this.$router.push('/user/dashboard')
      }
      else {
        const alert = {
          msg: 'Credentials are incorrect',
          isVisible: true,
          alertType: 'error'
        }
        this.$refs.form.reset()
        this.$store.commit('alert/setAlert', alert)
      }
      this.isLoading = false
    }
  }
 };
</script>
