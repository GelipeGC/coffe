/**
 * Defines the API route we are using
 */
var api_url = '';
var google_maps_js_api = '';

switch (process.env.NODE_ENV) {
  case 'development':
    api_url = 'http://coffe.in/api/v1';
    break;

  case 'production':
    api_url = 'http://coffe.com/api/v1';
    break;
}

export const COFFE_CONFIG = {
  API_URL: api_url,
  GOOGLE_MAPS_JS_API: google_maps_js_api

}