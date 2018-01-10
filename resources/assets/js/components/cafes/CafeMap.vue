<template>
  <div id="cafe-map">
  </div>
  
</template>

<script>
  export default {
    props: {
      'latitude': {
        type: Number,
        default: function(){
          return 20.67
        }
      },
      'longitude': {
        type: Number,
        default: function(){
          return -103.40
        }
      },
      'zoom': {
        type: Number,
        default: function(){
          return 6
        }
      }
    },
    data() {
      return {
        markers:[],
        infoWindows:[]
      }
    },
    methods: {
      buildMarkers() {
        /*
          Initialize the markers to an empty array.
        */
        this.markers = [];

        /*
          Iterate over all of the cafes
        */
        for( var i = 0; i < this.cafes.length; i++ ){

          /*
            Create the marker for each of the cafes and set the
            latitude and longitude to the latitude and longitude
            of the cafe. Also set the map to be the local map.
          */
          var image = 'img/coffee-icon-png-13684.png';
          var marker = new google.maps.Marker({
            position: { lat: parseFloat( this.cafes[i].latitude ), lng: parseFloat( this.cafes[i].longitude ) },
            map: this.map,
            icon:image
          });

          /**
            create info window and add it ti the loca
          */
          let infoWindow = new google.maps.InfoWindow({
            content: this.cafes[i].name
          });
          this.infoWindows.push(infoWindow);

          /*
            Push the new marker on to the array.
          */
          this.markers.push( marker );

          /**
            Add the event listener to open the info window for the marker 
          */
          marker.addListener('click', function() {
            infoWindow.open(this.map, this);
          });

        }
      },
      /*
        Clears the markers from the map.
      */
      clearMarkers(){
        /*
          Iterate over all of the markers and set the map
          to null so they disappear.
        */
        for( var i = 0; i < this.markers.length; i++ ){
          this.markers[i].setMap( null );
        }
      }
    },
    computed: {
      cafes() {
        return this.$store.getters.getCafes;
      }
    },
    mounted() {
       /*
        We don't want the map to be reactive, so we initialize it locally,
        but don't store it in our data array.
      */
      this.map = new google.maps.Map(document.getElementById('cafe-map'), {
        center: {lat: this.latitude, lng: this.longitude},
        zoom: this.zoom
      });
      /*
        Clear and re-build the markers
      */
      this.clearMarkers();
      this.buildMarkers();
    },
    watch: {
      /*
        Watches the cafes. When they are updated, clear the markers
        and re build them.
      */
      cafes(){
        this.clearMarkers();
        this.buildMarkers();
      }
    }
  }
</script>

<style lang="scss">
  div#cafe-map{
    position:absolute;
    top:50px;
    left:0px;
    right:0px;
    bottom:100px;
  }
</style>