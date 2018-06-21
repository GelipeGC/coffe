/*
	Imports the Roast API URL from the config.
*/
import { COFFE_CONFIG } from "../config.js";

export default {
  /*
    GET   /api/v1/brew-methods
  */
  getBrewMethods: function() {
    return axios.get(COFFE_CONFIG.API_URL + "/brew-methods");
  }
};
