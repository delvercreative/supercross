<template>
  <div id="nextRaceHero">
    <div class="page-title card gray-card">
     <img class="race-class-logo" :src="logo" alt="class-logo">
    <div class="card-body">
      <h1 style="font-size: 2rem;">{{nRace.name}}</h1>
      <div class="race-location">{{nRace.location}}</div>
      <div class="race-date">{{nEvent.name}}</div>
      <div class="race-entry-qty"><span class="label">Number of Contestants: </span>{{nRace.count}}</div>
    </div>
  </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'next-race',
  props: ['race', 'logo', 'event'],
  data() {
    return {

    }
  },
  created(){
    this.nextRace();
    console.log(this.nRace)
  },
  methods: {
    nextRace() {
      // axios.get('api/nextrace')
      // .then(res => (
      //   console.log(res)
      // ))

      console.log(this.race)
    }
  },
  computed: {
    nRace(){
      // let r = JSON.parse(this.race)
      return JSON.parse(this.race)
      
    },
     nEvent(){
      // let r = JSON.parse(this.race)
      return JSON.parse(this.event)
      
    }
  }
}
</script>
