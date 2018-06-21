/**
 * Imports the Roats API URL from the config
 */
import {COFFE_CONFIG} from '../config.js';

export default {
  /**
   * GET /api/v1/user
   */

   getUser: function() {
     return axios.get(COFFE_CONFIG.API_URL + '/user');
   },

  /**
   * PUT /api/v1/user
   */
  putUpdateUser: function(public_visibility, favorite_coffe, flavor_notes,city,state) {
    return axios.put(COFFE_CONFIG.API_URL + '/user',
    {
      public_visibility: public_visibility,
      favorite_coffe: favorite_coffe,
      flavor_notes: flavor_notes,
      city: city,
      state: state
    })
  }
}