<?php

use Illuminate\Http\Request;

    Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
        Route::get('/user', function(Request $request){
            return $request->user();
        });
        /*
        |-------------------------------------------------------------------------------
        | Get All Cafes
        |-------------------------------------------------------------------------------
        | URL:            /api/v1/cafes
        | Controller:     API\CafesController@getCafes
        | Method:         GET
        | Description:    Gets all of the cafes in the application
        */

        Route::get('/cafes', 'API\CafesController@getCafes');
        /*
        |-------------------------------------------------------------------------------
        | Get An Individual Cafe
        |-------------------------------------------------------------------------------
        | URL:            /api/v1/cafes/{id}
        | Controller:     API\CafesController@getCafe
        | Method:         GET
        | Description:    Gets an individual cafe
        */
        Route::post('/cafes', 'API\CafesController@postNewCafe');
        /*
        |-------------------------------------------------------------------------------
        | Adds a New Cafe
        |-------------------------------------------------------------------------------
        | URL:            /api/v1/cafes
        | Controller:     API\CafesController@postNewCafe
        | Method:         POST
        | Description:    Adds a new cafe to the application
        */
        Route::get('/cafes/{id}', 'API\CafesController@getCafe');

        /*
        |-------------------------------------------------------------------------------
        | Get All Brew methods
        |-------------------------------------------------------------------------------
        | URL:            /api/v1/brew-methods
        | Controller:     API\BrewMethodsController@getBrewMethods
        | Method:         GET
        | Description:    Gets all of the brew methods in the application
        */
        Route::get('/brew-methods', 'API\BrewMethodsController@getBrewMethods');

        Route::post('/cafes/{id}/like', 'API\CafesController@postLikeCafe');

        Route::delete('/cafes/{id}/like', 'API\CafesController@deleteLikeCafe');

        /**
         * add tags to a cafe
         * URl: /api/v1/cafes/{id}/tags
         * Controller: API/CafesController@postAddTags
         * Method: POST
         * description: add tags to a cafe for a user
         */
        Route::post('/cafes/{id}/tags', 'API\CafesController@postAddTags');

        /*
        |-------------------------------------------------------------------------------
        | Deletes A Cafe Tag
        |-------------------------------------------------------------------------------
        | URL:            /api/v1/cafes/{id}/tags/{tagID}
        | Controller:     API\CafesController@deleteCafeTag
        | Method:         DELETE
        | Description:    Deletes a tag from a cafe for a user
        */
        Route::delete('/cafes/{id}/tags/{tagID}', 'API\CafesController@deleteCafeTag');
        /*
        |-------------------------------------------------------------------------------
        | Search Tags
        |-------------------------------------------------------------------------------
        | URL:            /api/v1/tags
        | Controller:     API\TagsController@getTags
        | Method:         GET
        | Description:    Searches the tags if a query is set otherwise returns all tags
        */
        Route::get('/tags', 'API\TagsController@getTags');
        
    });

