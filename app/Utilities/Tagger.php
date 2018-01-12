<?php

namespace App\Utilities;

use App\Models\Tag;

class Tagger 
{
  public static function tagCafe($cafe, $tags)
  {
    /**
     * Iterete over all of the tags attaching each one to the coffe
     */
    foreach ($tags as $tag) {
      $name = self::genereteTagName($tag);
      /**
       * Get the tag by name or create a new tag
       */
      $newCafeTag = Tag::firstOrNew(['name' => $name]);
      
      $newCafeTag->tag = $name;

      $newCafeTag->save();

      /*
      Apply the tag to the cafe
      */
      $cafe->tags()->syncWithoutDetaching( [ $newCafeTag->id => ['user_id' => Auth::user()->id ] ] );
    }

  }

  public static function genereteTagName($tagName) 
  {
    /**
     * Trim whitspaces from beginning and end of tag
     */
    $name = trim($tagName);
    /**
     * Convert tag name to lower
     */
    $name = strtolower($name);
    /**
     * Convert anything not a letter or number to a dash
     */
    $name = preg_replace('/[^a-zA-Z0-9]/', '-', $name );
    /**
     * Remove multiple instance of '-' and group to one
     */
    $name = preg_replace( '/-{2,}/', '-', $name );
    /**
     * Get rid of leading and trailing '-'
     */
    $name = trim($name, '-');
    /**
     * Returns the cleaned tag name
     */
    return $name;
  }
  
}
