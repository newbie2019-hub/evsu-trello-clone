<template>
 <div class="bg-hero">
  <div class="top-nav">
   <div>
    <p class="logo">BRAND NAME</p>
   </div>
   <router-link to="/">Sign In</router-link>
  </div>
  <v-container class="vh-100">
   <v-row justify="center" align-content="center" class="h-100">
    <v-col cols="10" sm="10" md="11" lg="12">
     <!-- <p class="white--text mt-6">JOIN FOR FREE</p> -->
     <p class="white--text font-poppins text-h3 mt-6">Enter your Credentials<span class="blue--text fs-2x">.</span></p>
     <p class="grey--text text--lighten-1 w-50 w-md-80">Simple, yet powerful project management to help you plan, collaborate, organize and deliver projects of all sizes</p>
     <v-row class="m">
       <v-col xl="5" lg="5" md="6" sm="8" cols="10">
        <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="signup">
          <v-row>
            <v-col>
              <v-text-field append-icon="mdi-card-bulleted" class="mt-3" hide-details="auto" v-model="data.first_name" :rules="required" label="First Name" required dark outlined filled dense></v-text-field>
            </v-col>
            <v-col>
              <v-text-field append-icon="mdi-card-bulleted" class="mt-3" hide-details="auto" v-model="data.last_name" :rules="required" label="Last Name" required dark outlined filled dense></v-text-field>
            </v-col>
          </v-row>
          <v-text-field append-icon="mdi-email" class="mt-3" hide-details="auto" v-model="data.email" :rules="emailRules" label="Email Address" required dark outlined filled dense></v-text-field>
          <v-text-field append-icon="mdi-key" type="password" class="mt-3" hide-details="auto" v-model="data.password" :rules="minChar" label="Password" required dark filled outlined dense></v-text-field>
          <p class="grey--text text--lighten-1 mt-5">
           Already A Member? <router-link class="text-decoration-none" to="/">Login</router-link>
          </p>
          <v-btn type="submit" class="mt-4" depressed color="success" :loading="isLoading">Create account</v-btn>
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
import Alert from './components/Alert.vue'

export default {
  name: 'Signup',
  mounted() {
   document.title = 'Welcome, Please Log-in your account';
  },
  data: () => ({
    valid: true,
    data: {
      first_name: '',
      last_name: '',
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
      v => v.length >= 10 || 'Input should be atleast 10 characters'
    ],
    isLoading: false,
  }),
  methods: {
    async signup(){
      this.valid = this.$refs.form.validate()

      if(this.valid){
        this.isLoading = true
        const {status, data} = await this.$store.dispatch('auth/signup', this.data)
        if(status == 200) {
          const alert = {
            msg: data.msg,
            isVisible: true,
            alertType: 'success'
          }
        
          this.$refs.form.reset()
          this.$store.commit('alert/setAlert', alert)
        }
        else {
          const alert = {
            msg: data,
            isVisible: true,
            alertType: 'error'
          }

          this.$refs.form.reset()
          this.$store.commit('alert/setAlert', alert)
        }
        this.isLoading = false
      }
    }
  }
 };
</script>
