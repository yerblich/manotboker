<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrevReturn extends Model
{
  protected $dates = ['date'];

   public function client(){
      return $this->belongsTo('App\Client');
   }
   public function PrevReturnItems(){
      return $this->hasMany('App\PrevReturnItem');
   }
}
