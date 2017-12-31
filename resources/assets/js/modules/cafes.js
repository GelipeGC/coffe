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
    cafeLoadStatus:0
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
      commit('setCafeLoadStatus', 1);

      CafeAPI.getCafe(data.id)
              .then(function(response){
                commit('setCafe', response.data);
                commit('setCafeLoadStatus', 2);
              }).catch(function(){
                commit('setCafe', []);
                commit('setCafeLoadStatus', 3);
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
    }
  }
}