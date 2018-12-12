<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
  protected $fillable = [
      'test'


  ];


    protected $dates = ['date'];
    public function order(){
        return $this->hasOne('App\Order');
     }
     public function client(){
        return $this->belongsTo('App\Client');
     }
     public function returnItems(){
        return $this->hasMany('App\returnItem');
     }
}
