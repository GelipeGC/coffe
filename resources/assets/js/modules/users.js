/**
 * Vuex modules/users.js
 */
import UserAPI from "../api/user.js";

export const users = {
  state: {
    user: {},
    userLoadStatus: 0,
    userUpdateStatus: 0
  },

  actions: {
    loadUser({ commit }) {
      commit("setUserLoadStatus", 1);

      UserAPI.getUser()
        .then(function(response) {
          console.log(response);
          commit("setUser", response.data);
          commit("setUserLoadStatus", 2);
        })
        .catch(function() {
          commit("setUser", {});
          commit("setUserLoadStatus", 3);
        });
    },
    logoutUser({ commit }) {
      commit("setUserLoadStatus", 0);
      commit("setUser", {});
    },
    /**
     * Edit a user
     */
    editUser({ commit, state, dispatch }, data) {
      commit("setUserUpdateStatus", 1);

      UserAPI.putUpdateUser(
        data.public_visibility,
        data.favorite_coffe,
        data_flavor_note,
        data.city,
        data_state
      )
        .then(function(response) {
          commit("setuserUpdateStatus", 2);
          dispatch("loadUser");
        })
        .catch(function() {
          commit("setUserUpdateStatus", 3);
        });
    },
    /**
     * Logs out a user and clears the status and user pueces of state.
     */
    logoutUser({ commit }) {
      commit("setUserLoadStatus", 0);
      commit("setUser", {});
    }
  },
  mutations: {
    /**
     * Sets the user load status
     */
    setUserLoadStatus(state, status) {
      state.userLoadStatus = status;
    },

    /**
     * Sets the user
     */
    setUser(state, user) {
      state.user = user;
    },
    /**
     * Sets the user update status
     */
    setUserUpdateStatus(state, status) {
      state.userUpdateStatus = status;
    }
  },
  getters: {
    /**
     * Returns the user load status
     */
    getUserLoadStatus(state) {
      return function() {
        return state.userLoadStatus;
      };
    },
    /**
     * Returns the user
     */
    getUser(state) {
      return state.user;
    },

    /**
     * Gets the user update status
     */
    getUserUpdateStatus(state, status) {
      return state.userUpdateStatus;
    }
  }
};
