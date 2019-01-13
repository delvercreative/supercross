<template>
  <div id="liveTiming">
    <timeboard :time_remaining="timeRemaining" :flag_status="status"></timeboard>

    <div id="leaderBoard">
      <div class="card gray-card">
        <div v-for="user in userLeaderboard" :key="user.id" class="leader-item">
          <div class="inner" v-if="user.winnings != 0">
            <div class="user-icon">
            <i class="fas fa-user"></i>
            <div class="user-name">{{user.display_name}}</div>
          </div>
            <div class="points-details">
              <div class="user-points">
                <span class="sm-title">Points:</span><span class="points">{{user.totalPoints}}</span>
              </div>
              <div class="user-winnings">
                <span class="sm-title">Wins:</span><span class="winnings">${{winningsAmt(user.winnings)}}</span>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>





    <h4 class="mt-4 scoring-title">Live Timing and Scoring</h4>
    <div v-if="raceLoading" class="loading-container">
      <div class="lds-dual-ring"></div>
    </div>
    <table v-if="!raceLoading && !showResults" class="table table-striped table-dark">
      <thead>
        <tr>
          <th class="r-position" scope="col">Pos</th>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Pts</th>
          <th scope="col">User</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="racer in newResults" :key="racer.rider_number">
          <th class="r-position" scope="row">{{racer.current_pos}}</th>
          <td class="r-plate"><span class="number-plate" :class="getBikeBrand(racer.rider_brand)"><span class="racer-number">{{racer.rider_number}}</span></span></td>
          <td class="r-name">{{racer.rider_name}}</td>
          <td class="r-points">{{racer.points}}</td>
          <td class="r-user"><i v-if="racer.user != null" class="fas fa-user"></i> {{racer.user}}</td>
        </tr>
      </tbody>
    </table>

    <table v-if="!raceLoading && showResults" class="table table-striped table-dark">
      <thead>
        <tr>
          <th class="r-position" scope="col">Pos</th>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Pts</th>
          <th scope="col">User</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="racer in riderResults" :key="racer.rider_number">
          <th class="r-position" scope="row">{{racer.current_pos}}</th>
          <td class="r-plate"><span class="number-plate" :class="getBikeBrand(racer.rider_brand)"><span class="racer-number">{{racer.rider_number}}</span></span></td>
          <td class="r-name">{{racer.rider_name}}</td>
          <td class="r-points">{{racer.points}}</td>
          <td class="r-user"><i v-if="racer.user != null" class="fas fa-user"></i> {{racer.user}}</td>
        </tr>
      </tbody>
    </table>




  </div>
</template>

<script>
let axios = require('axios')
import Timeboard from './Timeboard.vue'
export default {
  name: 'live-timing',
  components: {Timeboard: Timeboard},
  props: {
    brand_class: String,
    init_status: Number,
    raceid: String
  },
  data(){
    return {
      status: parseInt(this.init_status,10),
      raceLoading: true,
      newResults: [],
      riderSelections: '',
      userSelections: '',
      flagStatus: '',
      timeRemaining: '00:00:00',
      riderResults: '',
      pointsList: '',
      prizeList: '',
      testTie: '',
      userLeaderboard: '',
      showResults: ''
      
    }
  },
  created(){
   this.loadInit()
  },
  
  mounted(){
    Echo.channel('race-status')
    .listen('BalanceChanged', (e) => {
        this.status = parseInt(e.race.status, 10)
        if(this.status === 4 || e.race.status === 4){
         clearInterval(this.runLive)
        }
    });
    this.checkStatus()
    if(this.status === 4){
      this.getResults()
    }
    
  },
  methods: {
    checkStatus(){
      setTimeout(() => {
        if(this.status === 1){
          this.showResults = false
          this.runLive = setInterval(this.liveResults, 8000)
        }
         if(this.status === 4){
           clearInterval(this.runLive)
        }
      }, 4000)
    },
    liveResults(){
      fetch('https://live.amasupercross.com/xml/sx/RaceResults.json')
        .then(res => res.json())
        .then(res => {
          if(this.status === 4 || this.init_status === 4) {
            clearInterval(this.runLive)
            this.getResults()
          } else {
            this.loadUserSelections(res.B)
            this.fetchAmaData()
          }
          
        })
    },
    loadInit(){
      axios.get('https://ninetwentygear.com/api/race', {
        params: {
          race_id: this.raceid
        }
      })
      .then(res => res.data)
      .then(res => {
        this.prizeList = res.data.winnings
        this.pointsList = res.data.points
        this.raceUsers = res.data.users
      })
    },
    loadUserSelections(amaRes){
      axios.get('https://ninetwentygear.com/api/race', {
        params: {
          race_id: this.raceid
        }
      })
      .then(res => res.data)
      .then(res => {
        this.userSelections = res.data.selections
      }).then(() => this.compileData(amaRes))
    },
    compileData(results){
      let cacheResults = []
      results.forEach(result => {
        let riderUser = ''
        let cPos = result.A
        const newResult = {
          rider_name: result.F,
          current_pos: cPos.toString(),
          rider_number: result.N,
          rider_brand: result.V,
        }
        this.userSelections.forEach(selection => {
          if(selection.rider_number === result.N){
            newResult.user = selection.user_name
            newResult.id = selection.id
            newResult.finish_pos = newResult.current_pos
            newResult.user_id = selection.user_id
          }
        })
        this.pointsList.forEach(point => {
            let rPos = this.convertPos(newResult.current_pos)
              if(rPos === point.id){
                newResult.points = parseInt(point.value, 10)
              } 
            })
        this.raceLoading = false
        cacheResults.push(newResult)
      })
      this.raceUsers.forEach(user => {
          user.totalPoints = this.totalUp(cacheResults,user.display_name)
          user.tiebreaker = this.tieBreak(cacheResults,user.display_name)
      })
      this.runTieBreak(this.raceUsers)
      this.userLeaderboard = this.sortPlace(this.raceUsers)

      this.compileEarnings()

      this.newResults = cacheResults

      this.sendUserData()
      this.sendUserWins()
    },
    runTieBreak(userPoints, ref){
      userPoints.forEach(p => {
        userPoints.forEach(pts => {
          if(p.display_name != pts.display_name){
            if(p.totalPoints === pts.totalPoints){
              let keyVal = this.compareTieBreak(userPoints, p.totalPoints)
                if(p.tiebreaker === keyVal){
                  p.totalPoints = parseInt(p.totalPoints, 10) + 1
                }
                console.log('Theres a tie!')
            }
          }
        })
      })
    },
    sendUserWins(){
      this.$root.$emit('wins', this.userLeaderboard)
      // this.$emit('send', this.userLeaderboard)
    },
    sendUserData(){
      this.$root.$emit('selections', this.newResults)
    },
    compileEarnings(){
      this.userLeaderboard.forEach((user,i) => {
        let pLength = this.prizeList.length - 1
        if(i <= pLength){
          user.winnings = this.generateLeaderboard(i)
        } else {
          user.winnings = 0
        }
      })

    },
    generateLeaderboard(i){
      let uPlace = i + 1
      const userPrizes = this.prizeList
      .filter((p) => p.place == uPlace)
      .map((p) => p.amount)
      return Math.max(...userPrizes)
    },
    compareTieBreak(users, total){
      const userTieBreakVal = users
      .filter((u) => u.totalPoints == total)
      .map((u) => u.tiebreaker)
      return Math.max(...userTieBreakVal)
    },
    tieBreak(ref,user){
      const refList = ref
      .filter((r) => r.user == user)
      .map((r) => r.points)
      return Math.max(...refList)
    },
    totalUp(users,user){
        const pointsCollect = users
        .filter((pc) => pc.user == user)
        .map((pc) => pc.points)
        .reduce((total, point ) => total + point, 0)
          return pointsCollect
    },
    sortPlace(users){
      const sortUserPlace = users
      .sort((a, b) => b.totalPoints - a.totalPoints)
      return sortUserPlace
    },
    getResults(){
      axios.get('https://ninetwentygear.com/api/race', {
        params: {
          race_id: this.raceid
        }
      })
      .then(res => res.data)
      .then(res => {
        this.raceLoading = false
        this.showResults = true
        this.riderResults = res.data.selections
      })
    },
    
    updateUserSelections(results) {
      // console.log(this.newResults)
      let toSave = []
      results.forEach(r => {
        if(r.id){
          toSave.push(r)
        }
      })
        const updateData = {
        data: toSave
      }
      axios.post('/api/updateselection', updateData)
      .then(res => {
        console.log(res)
      })
      .catch(err => {
        console.log(err)
      })
    },
    fetchAmaData(){
      fetch('https://live.amasupercross.com/xml/sx/RaceData.json')
        .then(res => res.json())
        .then(res => {
          // console.log(res)
          
          if(res.C === "0"){
            this.status = 4
          }
          if(res.C === "1"){
            this.status = 1
          }
          if(res.C === "2"){
            this.status = 2
          }
          if(res.C === "3"){
            this.status = 3
          } 
          this.timeRemaining = res.T
          if(this.status === 0){
            console.log('Not started')
            // this.updateRaceStatus(this.status)
          }
          if(this.status === 1){
            console.log('Race in progress')
            // this.updateRaceStatus(this.status)
          }
          if(this.status === 2){
            console.log('Race in caution')
            // this.updateRaceStatus(this.status)
          }
          if(this.status === 3){
            console.log('Race stopped: red flag')
            // this.updateRaceStatus(this.status)
          }
          if(this.status === 4){
            console.log('Race is complete')
            this.updateRaceStatus(this.status)
            this.updateUserSelections(this.newResults)
            clearInterval(this.runLive)
          }
        })
    },
    convertPos(p){
      return parseInt(p, 10)
    },
    getBikeBrand(brand) {
      if(brand) {
        let lower = brand.toLowerCase().substring(0,3)
        return lower
      } else {
        return 'no-brand'
      }
    },
    updateRaceStatus(s){
       const raceData = {
        status: s
      }
      axios.post('/api/race/status/'+this.raceid, raceData)
      .then(res => {
        if(s === 4){
          clearInterval(this.runLive)
        }
        console.log(res)
      })
      .catch(err => {
        console.log(err)
      })
    },
    winningsAmt(x){
      return x.toFixed(2)
    }
    
  },
  

}
</script>

