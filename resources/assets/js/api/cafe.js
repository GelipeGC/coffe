/**
 *  Import the Caffe API URL  from the config
 */
import { COFFE_CONFIG } from "../config.js";

export default {
  /**
   * GET /api/v1/cafes
   */

  getCafes: function() {
    return axios.get(COFFE_CONFIG.API_URL + "/cafes");
  },

  /**
   *  GET /api/v1/cafes/{cafeID}
   */

  getCafe: function(cafeID) {
    return axios.get(COFFE_CONFIG.API_URL + "/cafes/" + cafeID);
  },

  /**
   *  POST /api/v1/cafes
   */
  postAddNewCafe: function(
    name,
    locations,
    website,
    description,
    roaster,
    picture
  ) {
    /**
     * Initialize the form data
     */
    let formData = new FormData();
    formData.append("name", name);
    formData.append("locations", JSON.stringify(locations));
    formData.append("website", website);
    formData.append("descriprion", description);
    formData.append("roaster", roaster);
    formData.append("picture", picture);

    return axios.post(ROAST_CONFIG.API_URL + "/cafes", formData, {
      headers: {
        "Content-type": "multipart/form-data"
      }
    });
  },
  /**
   * POST /api/v1/cafes/{cafeID}/like
   */
  postLikeCafe: function(cafeID) {
    return axios.post(ROAST_CONFIG.API_URL + "/cafes/" + cafeID + "/like");
  },

  /**
   * DELETE /api/v1/cafes/{cafeID/like}
   */
  deleteLikeCafe: function(cafeID) {
    return axios.delete(ROAST_CONFIG.API_URL + "/cafes/" + cafeID + "/like");
  },

  /*
    PUT   /api/v1/cafes/{id}
  */
  putEditCafe: function(
    id,
    name,
    locations,
    website,
    description,
    roaster,
    picture
  ) {
    /*
      Initialize the form data
    */
    let formData = new FormData();

    /*
      Add the form data we need to submit
    */
    formData.append("name", name);
    formData.append("locations", JSON.stringify(locations));
    formData.append("website", website);
    formData.append("description", description);
    formData.append("roaster", roaster);
    formData.append("picture", picture);

    return axios.put(ROAST_CONFIG.API_URL + "/cafes/" + id, formData, {
      headers: {
        "Content-Type": "multipart/form-data"
      }
    });
  }
};
