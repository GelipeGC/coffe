<template>
  <div id="individual-cafe-map">

  </div>
</template>

<script>
  export default {
    computed: {
      cafeLoadStatus() {
        return this.$store.getters.getCafeLoadStatus;
      },
      cafe() {
        return this.$store.getters.getCafe;
      }
    },
    watch: {
      cafeLoadStatus() {
        if(this.cafeLoadStatus == 2) {
          this.displayIndividualCafeMap();
        }
      }
    },
    methods: {
      displayIndividualCafeMap(){
        this.map = new google.maps.Map(document.getElementById('individual-cafe-map'), {
          center: {lat: parseFloat( this.cafe.latitude ), lng: parseFloat( this.cafe.longitude )},
          zoom: 13 
        });

        var image = '/img/coffe-marker.png';

        var marker = new google.maps.Marker({
          position: { lat: parseFloat(this.cafe.latitude), lng: parseFloat(this.cafe.longitude)}, map: this.map,
          icon: image
        });
      }
    }
  }
  
</script>

<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#individual-cafe-map{
    width: 700px;
    height: 500px;
    margin: auto;
    margin-bottom: 200px;
  }
</style>