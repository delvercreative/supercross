<template>
  <div class="page-title card gray-card">
    <div class="card-body">
      <img class="race-class-logo" :src="image" alt="class-logo">
      <h1>{{title}}</h1>
      <div class="flex race-sub-header">
        <div class="race-location">{{location}}</div>
        <span>|</span>
        <div class="race-date">{{date}}</div>
      </div>
      <div :class="statusClass" class="race-status">{{status}}</div>
      <div class="race-entry-qty"><span class="label">Number of Contestants: </span>{{count}}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'race-header',
  props: ['title', 'location', 'status', 'date', 'count', 'image', 'statusClass'],
  data(){
    return {
      watchStatus: this.status
    }
  },
  mounted() {
    Echo.channel('race-status')
    .listen('BalanceChanged', (e) => {
        this.watchStatus = e.race.status
        console.log(e.race.status)
    });
  },
}
</script>
