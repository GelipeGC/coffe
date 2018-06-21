/*
|-------------------------------------------------------------------------------
| routes.js
|-------------------------------------------------------------------------------
| Contains all of the routes for the application
*/

/*
    Imports Vue and VueRouter to extend with the routes.
*/
import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store.js";

/*
    Extends Vue to use Vue Router
*/
Vue.use(VueRouter);

/**
 * This will check to see if the user is authenticated or not
 */
function requireAuth(to, from, next) {
  /**
   * Determines where we should send the user
   */
  function proceed() {
    if (store.getters.getUserLoadStatus() == 2) {
      if (store.getters.getUser != "") {
        next();
      } else {
        next("/home");
      }
    }

    /**
     * Confirm the user has been loaded
     */
    if (store.getters.getUserLoadStatus != 2) {
      store.dispatch("loadUser");

      store.watch(store.getters.getUserLoadStatus, function() {
        if (store.getters.getUserLoadStatus() == 2) {
          proceed();
        }
      });
    } else {
      proceed();
    }
  }
}

export default new VueRouter({
  routes: [
    {
      path: "/",
      redirect: { name: "home" },
      name: "layout",
      component: Vue.component("Layout", require("./pages/Layout.vue")),
      children: [
        {
          path: "home",
          name: "home",
          component: Vue.component("Home", require("./pages/Home.vue"))
        },
        {
          path: "cafes",
          name: "cafes",
          component: Vue.component("Cafes", require("./pages/Cafes.vue"))
        },
        {
          path: "cafes/new",
          name: "newcafe",
          component: Vue.component("NewCafe", require("./pages/NewCafe.vue")),
          beforeEnter: requireAuth
        },
        {
          path: "cafes/:id",
          name: "cafe",
          component: Vue.component("Cafe", require("./pages/Cafe.vue"))
        },
        {
          path: "cafes/:id/edit",
          name: "editcafe",
          component: Vue.component("EditCafe", require("./pages/EditCafe.vue")),
          beforeEnter: requireAuth
        },
        {
          path: "profile",
          name: "profile",
          component: Vue.component("Profile", require("./pages/Profile.vue")),
          beforeEnter: requireAuth
        }
      ]
    }
  ]
});
