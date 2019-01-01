<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrevReturnItem extends Model
{
  protected $fillable = [
      'quantity',



  ];

    public function return(){
        return $this->belongsTo('App\PrevReturn');
     }
}
