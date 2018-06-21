<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCafeRequest;
use App\Utilities\GoogleMaps;

use App\Utilities\Tagger;

use App\Models\Cafe;
use App\Models\CafePhoto;
use Auth;
use DB;
use File;

class CafesController extends Controller
{
    /*
    |-------------------------------------------------------------------------------
    | Get All Cafes
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes
    | Method:         GET
    | Description:    Gets all of the cafes in the application
    */
    public function getCafes()
    {
         $cafes = Cafe::with('brewMethods')
                        ->with(['tags' => function($query){
                            $query->select('tag');
                        }])
                        ->get();

        return response()->json($cafes);

    }

    /*
    |-------------------------------------------------------------------------------
    | Get An Individual Cafe
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes/{id}
    | Method:         GET
    | Description:    Gets an individual cafe
    | Parameters:
    |   $id   -> ID of the cafe we are retrieving
    */
    public function getCafe($id)
    {
        $cafe = Cafe::where('id', '=', $id)
                    ->with('brewMethods')
                    ->with('userLike')
                    ->with('tags')
                    ->first();

        return response()->json($cafe);

    }
    /*
    |-------------------------------------------------------------------------------
    | Adds a New Cafe
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes
    | Method:         POST
    | Description:    Adds a new cafe to the application
    */
    public function postNewCafe(StoreCafeRequest $request)
    {
        $addedCafes = array();

        $locations = json_decode($request->get('locations'));

        /*
        Create a parent cafe and grab the first location
        */
        $parentCafe = new Cafe();

        $address            = $locations[0]['address'];
        $city                   = $locations[0]['city'];
        $state                  = $locations[0]['state'];
        $zip                        = $locations[0]['zip'];
        $locationName       = $locations[0]['name'];
        $brewMethods        = $locations[0]['methodsAvailable'];
        $tags               = $locations[0]['tags'];

        /*
        Get the Latitude and Longitude returned from the Google Maps Address.
        */
        $coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );

        $parentCafe->name                   = $request->get('name');
        $parentCafe->location_name  = $locationName != '' ? $locationName : '';
        $parentCafe->address                = $address;
        $parentCafe->city                   = $city;
        $parentCafe->state                  = $state;
        $parentCafe->zip                        = $zip;
        $parentCafe->latitude           = $coordinates['lat'];
        $parentCafe->longitude          = $coordinates['lng'];
        $parentCafe->roaster                = $request->get('roaster') != '' ? 1 : 0;
        $parentCafe->website                = $request->get('website');
        $parentCafe->description        = $request->get('description') != '' ? $request->get('description') : '';
        $parentCafe->added_by           = Auth::user()->id;

        /*
        Save parent cafe
        */
        $parentCafe->save();

        $photo = Request::file('picture');

        if (count($photo) > 0) {
            if ($photo != null && $photo->isValid()) {
               if (!File::exists(app_path().'/Photos/'.$parentCafe->id.'/')) {
                   File::makeDirectory(app_path().'/Photos/'.$parentCafe->id.'/');
               } 
               $destinationPath = app_path().'/Photos/'.$parentCafe->id;

               $filename = time().'-'.$photo->getClientOriginalName();

               $photo->move($destinationPath, $filename);

               $cafePhoto = new CafePhoto();

               $cafePhoto->cafe_id = $parentCafe->id;
               $cafePhoto->uploaded_by = Auth::user()->id;
               $cafePhoto->file_url = $destinationPath.'/';
               $cafePhoto->save();
            }
        }

        /*
        Attach the brew methods
        */
        $parentCafe->brewMethods()->sync( $brewMethods );
        /**
         * tags the cafe
         */
        Tagger::tagCafe($parentCafe, $tags);

        array_push( $addedCafes, $parentCafe->toArray() );

        /*
        Now that we have the parent cafe, we add all of the other
        locations. We have to see if other locations are added.
        */
        if( count( $locations ) > 1 ){
            /*
                We off set the counter at 1 since we already used the
                first location.
            */
            for( $i = 1; $i < count( $locations ); $i++ ){
                /*
                Create a cafe and grab the location
                */
                $cafe = new Cafe();

                $address            = $locations[$i]['address'];
                $city                   = $locations[$i]['city'];
                $state                  = $locations[$i]['state'];
                $zip                        = $locations[$i]['zip'];
                $locationName       = $locations[$i]['name'];
                $brewMethods        = $locations[$i]['methodsAvailable'];

                /*
                Get the Latitude and Longitude returned from the Google Maps Address.
                */
                $coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );

                $cafe->parent               = $parentCafe->id;
                $cafe->name                     = $request->get('name');
                $cafe->location_name    = $locationName != '' ? $locationName : '';
                $cafe->address              = $address;
                $cafe->city                     = $city;
                $cafe->state                    = $state;
                $cafe->zip                      = $zip;
                $cafe->latitude             = $coordinates['lat'];
                $cafe->longitude            = $coordinates['lng'];
                $cafe->roaster              = $request->get('roaster') != '' ? 1 : 0;
                $cafe->website              = $request->get('website');
                $cafe->description      = $request->get('description') != '' ? $request->get('description') : '';
                $cafe->added_by             = Auth::user()->id;

                /*
                Save cafe
                */
                $cafe->save();

                /*
                Attach the brew methods
                */
                $cafe->brewMethods()->sync( $brewMethods );

                $tags = $locations[$i]['tags'];

                /**
                 * Tags the cafe
                 */
                Tagger::tagCafe($cafe, $tags);

                array_push( $addedCafes, $cafe->toArray() );
            }
        }

        /*
        Return the added cafes as JSON
        */
        return response()->json($addedCafes, 201);
        
    }
    /*
	|-------------------------------------------------------------------------------
	| Edits a Cafe
	|-------------------------------------------------------------------------------
	| URL:            /api/v1/cafes/{cafeID}
	| Method:         PUT
	| Description:    Edits a cafe
	*/
	public function putEditCafe( $cafeID, EditCafeRequest $request ){
		$editedCafes = array();
		$locations = json_decode( $request->get('locations') );
		/*
			Get the cafe we are editing.
		*/
		$cafe = Cafe::where('id', '=', $cafeID )->first();
		$address  			= $locations[0]->address;
		$city 					= $locations[0]->city;
		$state 					= $locations[0]->state;
		$zip 						= $locations[0]->zip;
		$locationName		= $locations[0]->name;
		$brewMethods 		= $locations[0]->methodsAvailable;
		$tags 					= $locations[0]->tags;
		/*
			Get the Latitude and Longitude returned from the Google Maps Address.
		*/
		$coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );
		$cafe->name 					= $request->get('name');
		$cafe->location_name	= $locationName != '' ? $locationName : '';
		$cafe->address 				= $address;
		$cafe->city 					= $city;
		$cafe->state 					= $state;
		$cafe->zip 						= $zip;
		$cafe->latitude 			= $coordinates['lat'];
		$cafe->longitude 			= $coordinates['lng'];
		$cafe->roaster 				= $request->get('roaster') != '' ? 1 : 0;
		$cafe->website 				= $request->get('website');
		$cafe->description		= $request->get('description') != '' ? $request->get('description') : '';
		$cafe->save();
		$photo = Request::file('picture');
		if( count( $photo ) > 0 ){
			if( $photo != null && $photo->isValid() ){
				/*
					Creates the cafe directory if needed
				*/
				if( !File::exists( app_path().'/Photos/'.$cafe->id.'/' ) ){
					File::makeDirectory( app_path() .'/Photos/'.$cafe->id.'/' );
				}
				/*
					Sets the destination path and moves the file there.
				*/
				$destinationPath = app_path().'/Photos/'.$cafe->id;
				/*
					Grabs the filename and file type
				*/
				$filename = time().'-'.$photo->getClientOriginalName();
				/*
					Moves to the directory
				*/
				$photo->move( $destinationPath, $filename );
				/*
					Creates a new record in the database.
				*/
				$cafePhoto = new CafePhoto();
				$cafePhoto->cafe_id = $cafe->id;
				$cafePhoto->uploaded_by = Auth::user()->id;
				$cafePhoto->file_url = app_path() .'/Photos/'.$cafe->id.'/';
				$cafePhoto->save();
			}
		}
		$cafe->brewMethods()->sync( $brewMethods );
		/*
			Clear the user's tags on the cafe. These will get
			re-added in the next step
		*/
		DB::statement('DELETE FROM cafes_users_tags WHERE cafe_id = "'.$cafeID.'" AND user_id = "'.Auth::user()->id.'"');
		/*
			Tag the cafe with the tags provided by the user.
		*/
		Tagger::tagCafe( $cafe, $tags );
		array_push( $editedCafes, $cafe->toArray() );
		/*
			When editing a cafe, we want either the parent of the cafe we just edited
			since it would have been a child cafe. Otherwise, the parent id is the ID of the
			cafe we edited.
		*/
		$parentID = $cafe->parent != null ? $cafe->parent : $cafe->id;
		/*
			Now that we have the parent cafe edited, we add all of the other
			locations. We have to see if other locations are added.
		*/
		if( count( $locations ) > 1 ){
			/*
				We off set the counter at 1 since we already used the
				first location.
			*/
			for( $i = 1; $i < count( $locations ); $i++ ){
				/*
					Create a cafe and grab the location
				*/
				$cafe = new Cafe();
				$address  			= $locations[$i]->address;
				$city 					= $locations[$i]->city;
				$state 					= $locations[$i]->state;
				$zip 						= $locations[$i]->zip;
				$locationName		= $locations[$i]->name;
				$brewMethods 		= $locations[$i]->methodsAvailable;
				/*
					Get the Latitude and Longitude returned from the Google Maps Address.
				*/
				$coordinates = GoogleMaps::geocodeAddress( $address, $city, $state, $zip );
				$cafe->parent 				= $parentID;
				$cafe->name 					= $request->get('name');
				$cafe->location_name	= $locationName != '' ? $locationName : '';
				$cafe->address 				= $address;
				$cafe->city 					= $city;
				$cafe->state 					= $state;
				$cafe->zip 						= $zip;
				$cafe->latitude 			= $coordinates['lat'];
				$cafe->longitude 			= $coordinates['lng'];
				$cafe->roaster 				= $request->get('roaster') != '' ? 1 : 0;
				$cafe->website 				= $request->get('website');
				$cafe->description		= $request->get('description') != '' ? $request->get('description') : '';
				$cafe->added_by 			= Auth::user()->id;
				/*
					Save cafe
				*/
				$cafe->save();
				/*
					Attach the brew methods
				*/
				$cafe->brewMethods()->sync( $brewMethods );
				$tags = $locations[$i]['tags'];
				/*
					Tags the cafe
				*/
				Tagger::tagCafe( $cafe, $tags );
				array_push( $editedCafes, $cafe->toArray() );
			}
		}
		/*
			Return the edited cafes as JSON
		*/
		return response()->json( $editedCafes, 200 );
	}

    public function postLikeCafe($cafeID)
    {
        $cafe = Cafe::where('id','=',$cafeID)->first();

        if (!$cafe->likes->contains(Auth::users()->id)) {
            /**
             * If the user doesnt already like the cafe, attaches the cafe to the users likes
             */
            $cafe->likes()->attach(Auth::users()->id, [
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
        }

        return response()->json(['cafe_liked' => true], 201);
    }

    public function deleteLikeCafe($cafeID)
    {
        $cafe = Cafe::where('id', '=', $cafeID)->first();

        $cafe->likes()->detach(Auth::user()->id);

        return response(null, 204);
    }
    /*
    |-------------------------------------------------------------------------------
    | Adds Tags To A Cafe
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes/{id}/tags
    | Controller:     API\CafesController@postAddTags
    | Method:         POST
    | Description:    Adds tags to a cafe for a user
    */
    public function postAddTags($cafeID )
    {
        $tags = Request::get('tags');

        $cafe = Cafe::where('id','=',$cafeID)->first();

        Tagger::tagCafe($cafe, $tags);

        /**
         * grabs the cafe with the brew methods, user like and tags
         */
        $cafe = Cafe::where('id','=', $cafeID)
                    ->with('brewMethods')
                    ->with('userLike')
                    ->with('tags')
                    ->first();

        return response()->json($cafe,201);

    }

    /*
    |-------------------------------------------------------------------------------
    | Deletes A Cafe Tag
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes/{id}/tags/{tagID}
    | Method:         DELETE
    | Description:    Deletes a tag from a cafe for a user
    */
    public function deleteCafeTag( $cafeID, $tagID )
    {
        DB::statement('DELETE FROM cafes_users_tags WHERE cafe_id = "'.$cafeID.'" AND tag_id = "'.$tagID.'" AND user_id = "'.Auth::user()->id.'"');

        return response(null, 204);
    }
}
