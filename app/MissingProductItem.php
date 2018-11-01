<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MissingProductItem extends Model
{
    protected $fillable = [
        'missing_products_id',
        'product_id',
        'quantity',
        'current_price'
        
        

    ];

    public function missingProduct(){
        return $this->belongsTo('App\MissingProduct');
     }
}
