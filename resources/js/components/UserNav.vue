<template>
  <div id="userHeader">
    <div class="nav-inner">
      <div class="user-account">
          <i class="fas fa-user"></i>
          <span class="user-name">{{user}}</span>
      </div>
      <div class="user-balance">
        <span class="balance-amount">
          <div class="sm-txt">Balance</div>
          <div class="balance-amount-val">${{newBalance}}</div>
        </span>
        <a :href="dlink" class="deposit-link">
        <span class="add-funds"><i class="fas fa-plus"></i></span>
        </a>
      </div>
    </div>
</div>
</template>

<script>
export default {
  name: 'user-nav',
  props: ['user', 'balance', 'user_id', 'dlink'],
  data() {
    return {
      newBalance: this.balance
    }
  },
  created() {

  },
  mounted() {
    this.$root.$on('balance', data => {
      this.newBalance = data;
          console.log(data);
      });
  },
  
  methods: {
  }
}
</script>
