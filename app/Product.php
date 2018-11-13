<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'active',
        'supplier_id',
        'name',
        'weight',
        'supplier_price',
        'type',
        'image',
        'units'

    ];

    public function supplier(){
        return $this->belongsTo('App\Supplier');
     }
}
