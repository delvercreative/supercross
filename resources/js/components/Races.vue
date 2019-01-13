<template>
  <div id="eventList">
    <div class="event-item" v-for="event in events" :key="event.id">
        <div class="gray-card card text-center" v-if="event.status === sStatus">
        <div class="card-body">
          
            <div class="date-container">
              <span class="txt-lg date month">{{moment(event.date).format('MMM')}}</span><span class="txt-lg date day">{{moment(event.date).format('DD')}}</span>
            </div>
              <h5 v-if="event.round > 0" class="event-round">{{event.name}}</h5>
              <h5 v-if="event.round === 0" class="card-title">Monster Energy Cup</h5>
              <p class="card-text">{{moment(event.date).format('MMMM Do, YYYY')}}</p>
          
          </div>
        <div class="card-footer">
          <button type="button" class="btn view-more btn-blue" @click="toggleDropdown(event.round)">View Races</button>
          <div class="races-dropdown" :id="dropdown + event.round">
            <ul class="race-list list-group">
              <li class="flex list-group-item" :id="itemId + race.id" v-for="race in event.races" :key="race.id">
                <div class="race-info">
                  
                    <img :src="race.class.image" alt="" class="race-logo">
                
                  <div class="race-name">{{race.name}}</div>
                </div>
                <button v-if="renderUserRaces(race.id) === false" @click="showPopUp(race.id,race.wager.value,race.name,event.date,event.name)" type="button" class="btn btn-green">Enter Now</button>
                <div v-else class="already-entered">
                  <a :href="racelink + race.id" class="btn btn-blue">View</a>
                </div>
              </li>
            </ul>
          </div> 
        </div>
      </div>
    </div>

  <!-- POP UP MESSAGE -->
  <div v-if="showPopup === true" id="confirmPopUp">   
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">{{popup.race}}</h5>
        <div v-if="!isEntered && !loading.addUser" class="card-inner-group">
          <p class="card-text"><span class="label">Event:</span> {{popup.location}}</p>
          <p class="card-text"><span class="label">Date:</span> {{popup.date}}</p>
          <p class="card-text entry-fee"><span class="label">Entry Fee:</span> ${{popup.entryfee}}</p>
           <div class="btn-row">
             <button @click="addUserToRace(popup.racekey, popup.fee)" type="button" class="btn btn-success">Confirm</button>
            <div>
            <button @click="closePopUp" type="button" class="btn btn-cancel">Cancel</button>
          </div>
        </div>
      </div>

      <div v-if="!isEntered && loading.addUser" class="card-inner-group">
        <div class="lds-dual-ring"></div>
      </div>

      <div v-if="isEntered && !loading.addUser" class="card-inner-group">
        <p class="card-text txt-success">You've beem entered successfully!</p>
      </div>
      </div>
      
    </div>
  </div>

  <div v-if="showDepositPopup" id="needDeposit">
    <div class="card text-center">
      <div class="card-body">
        <div class="card-title">Not enough funds to enter!</div>
        <p class="card-text strong">Please make a deposit to enter this race</p>
        <a href="#" class="btn btn-danger">Make Deposit</a>
         <button @click="closeDepositPopup" type="button" class="btn btn-close">Close</button>
      </div>
    </div>
  </div>

</div>
  </template>
  
  <!-- <template>
  <div id="eventList">
    <div class="card" v-for="event in events" :key="event.id">
      <h6 class="card-header dark-bg text-center">{{event.name}}</h6>
      <div class="card-body">
        <div class="event-list-inner flex flex-row">
          <div class="column date-container">
            <span class="txt-lg date month">{{moment(event.date).format('MMM')}}</span><span class="txt-lg date day">{{moment(event.date).format('DD')}}</span>
          </div>
          <div class="column text">
            <h5 class="card-title">Special title treatment</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <a href="#" class="btn view-more">View Races</a>
      </div>
    </div>
 
  </div>
</template> -->


<script>
let moment = require('moment')
let axios = require('axios')
import ConfirmButton from './ConfirmButton.vue'
let parseString = require('xml2js').parseString
export default {
  name: 'races',
  components: {
    ConfirmButton: ConfirmButton
  },
  props: ['user'],
  data() {
    return {
      sStatus: '1',
      showDepositPopup: false,
      raceLink: '/public/races/',
      events: '',
      moment: moment,
      mec: 'Monster Energy Cup is Back!',
      dropdown: 'raceDropdown',
      dropdownOpen: '',
      itemId: 'raceItem',
      userRaces: '',
      userRacesId: [],
      userBalance: '',
      showPopup: false,
      toConfirm: '',
      isEntered: false,
      loading: {
        addUser: false,
      },
      popup : {
        race: '',
        racekey: '',
        entryfee: '',
        date: '',
        location: ''
      }
    }
  },
  created() {
    this.fetchEvents();
    this.fetchAmaResults();
    this.fetchAmaDataDev();
    this.fetchAmaAnnouncements();
    this.checkUser();
  },
  methods: {

    verifyBalance(){

    },
    
    fetchEvents(){
      fetch('api/events')
        .then(res => res.json())
          .then(res => {
            this.events = res.data;
            console.log(res.data);
          });
   
    },
    addUserToRace(id){
      this.loading.addUser = true
      axios.post('api/race/adduser', {
          race_id: id,
          user_id: this.user
      }) .then(res => {
        this.userRacesId.push(id)
        this.loading.addUser = false
        this.isEntered = true
        setTimeout(this.closePopUp, 2000)
        this.checkUser();
        console.log(this.userBalance)
        console.log(this.userRacesId)
      })
      .catch(err => {
        console.log(err)
      })
    },
    checkUser(){
      axios.get('api/race/checkuser', {
        params: {
          user_id: this.user
        }
      }).then(res => res.data)
      .then(res => {
        this.userRaces = res.data.races
        this.userBalance = res.data.balance;
        this.userRaces.forEach(user => {
        this.userRacesId.push(user.id)
        })
         this.$root.$emit('balance', this.userBalance);
        console.log(res.data.balance)
      })
      .catch(err => {
        console.log(err)
      })
    },
    convertInt(int, status){
      return parseInt(int, 10)
    },
    checkBalance(){
       axios.get('api/race/checkuser', {
        params: {
          user_id: this.user
        }
      }).then(res => res.data)
      .then(res => {
        this.userBalance = res.data.balance
        console.log(res.data.balance)
      })
      .catch(err => {
        console.log(err)
      })
    },
    renderUserRaces(race_id) {
      return this.userRacesId.includes(race_id);
    },
    fetchAmaResults(){
      fetch('https://live.amasupercross.com/xml/dev/sx/RaceResults.json')
        .then(res => res.json())
        .then(res => {
          console.log(res);
        })
    },
    fetchAmaResultsDev(){
      fetch('https://live.amasupercross.com/xml/sx/RaceResults.json')
        .then(res => res.json())
        .then(res => {
          console.log(res);
        })
    },
    fetchAmaData(){
      fetch('https://live.amasupercross.com/xml/sx/RaceData.json')
        .then(res => res.json())
        .then(res => {
          console.log(res);
        })
    },
    fetchAmaDataDev(){
      fetch('https://live.amasupercross.com/xml/dev/sx/RaceData.json')
        .then(res => res.json())
        .then(res => {
          console.log(res);
        })
    },
    fetchAmaAnnouncements(){
      fetch('https://live.amasupercross.com/xml/sx/Announcements.json')
        .then(res => res.json())
        .then(res => {
          console.log(res);
        })
    },
    fetchAmaAnnouncementsDev(){
      fetch('https://live.amasupercross.com/xml/dev/sx/Announcements.json')
        .then(res => res.json())
        .then(res => {
          console.log(res);
        })
    },
    toggleDropdown(round) {
      let selection = document.querySelector('#raceDropdown'+ round)
      selection.classList.toggle('dropdown-open')
    },
    showPopUp(id,fee,race,date,location) {
      if(this.userBalance >= fee){
        this.showPopup = true
        this.popup.racekey = id
        this.popup.entryfee = fee
        this.popup.race = race
        this.popup.date = date
        this.popup.location = location
      } else [
        this.showDepositPopup = true
      ]
      
    },
    closePopUp() {
      this.showPopup = false
      this.popup.racekey = ''
      this.popup.entryfee = ''
      this.popup.race = ''
      this.popup.date = ''
      this.popup.location = ''
      this.isEntered = false
    },
    closeDepositPopup(){
      this.showDepositPopup = false
    }
  },
  computed: {
    dateFormat(date){
      return this.moment(date).format('MMMM Do YYYY')
    },
    hasUser(){
      return this.userRacesId.includes(this)
    }
    
  },
}
</script>

<style>
.races-dropdown {
  max-height: 0;
  overflow: hidden;
}
.dropdown-open {
  max-height: 100%;
}
</style>

