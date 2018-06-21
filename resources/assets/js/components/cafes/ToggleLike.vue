<template>
  <span class="toggle-like">
    <span class="like" v-on:click="likeCafe( cafe.id )" v-if="!liked && cafeLoadStatus == 2 && cafeLikeActionStatus != 1 && cafeUnlikeActionStatus != 1">
        Like
    </span>
    <span class="un-like" v-on:click="unlikeCafe( cafe.id )" v-if="liked && cafeLoadStatus == 2 &&cafeLikeActionStatus != 1 && cafeUnlikeActionStatus != 1">
      Un-like
    </span>
    <loader v-show="cafeLikeActionStatus == 1 || cafeUnlikeActionStatus == 1"
        :width="30"
        :height="30"
        :display="'inline-block'">
    </loader>
  </span>
</template>
<script>
import Loader from '../global/Loader.vue';

 export default {
   components: {
     Loader
   },
   computed: {
     /**
      Gets the cafe load status from the Vuex state
      */
    cafeLoadStatus() {
      return this.$store.getters.getCafeLoadStatus;
    },
    /**
      Gets the cafe from the vuex state
     */
    cafe() {
      return this.$store.getters.getCafe;
    },
    /**
      Determines if the cafe is liked or not
     */
    liked() {
      return this.$store.getters.getCafeLikedStatus;
    },
    /**
      Determine if the cafe is still processing the like action
     */
    cafeLikeActionStatus() {
      return this.$store.getters.getCafeLikeActionStatus;
    },
    /**
      determines if the cafe is still proccessing the un-like action
     */
    cafeUnlikeActionStatus() {
      return this.$store.getters.getCafeUnLikeActionStatus;
    }
    //NO PUEDO SACARTE DE MI <head> <3
   },
   methods: {
     likeCafe(cafeID) {
       this.$store.dispatch('likeCafe', {
         id: this.cafe.id
       })
     },
     unlikeCafe(cafeID) {
       this.$store.dispatch('unlikeCafe', {
         id: this.cafe.id
       });
     }
   }
 } 
</script>