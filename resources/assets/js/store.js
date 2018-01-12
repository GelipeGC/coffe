/*
  Adds the promise polyfill for IE 11
*/
require('es6-promise').polyfill();

/**
 * Imports vue and vuex
 */
import Vue from 'vue'
import Vuex from 'vuex'

/**
 * initialize vuex in vue
 */
Vue.use(Vuex)

/*
    Imports all of the modules used in the application to build the data store.
*/
import { cafes } from './modules/cafes.js'
/**
 * imports all of the modules use in the application to build data store
 */
import { brewMethods } from './modules/brewMethods.js'

/**
 * export our data store
 */

 export default new Vuex.Store({
   modules: {
    cafes,
    brewMethods
   }
 });