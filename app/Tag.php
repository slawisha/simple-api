<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
   protected  $fillable = ['name', 'user_id'];

   /**
    * Get all lessons that have a specific tag
    * 	
    * @return \Illuminate\Database\Eloquent\Relations\belongsToMany 
    */
   public function lessons()
   {
   		return  $this->belongsToMany('App\Lesson');
   }

   /**
    * Tag is owned by user
    *   
    * @return \Illuminate\Database\Eloquent\Relations\belongsToMany 
    */
   public function user()
   {
      return $this->belongsTo('App\User');
   }


}
