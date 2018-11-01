<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MissingProduct extends Model
{
    protected $fillable = [
        'supplier_id',
        'order_id',
        'date'
        
        

    ];
    protected $dates = ['date'];

    public function missingItems(){
        return $this->hasMany('App\MissingProductItem');
     }
     public function supplier(){
        return $this->hasOne('App\Supplier');
     }
}
