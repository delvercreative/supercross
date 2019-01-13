<template>
  <div class="card text-center">
    <div class="card-header">
      Confirm
    </div>
    <div class="card-body">
      <h5 class="card-title">Confirm entry into {{raceName}}</h5>
      <p class="card-text">Entry fee is ${{fee}}</p>
      <button @click="click" type="button" class="btn btn-success">Confirm</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'confirm-button',
  props: ['click', 'raceId', 'raceName', 'fee']
}
</script>
