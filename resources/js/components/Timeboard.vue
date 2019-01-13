<template>
  <div id="timeBoard">
      <div class="time-remaining-container">
        <div class="time-board-title sm-txt">Time Remaining</div>
        <div id="timeRemaining">{{time_remaining}}</div>
      </div>
      <div class="flag-status-container">
        <div id="flagStatus" :class="flagClass"></div>
      </div>
    </div>
</template>

<script>
export default {
  name: 'timeboard',
  props: ['time_remaining', 'flag_status'],
  data() {
    return {
    }
  },
  computed: {
    flagClass() {
      let flag = this.flag_status
      return 'flag-class-'+ flag
    }
  }
}
</script>
