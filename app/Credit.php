<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
  protected $fillable = [
      'client_id',
      'amount'

  ];

  public function creditItems(){
     return $this->hasMany('App\CreditItem');
  }
  public function client(){
     return $this->belongsTo('App\Client');
  }
}
