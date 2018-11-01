<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderItem extends Model
{

    protected $fillable = [
        'quantity'
       
        

    ];
    public function order(){
        return $this->belongsTo('App\Order');
     }


     public function returnItem(){
        return $this->hasOne('App\returnItem ');
     }
    }
