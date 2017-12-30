/**
 *  Import the Caffe API URL  from the config
 */
import {COFFE_CONFIG} from '../config.js';

export default {
  /**
   * GET /api/v1/cafes
  */

  getCafes: function () {
    return axios.get( COFFE_CONFIG.API_URL + '/cafes');
  },
  
  /**
   *  GET /api/v1/cafes/{cafeID}
   */

  getCafe: function(cafeID) {
    return axios.get( COFFE_CONFIG.API_URL + '/cafes/' + cafeID);
  },

   /**
    *  POST /api/v1/cafes
    */
  postAddNewCafe: function(name, addres, city,state,zip) {
    return axios.post( COFFE_CONFIG.API_URL + '/cafes',
            {
              name: name,
              addres: addres,
              city: city,
              state: state,
              zip: zip
            }
          );
  }
}