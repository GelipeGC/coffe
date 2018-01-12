/**
 * Vuex modules/cafe.js
 * the vuex data store for the cafes
 */
import CafeAPI from '../api/cafe.js';

export const cafes = {
  state: {
    cafes:[],
    cafesLoadStatus:0,
    cafe:{},
    cafeLoadStatus:0,

    cafeAddStatus:0,
    cafeLikeActionStatus:0,
    cafeUnLikeActionStatus:0,

    cafeLiked: false,
  },

  actions: {
    loadCafes({commit}) {
      commit('setCafesLoadStatus', 1);

      CafeAPI.getCafes()
              .then(function(response){
                commit('setCafes', response.data);
                commit('setCafesLoadStatus', 2);
              }).catch(function(){
                commit('setCafes', []);
                commit('setCafesLoadStatus', 3);
              });
    },
    loadCafe({commit}, data) {
      commit('setCafeLikedStatus', false);
      commit('setCafeLoadStatus', 1);

      CafeAPI.getCafe(data.id)
              .then(function(response){
                commit('setCafe', response.data);
                if (response.data.user_like.length > 0) {
                  commit('setCafeLikedStatus', true);
                }
                commit('setCafeLoadStatus', 2);
              }).catch(function(){
                commit('setCafe', {});
                commit('setCafeLoadStatus', 3);
              });
    },
    /**
     * adds a cafe
     */
    addCafe( { commit, state, dispatch }, data ){
      commit( 'setCafeAddedStatus', 1 );
    
      CafeAPI.postAddNewCafe( data.name, data.locations, data.website, data.description, data.roaster )
          .then( function( response ){
            commit( 'setCafeAddedStatus', 2 );
            dispatch( 'loadCafes' );
          })
          .catch( function(){
            commit( 'setCafeAddedStatus', 3 );
          });
    },
    /**
     * Likes a cafe
     */
    likeCafe( { commit, state}, data) {
      commit( 'setCafeLikeActionStatus',1);

      CafeAPI.postLikeCafe(data.id)
              .then(function(response){
                commit('setCafeLikedStatus', true);
                commit('setCafeLikeActionStatus', 2);
              }).catch(function(){
                commit('setCafeLikedActionStatus', 3);
              });
    },
    /**
     * UnLikes a cafe
     */
    unlikeCafe( { commit, state}, data){
      commit('setCafeUnlikeActionStatus', 1);

      CafeAPI.deleteLikeCafe(data.id)
              .then(function(response) {
                commit('setCafeLikedStatus', false);
                commit('setCafeUnlikeActionStatus', 2);
              }).catch(function(){
                commit('setCafeUnlikeActionStatus',3);  
              });
    }
  },
  mutations: {
    setCafesLoadStatus(state, status) {
      state.cafesLoadStatus = status;
    },
    setCafes(state, status) {
      state.cafes = cafes;
    },
    setCafeLoadStatus(state, status) {
      state.cafeLoadStatus = status;
    },
    setCafe(state, status) {
      state.cafe = cafe;
    },
    setCafeAddedStatus( state, status) {
      state.cafeAddStatus = status;
    },
    /**
     * set the cafe liked status
     */
    setCafeLikedStatus(state, status) {
      state.cafeLiked = status;
    },
    /**
     * Set the cafe like action status
     */
    setCafeLikeActionStatus(state, status) {
      state.cafeLikeActionStatus = status;
    },
    /**
     * Set the cafe unlike action status
     */
    setCafeUnlikeActionStatus(state, status) {
      state.cafeUnLikeActionStatus = status;
    }

  },
  /**
   * defined the getters used by module 
   */
  getters: {
    /**
     * return the cafes load status
     */
    getCafesLoadStatus(state) {
      return state.cafesLoadStatus;
    },
    /**
     * return the cafes
     */
    getCafes(state) {
      return state.cafes;
    },
    /**
     * return the cafe load status
     */
    getCafeLoadStatus(state) {
      return state.cafeLoadStatus;
    },
    /**
     * return the cafe
     */
    getCafe(state) {
      return state.cafe;
    },
    /**
     * gets the cafe add status
     */
    getCafeAddStatus(state) {
      return state.cafeAddStatus;
    },
    /**
     * Gets the cafe liked status
     */
    getCafeLikedStatus(state) {
      return state.cafeLiked;
    },
    /**
     * Gets the cafe liked action status
     */
    getCafeLikeActionStatus(state) {
      return state.cafeLikeActionStatus;
    },
    /**
     * Gets the cafe unlike action status
     */
    getCafeUnlikeActionStatus(state) {
      return state.cafeUnLikeActionStatus;
    }
  }
}