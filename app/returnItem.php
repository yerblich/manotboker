<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class returnItem extends Model
{
  protected $fillable = [
      'quantity',


  ];

    public function return(){
        return $this->belongsTo('App\ProductReturn');
     }




    }
