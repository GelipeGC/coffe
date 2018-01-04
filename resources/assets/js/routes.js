/*
|-------------------------------------------------------------------------------
| routes.js
|-------------------------------------------------------------------------------
| Contains all of the routes for the application
*/

/*
    Imports Vue and VueRouter to extend with the routes.
*/
import Vue from 'vue'
import VueRouter from 'vue-router'

/*
    Extends Vue to use Vue Router
*/
Vue.use( VueRouter )

export default new VueRouter({
  routes: [
    {
      path: '/',
      name: 'layout',
      component: Vue.component('home', require('./pages/Layout.vue')),
      children: [
        {
          path: 'home',
          name: 'home',
          component: Vue.component('home', require('./pages/Home.vue'))
        },
        {
          path: 'cafes',
          name: 'cafes',
          component: Vue.component('home', require('./pages/Cafes.vue'))
        },
        {
          path: 'cafes/new',
          name: 'newcafes',
          component: Vue.component('home', require('./pages/NewCafe.vue'))
        },
        {
          path: 'cafes/:id',
          name: 'cafe',
          component: Vue.component('home', require('./pages/Cafe.vue'))
        },
      ]
    },
    
    
  ]
})