<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected  $fillable = ['name'];

    /**
     * Get user that owns a lesson
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    /**
     * Get all tags for a lesson
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany 
     */
    public function tags()
    {
    	return $this->belongsToMany('App\Tag');
    }
}
