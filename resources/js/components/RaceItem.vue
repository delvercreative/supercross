<template>
    <button @click="testFun">Click</button>
</template>

<script>
export default {
  name: 'race-item',
  props: ['image', 'user', 'raceId', 'raceName'],
  data(){
    return {

    }
  },
  methods: {
    testFun() {
      this.$root.$emit('test', 'This is a test');
    },
  }
  
}
</script>
