<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    protected $table = "cafes";


    public function brewMethods()
    {
        return $this->belongsToMany(BrewMethod::class,'cafes_brew_methods','cafe_id','brew_method_id');
    }

    
    public function children() 
    {
        return $this->hasMany(Cafe::class,'parent','id');
    }
    public function parent()
    {
        return $this->hasOne(Cafe::class, 'id','parent');
    }
    
    public function likes()
    {
        return $this->belongsToMany(User::class, 'users_cafes_likes','cafe_id','user_id');
    }

    public function userLike() 
    {
        return $this->belongsToMany(User::class, 'users_cafes_likes','cafe_id','user_id')->where('user_id', auth()->id());
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'cafes_users_tags','cafe_id','user_id','tag_id');
    }

    public function photos()
    {
        return $this->hasMany(CafePhoto::class, 'id', 'cafe_id');
    }

    
}
