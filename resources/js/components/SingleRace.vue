<template>
  <div id="singleRace">
    <race-header v-if="!pageLoading" :status="raceStatus" :status-class="statusClass" :image="raceClass.image" :count="userCount" :date="dateFormat" :location="event.name" :title="raceDetails.name"></race-header>
    <div class="race-results">
      <live-timing @send="getLeaderboard" :raceid="raceid" :init_status="currentStatus"></live-timing>
    </div>
    <my-riders :user="user"></my-riders>
  </div>
  </template>
  



<script>
let moment = require('moment')
let axios = require('axios')
import RaceHeader from './RaceHeader.vue'
import MyRiders from './MyRiders.vue'
import LiveTiming from './LiveTiming.vue'
export default {
  name: 'single-race',
  props: ['raceid', 'user', 'status'],
  components: {
    RaceHeader: RaceHeader,
    MyRiders: MyRiders,
    LiveTiming: LiveTiming
  },
  data() {
    return {
      pageLoading: true,
      selectionComplete: false,
      currentDate: '',
      raceClass: '',
      raceClassName: '',
      riders: '',
      raceDetails: '',
      event: '',
      moment: moment,
      users: '',
      statusClass: '',
      amaResults: {
        amaClass: '',
        raceType: ''
      },
      myRider: '',
      selectedRiders: [],
      timerOn: false,
      test: [],
      finishedSelect: [],
      liveRacerData: [],
      addSelectionsData: [],
      initStatus: this.status,
      pointsList: []
    }
  },
  created() {
    this.fetchRace();
  },
  mounted() {
   
    Echo.channel('race-status')
    .listen('BalanceChanged', (e) => {
        this.initStatus = e.race.status
        // console.log(e.race.status)
    });
  },
  methods: {
    getLeaderboard(leaders){
      // console.log('got leaders')
    },
    
    
    fetchRace(){
      axios.get('https://ninetwentygear.com/api/race', {
        params: {
          race_id: this.raceid
        }
      })
      .then(res => res.data)
      .then(res => {
        let s = parseInt(res.data.status,10)
        this.pointsList = res.data.points
        this.prizeList = res.data.winnings
        this.users = res.data.users
        this.raceDetails = res.data
        this.event = res.data.event
        this.raceClass = res.data.class
        if(res.data.class.name === "250"){
          this.raceClassName = 'S2'
        }
        if(res.data.class.name === "450") {
          this.raceClassName = 'S1'
        }
        this.pageLoading = false
      })
      .then(() => {
        this.fetchAmaResults()
        this.amaInterval = setInterval(this.fetchAmaResults, 3000)
      })
      // console.log(this.initStatus)
      
    },
    userRiderSelection(){
      
      this.users.forEach(user => {
        Array(this.calcCount).fill(user).forEach(t => {
          const selectionItem = {
            user: t.display_name,
            userid: t.id,
            rider: this.generateRider()
          }
          this.finishedSelect.push(selectionItem)
        })
  
      })
      this.finishedSelect.forEach(r => {
        // let.iPos = r.rider.position
        // let cPos = iPos.toString()
        const addSelection = {
          user_name: r.user,
          current_pos: r.rider.position,
          user_id: r.userid,
          race_id: this.raceid,
          rider_number: r.rider.number,
          rider_brand: r.rider.brand,
          rider_name: r.rider.name,
          start_pos: r.rider.position,
        }
        this.pointsList.forEach(point => {
          let rPos = parseInt(r.rider.position, 10)
          if(rPos === point.id){
            addSelection.points = point.value
            // console.log(point.value)
          }
        })
        this.addSelectionsData.push(addSelection) 
      })
      setTimeout(this.addUsersSelections, 1000)
    },
    addUsersSelections() {
      const postSelectionData = {
        data: this.addSelectionsData,
        race: this.raceid
      }
      axios.post('/api/newselection', postSelectionData)
      .then(res => {
        // console.log(res)
        this.selectionComplete = true
      })
      .catch(err => {
        console.log(err)
      })
    },
    
    fetchAmaResults(){
      fetch('https://live.amasupercross.com/xml/sx/RaceResults.json')
        .then(res => res.json())
        .then(res => {
          
          this.amaResults.raceType = res.A
          this.amaResults.amaClass = res.B[0].C
          if(this.checkRaceType && this.currentStatus === 0 && this.selectionComplete === false){
            this.riders = res.B
            clearInterval(this.amaInterval)
            this.userRiderSelection()
            // this.selectionInterval = setInterval(this.userRiderSelection, 3000)
            // this.resultsInterval = setInterval(this.liveResults, 6000)
          } else {
            if(this.currentStatus === 1) {
              console.log('Race has started')
            } else {
              console.log('Not correct race')
            }
          }
          
        })
    },
    fetchAmaData(){
      fetch('https://live.amasupercross.com/xml/sx/RaceData.json')
        .then(res => res.json())
        .then(res => {
          console.log('fetching ama data for status')
          console.log(res)
        })
    },
    generateRider(user) {
      let randomItem = this.riders[Math.floor(Math.random()*this.riders.length)]
      this.myRider = randomItem.F
      let myR = randomItem.F
      let index = this.riders.indexOf(randomItem)
        if (index > -1) {
          this.riders.splice(index, 1);
        }
        
       this.selectedRiders.push(this.myRider);
       let riderDetails = {
         number: randomItem.N,
         name: randomItem.F,
         position: randomItem.A,
         brand: randomItem.V
       }
       return riderDetails;
    },
    
  },
  computed: {
    currentStatus(){
      return parseInt(this.initStatus, 10)
    },
    
    checkRaceType(){
      if(this.amaResults.amaClass === this.raceClassName && this.amaResults.raceType === 3 ){
        return true
      } else {
        return false
      }
    },
    calcCount(){
      if(this.userCount === 1 || this.userCount === 2 || this.userCount === 3){
        return 6;
      }
      if(this.userCount === 4 || this.userCount === 5){
        return 4;
      }
      if(this.userCount === 6 || this.userCount === 7){
        return 3;
      }
      if(this.userCount === 8 || this.userCount === 9 || this.userCount === 10){
        return 2;
      }
    },
    dateFormat(){
      return this.moment(this.event.date).format('MMMM Do, YYYY')
    },
    userCount(){
      return this.users.length;
    },
    raceStatus(){
      if(this.currentStatus === 0){
        this.statusClass = 'status-0'
        return 'Race has not started'
      } 
      if(this.currentStatus === 1) {
        this.selectionComplete = true
        this.statusClass = 'status-1'
        return 'Race in-progress'
      }
      if(this.currentStatus === 2) {
        this.selectionComplete = true
        this.statusClass = 'status-3'
        return 'Race is under caution'
      }
      if(this.currentStatus === 3) {
        this.selectionComplete = true
        this.statusClass = 'status-3'
        return 'Race has been red flagged'
      }
      if(this.currentStatus === 4) {
        this.selectionComplete = true
        this.statusClass = 'status-4'
        return 'Race is complete'
      }
    }
    
  }
}
</script>
