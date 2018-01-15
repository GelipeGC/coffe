<template>
  <div class="grid-container">
    <div class="grid-x grid-padding-x">
      <loader v-show="cafesLoadStatus == 1" :width="100" :height="100"></loader>
      <cafe-card v-for="cafe in cafes" :key="cafe.id" :cafe="cafe"></cafe-card>
    </div>
    <router-link :to="{name:'cafe', params: {id: cafe.id}}"></router-link>
  </div>  
</template>

<script>
  import { CafeIsRoasterFilter } from '../../mixins/filters/CafeIsRoasterFilter.js';
  import { CafeBrewMethodsFilter } from '../../mixins/filters/CafeBrewMethodsFilter.js';
  import { CafeTagsFilter } from '../../mixins/filters/CafeTagsFilter.js';
  import { CafeTextFilter } from '../../mixins/filters/CafeTextFilter.js';

  import { EventBus } from '../../event-bus.js';

  export default {
    mixins: [
      CafeIsRoasterFilter,
      CafeBrewMethodsFilter,
      CafeTagsFilter,
      CafeTextFilter
    ],
    data() {
      return {
        show: true
      }
    }
    mounted(){
      EventBus.$on('filters-updated', function( filters ){
        this.processFilters( filters );
      }.bind(this));
    },
    methods: {
      processFilters(filter) {
        /*
          If no filters are selected, show the card
        */
        if( filters.text == ''
          && filters.tags.length == 0
          && filters.roaster == false
          && filters.brew_methods.length == 0 ){
            this.show = true;
        }else{
          /*
            Initialize flags for the filtering
          */
          var textPassed = false;
          var tagsPassed = false;
          var brewMethodsPassed = false;
          var roasterPassed = false;


          /*
            Check if the roaster passes
          */
          if( filters.roaster && this.processCafeIsRoasterFilter( this.cafe ) ){
            roasterPassed = true;
          }else if( !filters.roaster ){
            roasterPassed = true;
          }

          /*
            Check if text passes
          */
          if( filters.text != '' && this.processCafeTextFilter( this.cafe, filters.text ) ){
            textPassed = true;
          }else if( filters.text == '' ){
            textPassed = true;
          }

          /*
            Check if brew methods passes
          */
          if( filters.brew_methods.length != 0 && this.processCafeBrewMethodsFilter( this.cafe, filters.brew_methods ) ){
            brewMethodsPassed = true;
          }else if( filters.brew_methods.length == 0 ){
            brewMethodsPassed = true;
          }

          /*
            Check if tags passes
          */
          if( filters.tags.length != 0 && this.processCafeTagsFilter( this.cafe, filters.tags ) ){
            tagsPassed = true;
          }else if( filters.tags.length == 0 ){
            tagsPassed = true;
          }

          /*
            If everything passes, then we show the Cafe Card
          */
          if( roasterPassed && textPassed && brewMethodsPassed && tagsPassed ){
            this.show = true;
          }else{
            this.show = false;
          }
        }
      }
    }
  }
</script>