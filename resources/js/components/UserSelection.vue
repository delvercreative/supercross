<template>
  <div id="raceSelectionGenerator">
    <ul v-if="!selectionsMade" class="list-group">
      <li class="list-group-item">
        <h5>250 Main Starting Lineup Posted!</h5>
   
        <button @click="startGen" type="button" class="btn btn-success">Generate Riders</button>
      </li>
      <li v-if="show === class450" class="list-group-item">
        <h5>450 Main Starting Lineup Posted!</h5>
        <button type="button" class="btn btn-success">Generate Riders</button>
      </li>
    </ul>
    <ul v-if="selectionsMade" class="list-group">
      <li class="list-group-item">{{selectionsMade}}</li>
    </ul>
  </div>
  </template>
  



<script>
let moment = require('moment')
let axios = require('axios')
export default {
  name: 'user-selection',
  props: ['raceid', 'users', 'show', 'rcount'],
  data() {
    return {
      selectionComplete: false,
      selectionsMade: '',
      riders: '',
      moment: moment,
      myRider: '',
      selectedRiders: [],
      finishedSelect: [],
      addSelectionsData: [],
      class450: '450',
      class250: '250'
    }
  },
  created() {
    
  },
  methods: {
    startGen(){
      fetch('https://live.amasupercross.com/xml/sx/RaceResults.json')
        .then(res => res.json())
        .then(res => {
            this.riders = res.B
        }).then(() => this.userRiderSelection())  
    },

    generateRider() {
      let randomItem = this.riders[Math.floor(Math.random()*this.riders.length)]
      this.myRider = randomItem.F
      let index = this.riders.indexOf(randomItem)
        if (index > -1) {
          this.riders.splice(index, 1)
        }
        
      //  this.selectedRiders.push(this.myRider);
       let riderDetails = {
         number: randomItem.N,
         name: randomItem.F,
         position: randomItem.A,
         brand: randomItem.V
       }
       return riderDetails;
    },

    userRiderSelection(){
      
      this.nUsers.forEach(user => {
        Array(this.calcCount).fill(user).forEach(t => {
          const selectionItem = {
            user: t.name,
            userid: t.id,
            rider: this.generateRider()
          }
          this.finishedSelect.push(selectionItem)
        })
        console.log(this.finishedSelect)
  
      })
      this.finishedSelect.forEach(r => {
        const addSelection = {
          user_name: r.user,
          current_pos: r.rider.position,
          user_id: r.userid,
          race_id: this.raceid,
          rider_number: r.rider.number,
          rider_brand: r.rider.brand,
          rider_name: r.rider.name,
          start_pos: r.rider.position
        }
        this.addSelectionsData.push(addSelection) 
      })
      setTimeout(this.addUsersSelections, 1000)
    },

    addUsersSelections() {
      const postSelectionData = {
        data: this.addSelectionsData,
        race: this.raceid
      }
      axios.post('/supercross/public/api/newselection', postSelectionData)
      .then(res => {
        this.selectionsMade = res.data
        console.log(res)
        this.selectionComplete = true
        this.initStatus = 1
      })
      .catch(err => {
        console.log(err)
      })
    },
  },

  computed: {

    countInt(){
     return parseInt(this.rcount, 10);
    },

   
     calcCount(){

      if(this.countInt === 1 || this.countInt === 2 || this.countInt === 3){
        return 6;
      }
      if(this.countInt === 4 || this.countInt === 5){
        return 4;
      }
      if(this.countInt === 6 || this.countInt === 7){
        return 3;
      }
      if(this.countInt === 8 || this.countInt === 9 || this.countInt === 10){
        return 2;
      }
    },
    
    
    
    nUsers(){
      // let r = JSON.parse(this.race)
      return JSON.parse(this.users)
      
    },
    
    
    
  }
}
</script>