<template>
  <div id="mainTopNav" class="dark-bg">
    <div class="nav-inner">
      <div class="nav-left">
        <div @click="toggleNav" id="menuExpand">
          <i class="fas fa-bars"></i>
        </div>
      </div>
      <div class="nav-logo">
        <img :src="logo" alt="logo">
      </div>
      <div class="nav-right"></div>
    </div>
  </div>
</template>


<script>
export default {
  name: 'main-nav',
  props: ['logo'],
  data(){
    return {

    }
  },
  methods: {
    toggleNav() {
      let selection = document.querySelector('#expandNav')
      selection.classList.add('slide-in');
      setTimeout(function() {
        selection.classList.remove('slide-in');
        selection.classList.add('expanded');
      }, 700)
    },
    

  }
}
    
</script>