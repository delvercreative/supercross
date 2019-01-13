<template>
<div id="checkStatus">
  <div class="card">
    <div class="card-body text-center">
      <h5>Check race results</h5>
      <button @click="checkResultsPosting" type="button" class="btn btn-blue">Check</button>
      
    </div>
  </div>
  <div v-if="isLoading" class="card-inner-group text-center mt-4">
        <div class="lds-dual-ring"></div>
      </div>
      <!-- <div v-if="resultsStatus" class="alert alert-success">{{resultsStatus}}</div> -->
      
      <div v-if="resultsStatus" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          
          <strong class="mr-auto">Race status</strong>
          <small>Now</small>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span @click="closeMessage" aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body">
          {{resultsStatus}}
        </div>
      </div>
  <user-selection :rcount="riderCount" :show="show" :users="users" :raceid="raceid"></user-selection>
</div>
  
</template>
<script>
import UserSelection from '../UserSelection.vue'
let axios = require('axios')
export default {
  name: 'edit-events',
  components: {UserSelection: UserSelection},
  props: ['riderCount', 'users', 'raceid'],
  data() {
    return {
      results: '',
      final250: [],
      final450: [],
      show: '',
      isLoading: false,
      eventName: 'S1905',
      resultsStatus: ''
    }
  },
  created() {
    
  },
  methods: {
    checkResultsPosting(){
      this.isLoading = true;
      axios.get('results/status', {
        params: {
          e: this.eventName
        }
      }).then(res => {
        this.results =  res.data;
        console.log(res.data)
      }).then(() => {
        
        this.results.forEach(r => {
          let type = r.type[0];
          if(type === "F"){
            if(r.class_name[0][0]=== "250SX"){
              this.final250.push(r.data)
              this.final250.forEach(final => {
                if(final[0][0] === "Starting Lineup"){
                  this.isLoading = false
                  this.show = "250"
                  this.resultsStatus = '250 main event is ready!'
                  console.log('250 starting lineup posted');
                }   
              })
            }
            if(r.class_name[0][0] === "450SX"){
              this.final450.push(r.data)
              this.final450.forEach(final => {
              if(final[0][0] === "Starting Lineup"){
                this.isLoading = false
                this.show = "250"
                console.log('450 starting lineup posted');
                this.resultsStatus = '450 main event is ready!'
              }
              })
            }
          } else {
    
            
          }
         
        })
      })
    },
    closeMessage() {
      this.resultsStatus = null
    }
  },
  computed: {
     
  }
}
</script>
<style>
.toast {
  background: #cecece;
  padding: 10px;
  text-align: center;
  margin-top: 15px;
}
</style>

