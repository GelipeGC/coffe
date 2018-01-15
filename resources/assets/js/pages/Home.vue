<template>
  <div id="home">
    <span v-show="cafesLoadStatus == 1">Loading</span>
    <span v-show="cafesLoadStatus == 2">Cafes loaded successfully!</span>
    <span v-show="cafesLoadStatus == 3">Cafes loaded unsuccessfully!</span>
    <ul>
      <li v-for="cafe in cafes">{{ cafe.name }}</li>
    </ul>
  </div>
</template>

<script>
  import CafeFilter from '../components/cafes/CafeFilter.vue';

  export default {

    created () {
      this.$store.dispatch('loadCafes');
    },
    computed: {
      cafesLoadStatus() {
        return this.$store.getters.getCafesLoadStatus;
      },
      cafes() {
        return this.$store.getters.getCafes;
      }
    }
  }
  
</script>

<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#home{
    a.add-cafe-button{
      float: right;
      display: block;
      margin-top: 10px;
      margin-bottom: 10px;
      background-color: $dark-color;
      color: white;
      padding-top: 5px;
      padding-bottom: 5px;
      padding-left: 10px;
      padding-right: 10px;
    }
  }
</style>