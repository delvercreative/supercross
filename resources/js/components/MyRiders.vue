<template>
  <div id="myRiders">
    <div class="my-riders-header">
      <div class="header-title">My Riders</div>
      <div class="header-right">
        <div @click="toggleMyRiders" id="toggleRiders"><i class="fas fa-chevron-up"></i></div>
      </div>
    </div>
    <!-- <div class="riders-list-inner flex">
      <div class="my-rider-item" v-for="s in mySelections" :key="s.id">
        <div class="rider-number-plate">
          <span class="rider-number">{{s.rider_number}}</span>
        </div>
      </div>
    </div> -->

    <div class="riders-list-inner flex" id="myRidersList">
      <div class="my-rider-item" v-for="t in mySelections" :key="t.id">
        <div class="rider-item-inner">
          <div class="rider-number-plate" :class="getBikeBrand(t.rider_brand)">
          <span class="rider-number">{{t.rider_number}}</span>
          </div>
          <div class="items-list rider-name">{{renderRiderFirstName(t.rider_name)}} {{renderRiderLastName(t.rider_name)}}</div>
          <div class="items-list rider-position">
            <span class="head">Position:</span>
            <span class="position-value">{{t.current_pos}}{{renderCurrentPos(t.current_pos)}}</span>
          </div>
          <div class="items-list rider-points-details">
            <div class="points-block">
              <span class="sm-title">Points</span>
              <span class="points-value">{{t.points}}</span>
            </div>
            <div class="points-block">
              <span class="sm-title">Last</span>
              <span class="lap-value">{{t.last_lap}}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: 'my-riders',
  props: ['user'],
  data(){
    return {
      allLeaders: '',
      allSelections: '',
      mySelections: '',
      userId: this.user,
      showClass: 'hide',
      testRiders: [
        {
          id: 1,
          rider_name: 'Marvin Musquin',
          rider_number: '25',
          current_pos: '1',
          points: 23,
          last_lap: "1:04:605",
          rider_brand: 'KTM 450SX'
        },
        {
          id: 2,
          rider_name: 'Eli Tomac',
          rider_number: '3',
          current_pos: '21',
          points: 19,
          last_lap: "1:04:605",
          rider_brand: 'Kawasaki 450F'
        },
        {
          id: 3,
          rider_name: 'Eli Tomac',
          rider_number: '3',
          current_pos: '21',
          points: 19,
          last_lap: "1:04:605",
          rider_brand: 'Kawasaki 450F'
        }
      ]
    }
  },
  mounted(){
    this.$root.$on('wins', data => {
      console.log('got leaderboard!')
      this.allLeaders = data
        console.log(data)
    })
    this.$root.$on('selections', data => {
      console.log('got selections!')
      this.allSelections = data
      console.log(data)
      this.renderMyRiders()
    })
  },
  methods: {
    filterMyRiders(u){
      let aSelections = this.allSelections
      const mSelections = aSelections
      .filter(s => s.user_id == u)
      return mSelections
      console.log(mSelections)
    },
    renderMyRiders(){
      this.mySelections = this.filterMyRiders(this.userId)
    },
    toggleMyRiders() {
      let myR = document.querySelector('#myRidersList')
      myR.classList.toggle('show')
      let icon = document.querySelector('#toggleRiders')
      icon.classList.toggle('active')
    },
    getBikeBrand(brand) {
      if(brand) {
        let lower = brand.toLowerCase().substring(0,3)
        return lower
      } else {
        return 'no-brand'
      }
    },
    renderRiderFirstName(name){
      let fInital = name.substring(0,1)+'.'
      return fInital
    },
    renderRiderLastName(name){
      let calc = name.indexOf(' ')
      let len = name.length
      let lastName = name.substring(calc,len)
      return lastName
    },
    renderCurrentPos(pos){
      let rVal = ''
      switch(pos){
        default:
          rVal = 'th'
          break;
        case "1":
        case 1:
        case "21":
        case 21:
          rVal = 'st'
          break;
        case "2":
        case 2:
        case "22":
        case 22:
          rVal = 'nd'
          break;
        case "3":
        case 3:
        case "23":
        case 23:
          rVal = 'rd'
          break;
      }
      return rVal
    }
    // getLeaderboard(leaderboard){
    //   console.log('got leaderboard!')
    // }
  }

}
</script>
<style>
.test {
  color: #fff;
}
</style>
