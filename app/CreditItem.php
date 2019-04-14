<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditItem extends Model
{
  protected $fillable = [
      'product_amount',
      'description',
      'unit_price',
      'total_credit'


  ];

  public function credit(){
      return $this->belongsTo('App\Credit');
   }
}
